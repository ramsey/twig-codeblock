<?php

namespace Ramsey\Twig\CodeBlock\Test\Highlighter;

use Ramsey\Twig\CodeBlock\Test\TestCase;
use Ramsey\Twig\CodeBlock\Test\Mock\GoodHighlighterMock;
use Ramsey\Twig\CodeBlock\Highlighter\HighlighterFactory;

class HighlighterFactoryTest extends TestCase
{
    /**
     * @covers Ramsey\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter
     * @expectedException RuntimeException
     * @expectedExceptionMessage Class Foobar does not exist
     */
    public function testHighlighterFactoryClassNotFoundException()
    {
        $highlighter = HighlighterFactory::getHighlighter('Foobar');
    }

    /**
     * @covers Ramsey\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter
     * @expectedException RuntimeException
     * @expectedExceptionMessage 'Ramsey\Twig\CodeBlock\Test\Mock\BadHighlighterMock'
     *     must be an instance of 'Ramsey\Twig\CodeBlock\Highlighter\HighlighterInterface'
     */
    public function testHighlighterFactoryClassNotInstanceOfHighlighterInterface()
    {
        $highlighter = HighlighterFactory::getHighlighter(
            'Ramsey\Twig\CodeBlock\Test\Mock\BadHighlighterMock'
        );
    }

    /**
     * @covers Ramsey\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter
     */
    public function testHighlighterFactoryHighlighterInstance()
    {
        $highlighterInstance = new GoodHighlighterMock();
        $highlighter = HighlighterFactory::getHighlighter($highlighterInstance);

        $this->assertSame($highlighterInstance, $highlighter);
    }

    /**
     * @covers Ramsey\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter
     */
    public function testHighlighterFactoryHighlighterClassnameArgs()
    {
        $highlighter = HighlighterFactory::getHighlighter(
            'Ramsey\Twig\CodeBlock\Test\Mock\GoodHighlighterMock',
            [
                '/path/to/highlighter',
                'html'
            ]
        );

        $this->assertInstanceOf('Ramsey\Twig\CodeBlock\Test\Mock\GoodHighlighterMock', $highlighter);
        $this->assertEquals('/path/to/highlighter', $highlighter->path);
        $this->assertEquals('html', $highlighter->format);
    }

    /**
     * @covers Ramsey\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter
     */
    public function testHighlighterFactoryWithPygments()
    {
        $highlighter = HighlighterFactory::getHighlighter('pygments');

        $this->assertInstanceOf('Ramsey\Twig\CodeBlock\Highlighter\PygmentsHighlighter', $highlighter);
    }

    /**
     * @covers Ramsey\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter
     */
    public function testHighlighterFactoryWithNull()
    {
        $highlighter = HighlighterFactory::getHighlighter();

        $this->assertInstanceOf('Ramsey\Twig\CodeBlock\Highlighter\PygmentsHighlighter', $highlighter);
    }
}
