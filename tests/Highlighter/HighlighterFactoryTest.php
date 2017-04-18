<?php

namespace Ramsey\Twig\CodeBlock\Test\Highlighter;

use Ramsey\Twig\CodeBlock\Highlighter\HighlighterFactory;
use Ramsey\Twig\CodeBlock\Highlighter\HighlighterInterface;
use Ramsey\Twig\CodeBlock\Highlighter\PygmentsHighlighter;
use Ramsey\Twig\CodeBlock\Test\TestCase;
use RuntimeException;

class HighlighterFactoryTest extends TestCase
{
    public function testGetHighlighterReturnsPassedHighlighter()
    {
        $highlighter = \Mockery::mock(HighlighterInterface::class);

        $this->assertSame($highlighter, HighlighterFactory::getHighlighter($highlighter));
    }

    public function testGetHighlighterReturnsPygmentsHighlighterForNull()
    {
        $this->assertInstanceOf(
            PygmentsHighlighter::class,
            HighlighterFactory::getHighlighter()
        );
    }

    public function testGetHighlighterReturnsSpecifiedHighlighterInstance()
    {
        $this->assertInstanceOf(
            HighlighterInterface::class,
            HighlighterFactory::getHighlighter(Mock\FooHighlighter::class)
        );
    }

    public function testGetHighlighterThrowsRuntimeExceptionWhenClassNotExists()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Class Bar does not exist');

        $highlighter = HighlighterFactory::getHighlighter('Bar');
    }

    public function testGetHighlighterThrowsRuntimeExceptionWhenClassNotImplementsHighlighterInterface()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            "'Ramsey\Twig\CodeBlock\Test\Highlighter\Mock\Baz' must be an instance of "
            . "'Ramsey\Twig\CodeBlock\Highlighter\HighlighterInterface'."
        );

        $highlighter = HighlighterFactory::getHighlighter(Mock\Baz::class);
    }
}
