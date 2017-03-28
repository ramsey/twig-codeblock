<?php

namespace Ramsey\Twig\CodeBlock\Test;

use Ramsey\Twig\CodeBlock\CodeBlockExtension;

class IntegrationTest extends \Twig_Test_IntegrationTestCase
{
    public function getExtensions()
    {
        return [
            // Defaults to using pygmentize
            new CodeBlockExtension(),
        ];
    }

    public function getFixturesDir()
    {
        return __DIR__ . '/fixtures/integration/';
    }
}
