<?php
/** Prepare a consistent extension release and run the local checks. */

declare(strict_types=1);

require_once __DIR__ . '/release-version.php';

/**
 * Prepare descriptor and registration contents for a newer release.
 *
 * @param string $manifest Current extension descriptor XML.
 * @param string $registration Current IONOS module registration.
 * @param string $version New numeric X.Y.Z version.
 * @param string $date Release date in YYYY-MM-DD format.
 * @return array{string,string} Updated descriptor and module contents.
 * @throws RuntimeException When a metadata or release input check fails.
 */
function prepareReleaseContents(string $manifest, string $registration, string $version, string $date): array
{
    if (!preg_match('/^(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)$/D', $version)) {
        throw new RuntimeException('Version must use X.Y.Z format without leading zeros.');
    }
    $parsedDate = DateTimeImmutable::createFromFormat('!Y-m-d', $date);
    if ($parsedDate === false || $parsedDate->format('Y-m-d') !== $date) {
        throw new RuntimeException('Date must be a valid YYYY-MM-DD date.');
    }
    $previous = libxml_use_internal_errors(true);
    try {
        $xml = simplexml_load_string($manifest, SimpleXMLElement::class, LIBXML_NONET);
        if ($xml === false || count($xml->release_date) !== 1) {
            throw new RuntimeException('Expected a release date in the Cloud extension manifest.');
        }
        $currentVersion = releaseMetadataVersion($xml, $registration);
    } finally {
        libxml_clear_errors();
        libxml_use_internal_errors($previous);
    }
    if (!version_compare($version, $currentVersion, '>')) {
        throw new RuntimeException('The release version must be newer than ' . $currentVersion . '.');
    }
    foreach (['version' => $version, 'release_date' => $date] as $element => $value) {
        $manifest = preg_replace_callback(
            '~<' . $element . '>.*?</' . $element . '>~s',
            static fn (): string => "<$element><![CDATA[$value]]></$element>",
            $manifest,
            -1,
            $count
        );
        $expectedCount = $element === 'version' ? 2 : 1;
        if ($manifest === null || $count !== $expectedCount) {
            throw new RuntimeException('Unexpected number of editable ' . $element . ' element.');
        }
    }
    $name = IONOS_MODULE;
    $registration = str_replace("'$name/$currentVersion'", "'$name/$version'", $registration, $count);
    if ($count !== 1) {
        throw new RuntimeException('Expected one editable IONOS module registration.');
    }
    return [$manifest, $registration];
}

if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    try {
        if ($argc < 2 || $argc > 3) {
            throw new RuntimeException('Usage: php scripts/prepare-release.php X.Y.Z [YYYY-MM-DD]');
        }
        $date = $argv[2] ?? (new DateTimeImmutable('now', new DateTimeZone('Europe/Berlin')))->format('Y-m-d');
        $root = dirname(__DIR__);
        $name = IONOS_MODULE;
        $paths = [$root . '/extension.xml', "$root/$name/module.$name.php"];
        $originals = [];
        foreach ($paths as $path) {
            $content = file_get_contents($path);
            if ($content === false || !is_writable($path)) {
                throw new RuntimeException('Cannot read and write ' . $path);
            }
            $originals[] = $content;
        }
        $updated = prepareReleaseContents($originals[0], $originals[1], $argv[1], $date);
        try {
            foreach ($paths as $index => $path) {
                if (file_put_contents($path, $updated[$index], LOCK_EX) !== strlen($updated[$index])) {
                    throw new RuntimeException('Cannot write ' . $path);
                }
            }
        } catch (Throwable $error) {
            foreach ($paths as $index => $path) {
                if (file_put_contents($path, $originals[$index], LOCK_EX) !== strlen($originals[$index])) {
                    fwrite(STDERR, "Could not restore $path; inspect it manually.\n");
                }
            }
            throw $error;
        }
        echo "Prepared version {$argv[1]} with release date $date. Running tests...\n";
        passthru(escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($root . '/.dev/tests/run.php'), $status);
        if ($status !== 0) {
            fwrite(STDERR, "Tests failed. Version edits are retained for inspection; do not publish yet.\n");
        }
        exit($status);
    } catch (Throwable $error) {
        fwrite(STDERR, $error->getMessage() . "\n");
        exit(1);
    }
}
