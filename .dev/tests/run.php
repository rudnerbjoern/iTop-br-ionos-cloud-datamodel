<?php
/** Dispatch the local schema and release checks for this bundle. */

declare(strict_types=1);

$suites = ['Native' => 'native.php', 'Unit' => 'unit.php'];
$requested = $argv[1] ?? null;
if ($requested === 'Integration') {
    require __DIR__ . '/integration.php';
    return;
}
if ($requested !== null && !isset($suites[$requested])) {
    throw new RuntimeException("Unsupported test suite: $requested");
}
foreach ($requested === null ? $suites : [$suites[$requested]] as $script) {
    require __DIR__ . '/' . $script;
}
