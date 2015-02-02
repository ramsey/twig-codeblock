<?php

namespace Rhumsaa\Tests\Twig\CodeBlock\Highlighter;

use Rhumsaa\Tests\Twig\CodeBlock\TestCase;
use Rhumsaa\Twig\CodeBlock\Highlighter\HighlighterFactory;
use Rhumsaa\Twig\CodeBlock\Highlighter\HighlighterInterface;

class HighlighterFactoryTest extends TestCase
{
    /**
     * @covers Rhumsaa\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter
     * @expectedException RuntimeException
     * @expectedExceptionMessage Class Foobar does not exist
     */
    public function testHighlighterFactoryClassNotFoundException()
    {
        $highlighter = HighlighterFactory::getHighlighter('Foobar');
    }

    /**
     * @covers Rhumsaa\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter
     * @expectedException RuntimeException
     * @expectedExceptionMessage 'Rhumsaa\Tests\Twig\CodeBlock\Highlighter\BadHighlighter'
     *     must be an instance of 'Rhumsaa\Twig\CodeBlock\Highlighter\HighlighterInterface'
     */
    public function testHighlighterFactoryClassNotInstanceOfHighlighterInterface()
    {
        $highlighter = HighlighterFactory::getHighlighter(
            'Rhumsaa\Tests\Twig\CodeBlock\Highlighter\BadHighlighter'
        );
    }

    /**
     * @covers Rhumsaa\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter
     */
    public function testHighlighterFactoryHighlighterInstance()
    {
        $highlighterInstance = new GoodHighlighter();
        $highlighter = HighlighterFactory::getHighlighter($highlighterInstance);

        $this->assertSame($highlighterInstance, $highlighter);
    }

    /**
     * @covers Rhumsaa\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter
     */
    public function testHighlighterFactoryHighlighterClassnameArgs()
    {
        $highlighter = HighlighterFactory::getHighlighter(
            'Rhumsaa\Tests\Twig\CodeBlock\Highlighter\GoodHighlighter',
            [
                '/path/to/highlighter',
                'html'
            ]
        );

        $this->assertInstanceOf('Rhumsaa\Tests\Twig\CodeBlock\Highlighter\GoodHighlighter', $highlighter);
        $this->assertEquals('/path/to/highlighter', $highlighter->path);
        $this->assertEquals('html', $highlighter->format);
    }

    /**
     * @covers Rhumsaa\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter
     */
    public function testHighlighterFactoryWithPygments()
    {
        $highlighter = HighlighterFactory::getHighlighter('pygments');

        $this->assertInstanceOf('Rhumsaa\Twig\CodeBlock\Highlighter\PygmentsHighlighter', $highlighter);
    }

    /**
     * @covers Rhumsaa\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter
     */
    public function testHighlighterFactoryWithNull()
    {
        $highlighter = HighlighterFactory::getHighlighter();

        $this->assertInstanceOf('Rhumsaa\Twig\CodeBlock\Highlighter\PygmentsHighlighter', $highlighter);
    }
}

class BadHighlighter
{
}

class GoodHighlighter implements HighlighterInterface
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
