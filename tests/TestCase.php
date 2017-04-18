<?php

namespace Ramsey\Twig\CodeBlock\Test;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    protected function setUp()
    {
        exec('which pygmentize', $output, $return);

        if ($return === 1) {
            $this->fail('`pygmentize` not found; unable to run tests');
        }

        $pygmentsVersion = exec('pygmentize -V', $output, $return);

        if (preg_match('/2\.2\.?\d?/', $pygmentsVersion) === 0) {
            $this->fail(
                'Pygments version 2.2 is required to run the tests. However, '
                . 'this library will work with any version of Python and '
                . 'Pygments supported by the ramsey/pygments library. Found: '
                . $pygmentsVersion
            );
        }
    }
}
