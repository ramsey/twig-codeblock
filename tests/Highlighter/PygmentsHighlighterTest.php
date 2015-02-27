<?php

namespace Ramsey\Tests\Twig\CodeBlock\Highlighter;

use Ramsey\Tests\Twig\CodeBlock\TestCase;

class PygmentsHighlighterTest extends TestCase
{
    protected $highlighter;

    public function setUp()
    {
        $highlighter = $this->getMockBuilder('Ramsey\Twig\CodeBlock\Highlighter\PygmentsHighlighter')
            ->setMethods(['getPygments'])
            ->getMock();

        $highlighter->method('getPygments')
            ->will($this->returnCallback(function ($pygmentizePath) {
                return new PygmentsMock($pygmentizePath);
            }));

        $this->highlighter = $highlighter;
    }

    /**
     * @covers Ramsey\Twig\CodeBlock\Highlighter\PygmentsHighlighter
     */
    public function testHighlightWithoutOptions()
    {
        $code = "<?php\nphpinfo();\n";
        $returnMock = $this->highlighter->highlight($code);

        $this->assertEquals('pygmentize', $returnMock->pygmentizePath);
        $this->assertEquals($code, $returnMock->code);
        $this->assertEquals(null, $returnMock->lexer);
        $this->assertEquals('html', $returnMock->format);
        $this->assertEquals(['encoding' => 'utf-8'], $returnMock->options);
    }

    /**
     * @covers Ramsey\Twig\CodeBlock\Highlighter\PygmentsHighlighter
     */
    public function testHighlightWithOptions()
    {
        $code = "<?php\nphpinfo();\n";

        $tagOptions = [
            'lang' => 'php',
            'format' => 'bbcode',
            'linenos' => true,
            'start' => 1,
            'end' => 30,
            'mark' => '1,5-8,15-20,24',
            'phpopentag' => false,
        ];

        $expectedParsedOptions = [
            'encoding' => 'utf-8',
            'linenos' => 'table',
            'linenostart' => 1,
            'hl_lines' => '1 5 6 7 8 15 16 17 18 19 20 24',
            'startinline' => 'True',
        ];

        $returnMock = $this->highlighter->highlight($code, $tagOptions);

        $this->assertEquals('pygmentize', $returnMock->pygmentizePath);
        $this->assertEquals($code, $returnMock->code);
        $this->assertEquals($tagOptions['lang'], $returnMock->lexer);
        $this->assertEquals($tagOptions['format'], $returnMock->format);
        $this->assertEquals($expectedParsedOptions, $returnMock->options);
    }

    /**
     * @covers Ramsey\Twig\CodeBlock\Highlighter\PygmentsHighlighter
     */
    public function testHighlightWithRangeAndStart()
    {
        $code = "<?php\nphpinfo();\n";

        $tagOptions = [
            'range' => '5-30',
            'start' => 1,
        ];

        $expectedParsedOptions = [
            'encoding' => 'utf-8',
            'linenostart' => 5,
        ];

        $returnMock = $this->highlighter->highlight($code, $tagOptions);

        $this->assertEquals('pygmentize', $returnMock->pygmentizePath);
        $this->assertEquals($code, $returnMock->code);
        $this->assertEquals(null, $returnMock->lexer);
        $this->assertEquals('html', $returnMock->format);
        $this->assertEquals($expectedParsedOptions, $returnMock->options);
    }
}

class PygmentsMock
{
    public $pygmentizePath;
    public $code;
    public $lexer;
    public $format;
    public $options;

    public function __construct($pygmentizePath)
    {
        $this->pygmentizePath = $pygmentizePath;
    }

    public function highlight($code, $lexer, $format, $options)
    {
        $this->code = $code;
        $this->lexer = $lexer;
        $this->format = $format;
        $this->options = $options;

        return $this;
    }
}
