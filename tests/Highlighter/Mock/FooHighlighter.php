<?php

declare(strict_types=1);

namespace Ramsey\Twig\CodeBlock\Test\Highlighter\Mock;

use Ramsey\Twig\CodeBlock\Highlighter\HighlighterInterface;

class FooHighlighter implements HighlighterInterface
{
    /**
     * @inheritDoc
     */
    public function highlight(string $code, array $options = []): string
    {
        return 'foo';
    }
}
