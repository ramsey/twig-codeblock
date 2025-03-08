<?php

declare(strict_types=1);

namespace Ramsey\Twig\CodeBlock\Test;

use Ramsey\Twig\CodeBlock\CodeBlockExtension;
use Ramsey\Twig\CodeBlock\Highlighter\HighlighterReference;
use Ramsey\Twig\CodeBlock\Highlighter\PygmentsHighlighter;
use Twig\Test\IntegrationTestCase;

use function getenv;

final class IntegrationTest extends IntegrationTestCase
{
    /**
     * @inheritDoc
     */
    public function getExtensions(): array
    {
        return [
            new CodeBlockExtension(
                new HighlighterReference(
                    PygmentsHighlighter::class,
                    ['pygmentizePath' => getenv('PYGMENTIZE_PATH')],
                ),
            ),
        ];
    }

    protected static function getFixturesDirectory(): string
    {
        return __DIR__ . '/fixtures/integration/';
    }
}
