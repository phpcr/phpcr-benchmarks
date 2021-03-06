<?php

$dom = null;
$cwd = getcwd();

foreach (array('phpunit.xml', 'phpunit.xml.dist') as $phpunitConfig) {
    if (file_exists($phpunitConfig)) {
        $dom = new \DOMDocument(1.0);
        $dom->load($phpunitConfig);
        break;
    }
}
if (null === $dom) {
    throw new \RuntimeException(
        'Could not find phpunit config (required for running benchmark suite)'
    );
}

$xpath = new \DOMXpath($dom);
foreach ($xpath->query('//php/var') as $globalEl) {
    $GLOBALS[$globalEl->getAttribute('name')] = $globalEl->getAttribute('value');
}

require_once($cwd . '/tests/bootstrap.php');
