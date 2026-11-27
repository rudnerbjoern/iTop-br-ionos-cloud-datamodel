<?php
/**
 * Verify IONOS Cloud attributes, provider samples and their native iTop behavior in the disposable installation.
 *
 * The environment dispatcher starts this file in a separate PHP process.
 */

declare(strict_types=1);

namespace BR\IonosCloud\Datamodel\Tests;

use CMDBObject;
use DBObjectSearch;
use DBObjectSet;
use XMLDataLoader;
use Config;
use MetaModel;
use RuntimeException;

/**
 * Ensure both the runner and the installed iTop configuration target the reserved test database.
 *
 * @throws RuntimeException When the connection belongs to another environment.
 */
function requireTestDatabase(string $host, string $name, string $user): void
{
    if (getenv('ITOP_TEST_MODE') !== '1' || $host !== 'test-db' || $name !== 'itop_test' || $user !== 'itop_test') {
        throw new RuntimeException('This check requires the isolated iTop database.');
    }
}

requireTestDatabase((string) getenv('ITOP_TEST_DB_HOST'), (string) getenv('ITOP_TEST_DB_NAME'), (string) getenv('ITOP_TEST_DB_USER'));
$root = '/var/www/html/';
require $root . 'approot.inc.php';
require_once $root . 'core/config.class.inc.php';
$config = new Config($root . 'conf/production/config-itop.php');
requireTestDatabase($config->Get('db_host'), $config->Get('db_name'), $config->Get('db_user'));
require_once $root . 'application/startup.inc.php';

$installed = GetModulesInfo();
foreach (['br-cloud-datamodel', 'br-ionos-cloud-datamodel'] as $module) {
    if (!isset($installed[$module])) {
        throw new RuntimeException("Required module missing from compiled model: $module");
    }
}

/**
 * Return the installed provider and its public locations, keyed by the exact API identifier.
 *
 * @return array{int,array<string,int>} Database IDs of the provider and its locations.
 * @throws RuntimeException When setup produced missing, unexpected or duplicate records.
 */
function sampleKeys(): array
{
    $organizations = new DBObjectSet(DBObjectSearch::FromOQL("SELECT Organization WHERE name = 'IONOS' AND code = 'IONOS'"));
    if ($organizations->Count() !== 1) {
        throw new RuntimeException('Expected one sample provider after setup.');
    }
    $providerId = (int) $organizations->Fetch()->GetKey();
    $locations = new DBObjectSet(DBObjectSearch::FromOQL('SELECT Location WHERE org_id = :provider'), [], ['provider' => $providerId]);
    $sample = simplexml_load_file(dirname(__DIR__, 2) . '/br-ionos-cloud-datamodel/data/data.sample.ionos.xml');
    if ($sample === false || $locations->Count() !== count($sample->Location)) {
        throw new RuntimeException('Provider location count differs from the sample.');
    }
    $byCode = [];
    while ($location = $locations->Fetch()) {
        $byCode[$location->Get('name')] = $location;
    }
    $ids = [];
    foreach ($sample->Location as $entry) {
        $code = (string) $entry->name;
        $location = $byCode[$code] ?? null;
        if ($location === null || $location->Get('city') !== (string) $entry->city
            || $location->Get('country') !== (string) $entry->country || $location->Get('status') !== 'active') {
            throw new RuntimeException("Unexpected installed location: $code");
        }
        $ids[$code] = (int) $location->GetKey();
    }
    return [$providerId, $ids];
}

foreach (['CloudNatGateway', 'CloudNatGatewayLan', 'CloudNatGatewayLanAddress', 'CloudNatGatewayPublicAddress'] as $class) {
    $manual = MetaModel::NewObject($class);
    if ((string) $manual->Get('ionos_source_key') !== '') {
        throw new RuntimeException("A new $class must begin without an IONOS source.");
    }
    if (str_ends_with($class, 'Address')) {
        if ($manual->Get('ionos_synced_ip_id') !== null) {
            throw new RuntimeException("An unlinked $class must begin with a null IPAM marker.");
        }
        $manual->Set('ionos_synced_ip_id', 0);
        if ($manual->Get('ionos_synced_ip_id') !== 0) {
            throw new RuntimeException("The IPAM marker must preserve zero as an integer.");
        }
    }
}

[$providerId, $locationIds] = sampleKeys();
$vm = MetaModel::NewObject('VirtualMachine');
$vmDetails = MetaModel::FlattenZList(MetaModel::GetZListItems('VirtualMachine', 'details'));
foreach (['ionos_server_id', 'ionos_vdc_id', 'ionos_source_key', 'ionos_server_type'] as $code) {
    if ((string) $vm->Get($code) !== '' || !in_array($code, $vmDetails, true)) {
        throw new RuntimeException("Native VM reservations must expose a blank $code field.");
    }
}
$details = MetaModel::FlattenZList(MetaModel::GetZListItems('Cloud', 'details'));
if (!in_array('ionos_sec_auth_protection', $details, true)) {
    throw new RuntimeException('The Cloud details must include IONOS access protection.');
}
$cloud = MetaModel::NewObject('Cloud');
$cloud->Set('name', 'IONOS test VDC');
$cloud->Set('org_id', $providerId);
$cloud->Set('provider_id', $providerId);
$cloud->Set('provider_resource_id', '6d947532-5782-48f4-a69a-e8858ce91871');
$cloud->DBInsert();
$cloud->Reload();
if ($cloud->Get('ionos_sec_auth_protection') !== 'unknown') {
    throw new RuntimeException('A new Cloud must default to unknown IONOS access protection.');
}
foreach (['yes', 'no', 'unknown'] as $protection) {
    $cloud->Set('ionos_sec_auth_protection', $protection);
    $cloud->DBUpdate();
    $cloud->Reload();
    if ($cloud->Get('ionos_sec_auth_protection') !== $protection) {
        throw new RuntimeException("Cloud round trip failed for protection value $protection.");
    }
}
echo "IONOS Cloud attributes passed.\n";

$berlin = MetaModel::GetObject('Location', $locationIds['de/txl']);
$berlin->Set('postal_code', '10115');
$berlin->DBUpdate();

// A local organization may use the same location name. Exercise the installer's reconciliation mode.
$otherProvider = MetaModel::NewObject('Organization');
$otherProvider->Set('name', 'Other integration test provider');
$otherProvider->DBInsert();
$otherBerlin = MetaModel::NewObject('Location');
$otherBerlin->Set('org_id', $otherProvider->GetKey());
$otherBerlin->Set('name', 'de/txl');
$otherBerlin->DBInsert();

require_once $root . 'setup/xmldataloader.class.inc.php';
$loader = new XMLDataLoader();
$loader->StartSession(CMDBObject::GetCurrentChange());
$loader->LoadFile(dirname(__DIR__, 2) . '/br-ionos-cloud-datamodel/data/data.sample.ionos.xml', false, true);
if (!$loader->EndSession(true) || $loader->GetCountCreated() !== 0
    || sampleKeys() !== [$providerId, $locationIds]
    || MetaModel::GetObject('Location', $locationIds['de/txl'])->Get('postal_code') !== '10115'
    || (int) MetaModel::GetObject('Location', $otherBerlin->GetKey())->Get('org_id') !== (int) $otherProvider->GetKey()) {
    throw new RuntimeException('Native key reconciliation failed to preserve existing records.');
}
echo "Optional provider samples, ownership and repeat import with native reconciliation passed.\n";
