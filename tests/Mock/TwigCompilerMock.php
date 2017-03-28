<?php

namespace Ramsey\Twig\CodeBlock\Test\Mock;

class TwigCompilerMock extends \Twig_Compiler
{
    public function __construct()
    {
        // Override parent method for mock
    }

    public function addDebugInfo(\Twig_Node $node)
    {
        // Override parent method for mock
    }
}
