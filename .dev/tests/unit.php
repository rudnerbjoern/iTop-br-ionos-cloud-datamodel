<?php
/** Verify that preparing an IONOS release preserves the external Cloud dependency. */

declare(strict_types=1);

namespace BR\IonosCloud\Datamodel\Tests;

use RuntimeException;
use SimpleXMLElement;

require_once __DIR__ . '/../../scripts/prepare-release.php';

$root = dirname(__DIR__, 2);
$version = projectVersion($root);
$manifest = file_get_contents($root . '/extension.xml');
$name = IONOS_MODULE;
$source = file_get_contents("$root/$name/module.$name.php");
[$major, $minor, $patch] = explode('.', $version);
$next = "$major.$minor." . ((int) $patch + 1);
[$updated, $registration] = prepareReleaseContents($manifest, $source, $next, '2028-02-29');
$xml = simplexml_load_string($updated, SimpleXMLElement::class, LIBXML_NONET);
if ($xml === false || releaseMetadataVersion($xml, $registration) !== $next
    || (string) $xml->release_date !== '2028-02-29'
    || str_replace("'$name/$next'", "'$name/$version'", $registration, $count) !== $source || $count !== 1) {
    throw new RuntimeException('A release must update only the IONOS metadata and preserve external dependencies.');
}
echo "Independent IONOS release preparation passed.\n";
