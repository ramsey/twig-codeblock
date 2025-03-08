<?php

declare(strict_types=1);

namespace Ramsey\Twig\CodeBlock\Test\Highlighter;

use PHPUnit\Framework\TestCase;
use Ramsey\Twig\CodeBlock\Exception\RuntimeException;
use Ramsey\Twig\CodeBlock\Highlighter\HighlighterFactory;
use Ramsey\Twig\CodeBlock\Highlighter\HighlighterReference;
use Ramsey\Twig\CodeBlock\Highlighter\PygmentsHighlighter;
use Ramsey\Twig\CodeBlock\Test\Highlighter\Mock\Baz;
use Ramsey\Twig\CodeBlock\Test\Highlighter\Mock\FooHighlighter;

class HighlighterFactoryTest extends TestCase
{
    public function testGetHighlighterReturnsPygmentsHighlighter(): void
    {
        $this->assertInstanceOf(
            PygmentsHighlighter::class,
            HighlighterFactory::getHighlighter(PygmentsHighlighter::class, ['pygmentizePath' => 'pygmentize']),
        );
    }

    public function testGetHighlighterReturnsMockHighlighter(): void
    {
        $this->assertInstanceOf(
            FooHighlighter::class,
            HighlighterFactory::getHighlighter(FooHighlighter::class),
        );
    }

    public function testGetHighlighterThrowsRuntimeExceptionWhenClassNotExists(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            "'Bar' must be an instance of 'Ramsey\Twig\CodeBlock\Highlighter\HighlighterInterface'",
        );

        /** @phpstan-ignore-next-line */
        HighlighterFactory::getHighlighter('Bar');
    }

    public function testGetHighlighterThrowsRuntimeExceptionWhenClassDoesNotImplementHighlighterInterface(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            "'Ramsey\Twig\CodeBlock\Test\Highlighter\Mock\Baz' must be an instance of "
            . "'Ramsey\Twig\CodeBlock\Highlighter\HighlighterInterface'",
        );

        /** @phpstan-ignore-next-line */
        HighlighterFactory::getHighlighter(Baz::class);
    }

    public function testGetHighlighterFromReferenceForPygmentsHighlighter(): void
    {
        $reference = new HighlighterReference(PygmentsHighlighter::class, ['pygmentizePath' => 'pygmentize']);

        $this->assertInstanceOf(
            PygmentsHighlighter::class,
            HighlighterFactory::getHighlighterFromReference($reference),
        );
    }

    public function testGetHighlighterFromReferenceForMockHighlighter(): void
    {
        $reference = new HighlighterReference(FooHighlighter::class);

        $this->assertInstanceOf(
            FooHighlighter::class,
            HighlighterFactory::getHighlighterFromReference($reference),
        );
    }
}
