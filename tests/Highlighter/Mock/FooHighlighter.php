<?php

namespace Ramsey\Twig\CodeBlock\Test\Highlighter\Mock;

use Ramsey\Twig\CodeBlock\Highlighter\HighlighterInterface;

class FooHighlighter implements HighlighterInterface
{
    public function highlight($code, array $options = [])
    {
        return 'foo';
    }
}
