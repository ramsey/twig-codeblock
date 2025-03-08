<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$pygmentizePath = getenv('PYGMENTIZE_PATH');

if (!$pygmentizePath) {
    fwrite(STDERR, "You must provide a PYGMENTIZE_PATH environment variable in your PHPUnit\nconfiguration.\n");
    exit(1);
}

$output = [];
$returnValue = 1;

@exec(escapeshellcmd($pygmentizePath . ' -V'), $output, $returnValue);

if ($returnValue !== 0) {
    fwrite(STDERR, sprintf("An error occurred while attempting to execute '%s -V'.\n", $pygmentizePath));
    exit(1);
}

/** @var string $pygmentizeVersion */
$pygmentizeVersion = array_shift($output);

if (preg_match('/(2\.19\.1)/', $pygmentizeVersion, $matches) === 0) {
    fwrite(
        STDERR,
        "Pygments 2.19.1 is required to run the tests. However, this library\n"
        . "will work with any version of Python and Pygments supported by the\n"
        . "ramsey/pygments library.\n\n"
        . "Found:\n"
        . $pygmentizeVersion . "\n",
    );
    exit(1);
}

assert(isset($matches[1]));

putenv("PYGMENTIZE_VERSION={$matches[1]}");
