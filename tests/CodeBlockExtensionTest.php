<?php

declare(strict_types=1);

namespace Ramsey\Twig\CodeBlock\Test;

use PHPUnit\Framework\TestCase;
use Ramsey\Twig\CodeBlock\CodeBlockExtension;
use Ramsey\Twig\CodeBlock\Highlighter\HighlighterReference;
use Ramsey\Twig\CodeBlock\Highlighter\PygmentsHighlighter;
use Ramsey\Twig\CodeBlock\TokenParser\CodeBlockParser;

use function getenv;

final class CodeBlockExtensionTest extends TestCase
{
    private CodeBlockExtension $extension;

    protected function setUp(): void
    {
        $this->extension = new CodeBlockExtension(new HighlighterReference(
            PygmentsHighlighter::class,
            ['pygmentizePath' => getenv('PYGMENTIZE_PATH')],
        ));
    }

    public function testGetFilters(): void
    {
        $this->assertSame([], $this->extension->getFilters());
    }

    public function testGetFunctions(): void
    {
        $this->assertSame([], $this->extension->getFunctions());
    }

    public function testGetNodeVisitors(): void
    {
        $this->assertSame([], $this->extension->getNodeVisitors());
    }

    public function testGetOperators(): void
    {
        $this->assertSame([[], []], $this->extension->getOperators());
    }

    public function testGetTests(): void
    {
        $this->assertSame([], $this->extension->getTests());
    }

    public function testGetTokenParsers(): void
    {
        $parsers = $this->extension->getTokenParsers();

        $this->assertCount(1, $parsers);
        $this->assertInstanceOf(CodeBlockParser::class, $parsers[0]);
    }
}
