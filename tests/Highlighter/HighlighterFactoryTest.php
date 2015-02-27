<?php

namespace Ramsey\Tests\Twig\CodeBlock\Highlighter;

use Ramsey\Tests\Twig\CodeBlock\TestCase;
use Ramsey\Twig\CodeBlock\Highlighter\HighlighterFactory;
use Ramsey\Twig\CodeBlock\Highlighter\HighlighterInterface;

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
     * @expectedExceptionMessage 'Ramsey\Tests\Twig\CodeBlock\Highlighter\BadHighlighter'
     *     must be an instance of 'Ramsey\Twig\CodeBlock\Highlighter\HighlighterInterface'
     */
    public function testHighlighterFactoryClassNotInstanceOfHighlighterInterface()
    {
        $highlighter = HighlighterFactory::getHighlighter(
            'Ramsey\Tests\Twig\CodeBlock\Highlighter\BadHighlighter'
        );
    }

    /**
     * @covers Ramsey\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter
     */
    public function testHighlighterFactoryHighlighterInstance()
    {
        $highlighterInstance = new GoodHighlighter();
        $highlighter = HighlighterFactory::getHighlighter($highlighterInstance);

        $this->assertSame($highlighterInstance, $highlighter);
    }

    /**
     * @covers Ramsey\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter
     */
    public function testHighlighterFactoryHighlighterClassnameArgs()
    {
        $highlighter = HighlighterFactory::getHighlighter(
            'Ramsey\Tests\Twig\CodeBlock\Highlighter\GoodHighlighter',
            [
                '/path/to/highlighter',
                'html'
            ]
        );

        $this->assertInstanceOf('Ramsey\Tests\Twig\CodeBlock\Highlighter\GoodHighlighter', $highlighter);
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
