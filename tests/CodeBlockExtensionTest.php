<?php

namespace Ramsey\Twig\CodeBlock\Test;

use Ramsey\Twig\CodeBlock\CodeBlockExtension;
use Ramsey\Twig\CodeBlock\Test\TestCase;

class CodeBlockExtensionTest extends TestCase
{
    public function testGetName()
    {
        $ext = new CodeBlockExtension();

        $this->assertEquals('codeblock', $ext->getName());
    }
}
