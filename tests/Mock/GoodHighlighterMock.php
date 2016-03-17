<?php

namespace Ramsey\Twig\CodeBlock\Test\Mock;

use Ramsey\Twig\CodeBlock\Highlighter\HighlighterInterface;

class GoodHighlighterMock implements HighlighterInterface
{
    public $path;
    public $format;

    public function __construct($path = null, $format = null)
    {
        $this->path = $path;
        $this->format = $format;
    }

    public function highlight($code, array $options = [])
    {
        return '';
    }
}
