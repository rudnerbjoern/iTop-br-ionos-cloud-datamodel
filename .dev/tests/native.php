<?php
/** Validate the bundle descriptor, module registration and iTop data model schemas. */

declare(strict_types=1);

namespace BR\IonosCloud\Datamodel\Tests;

use DOMDocument;
use RuntimeException;
use SimpleXMLElement;

require_once __DIR__ . '/../../scripts/release-version.php';

$root = dirname(__DIR__, 2);
$schemas = getenv('ITOP_SCHEMA_DIR') ?: $root . '/.tmp/schemas';
$ionos = IONOS_MODULE;
projectVersion($root);
foreach (['extension.xml' => 'extension.xsd', "$ionos/datamodel.$ionos.xml" => '3.3/itop_design.xsd'] as $file => $schema) {
    $xml = new DOMDocument();
    if (!$xml->load("$root/$file", LIBXML_NONET) || !$xml->schemaValidate("$schemas/$schema")) {
        throw new RuntimeException("Schema validation failed for $file using $schemas/$schema");
    }
}
/** Capture the real setup registration during a native check without loading the iTop runtime. */
final class RegistrationCapture
{
    /** @var array<string,mixed> Module metadata passed to the installer. */
    public static array $metadata = [];

    /**
     * Save the metadata for the registered module.
     *
     * @param array<string,mixed> $metadata Setup parameters for the module.
     */
    public static function AddModule(string $file, string $id, array $metadata): void
    {
        self::$metadata = $metadata;
    }
}

class_alias(RegistrationCapture::class, 'SetupWebPage');
require "$root/$ionos/module.$ionos.php";
$addon = RegistrationCapture::$metadata;
if ($addon['mandatory'] !== false || $addon['dependencies'] !== ['br-cloud-datamodel/0.3.0']
    || $addon['data.sample'] !== ['data/data.sample.ionos.xml']) {
    throw new RuntimeException('Unexpected IONOS module registration.');
}
$model = simplexml_load_file("$root/$ionos/datamodel.$ionos.xml", SimpleXMLElement::class, LIBXML_NONET);
foreach (['CloudNatGateway', 'CloudNatGatewayLan', 'CloudNatGatewayLanAddress', 'CloudNatGatewayPublicAddress',
    'CloudVpnGateway', 'CloudVpnGatewayLan', 'CloudVpnGatewayLanAddress', 'CloudVpnGatewayInterfaceAddress',
    'CloudNetworkLoadBalancer', 'CloudNetworkLoadBalancerListenerAddress', 'CloudNetworkLoadBalancerBackendAddress'] as $class) {
    $expected = ['ionos_source_key' => 'AttributeString'];
    if (str_ends_with($class, 'Address') || $class === 'CloudVpnGateway') {
        $expected['ionos_synced_ip_id'] = 'AttributeInteger';
    }
    $node = $model->xpath("classes/class[@id='$class']")[0] ?? null;
    foreach ($expected as $code => $type) {
        $field = $node?->xpath("fields/field[@id='$code']")[0] ?? null;
        if ($field === null || (string) $field->attributes('xsi', true)->type !== $type
            || (string) $field->is_null_allowed !== 'true'
            || $node->xpath("presentation//item[@id='$code']")) {
            throw new RuntimeException("Unexpected collector ownership field: $class.$code");
        }
    }
}

$sample = simplexml_load_file("$root/$ionos/" . $addon['data.sample'][0], SimpleXMLElement::class, LIBXML_NONET);
if ($sample === false || $sample->getName() !== 'Set' || count($sample->Organization) !== 1
    || (string) $sample->Organization->name !== 'IONOS' || (string) $sample->Organization->code !== 'IONOS') {
    throw new RuntimeException('Expected the IONOS provider in the optional sample file.');
}
$providerId = (string) $sample->Organization['id'];
$seenIds = [$providerId => true];
$codes = [];
foreach ($sample->Location as $location) {
    $id = (string) $location['id'];
    $code = (string) $location->name;
    if (!ctype_digit($id) || isset($seenIds[$id]) || isset($codes[$code]) || (string) $location->org_id !== $providerId
        || (string) $location->city === '' || (string) $location->country === '') {
        throw new RuntimeException("Invalid sample location $code.");
    }
    $seenIds[$id] = $codes[$code] = true;
}
$expectedCodes = ['de/fra', 'de/fra/1', 'de/fra/2', 'de/txl', 'es/vit', 'fr/par',
    'gb/bhx', 'gb/lhr', 'us/ewr', 'us/las', 'us/mci'];
ksort($codes);
if (array_keys($codes) !== $expectedCodes || count($sample->children()) !== count($codes) + 1) {
    throw new RuntimeException('Sample objects differ from the public location snapshot.');
}
echo "Module registration, XML schemas and optional provider samples passed.\n";
