<?php
/** Read and cross-check the IONOS version used by release automation. */

declare(strict_types=1);

const IONOS_MODULE = 'br-ionos-cloud-datamodel';

/** Verify that the descriptor and its single IONOS module registration share a numeric X.Y.Z version. */
function releaseMetadataVersion(SimpleXMLElement $manifest, string $registration): string
{
    $version = (string) $manifest->version;
    $entry = $manifest->modules->module;
    if ($manifest->getName() !== 'extension' || (string) $manifest->extension_code !== IONOS_MODULE
        || count($manifest->version) !== 1 || !preg_match('/^(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)$/D', $version)
        || count($manifest->modules) !== 1 || (string) $manifest->modules['type'] !== 'array'
        || count($entry) !== 1 || (string) $entry->id !== IONOS_MODULE || (string) $entry->version !== $version) {
        throw new RuntimeException('Expected one matching IONOS module entry in extension.xml.');
    }
    $pattern = "~SetupWebPage::AddModule\\(\\s*__FILE__,\\s*'br-ionos-cloud-datamodel/([^']+)'~";
    if (preg_match_all($pattern, $registration, $matches) !== 1 || $matches[1][0] !== $version) {
        throw new RuntimeException('The IONOS module registration must match the descriptor version.');
    }
    return $version;
}

/** Read the release version from the descriptor and the IONOS runtime module. */
function projectVersion(string $root): string
{
    $manifest = simplexml_load_file($root . '/extension.xml', SimpleXMLElement::class, LIBXML_NONET);
    $name = IONOS_MODULE;
    $registration = file_get_contents("$root/$name/module.$name.php");
    if ($manifest === false || $registration === false) {
        throw new RuntimeException('Cannot read the IONOS extension metadata.');
    }
    return releaseMetadataVersion($manifest, $registration);
}

if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    $version = projectVersion(dirname(__DIR__));
    if (($argv[1] ?? '') !== '' && $argv[1] !== 'release/' . $version) {
        throw new RuntimeException('Release branch and project version must match.');
    }
    echo $version, PHP_EOL;
}
