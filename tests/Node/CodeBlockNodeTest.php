<?php

namespace Ramsey\Tests\Twig\CodeBlock\Node;

use Ramsey\Tests\Twig\CodeBlock\TestCase;
use Ramsey\Twig\CodeBlock\Node\CodeBlockNode;

class CodeBlockNodeTest extends TestCase
{
    protected $bodyNode;
    protected $compiler;

    public function setUp()
    {
        $this->compiler = new MockTwigCompiler();
        $this->bodyNode = new \Twig_Node([], ['data' => "<?php\nphpinfo();"], 1);
    }

    public function testCompile()
    {
        $expectedSource = <<<'EOD'
$highlighter = \Ramsey\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter("pygments", array(0 => "/usr/local/bin/pygmentize"));
$highlightedCode = $highlighter->highlight("<?php
phpinfo();", array("lang" => "php", "format" => "bbcode", "linenos" => true, "start" => 1, "end" => 30, "mark" => "1,5-8,15-20,24", "phpopentag" => false));
echo $highlightedCode;

EOD;

        $node = new CodeBlockNode(
            'pygments',
            [
                '/usr/local/bin/pygmentize',
            ],
            [
                'lang' => 'php',
                'format' => 'bbcode',
                'linenos' => true,
                'start' => 1,
                'end' => 30,
                'mark' => '1,5-8,15-20,24',
                'phpopentag' => false,
            ],
            $this->bodyNode,
            1
        );

        $node->compile($this->compiler);
        $source = $this->compiler->getSource();

        $this->assertEquals($expectedSource, $source);
    }

    public function testCompileWithTitle()
    {
        $expectedSource = <<<'EOD'
$highlighter = \Ramsey\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter("pygments", array(0 => "/usr/local/bin/pygmentize"));
$highlightedCode = $highlighter->highlight("<?php
phpinfo();", array("format" => "html", "lang" => "php", "title" => "Test Title"));
$figcaption = "<figcaption class=\"code-highlight-caption\"><span class=\"code-highlight-caption-title\">Test Title</span></figcaption>";
echo sprintf("<figure class=\"code-highlight-figure\">%s%s</figure>\n", $figcaption, $highlightedCode);

EOD;

        $node = new CodeBlockNode(
            'pygments',
            [
                '/usr/local/bin/pygmentize',
            ],
            [
                'format' => 'html',
                'lang' => 'php',
                'title' => 'Test Title',
            ],
            $this->bodyNode,
            1
        );

        $node->compile($this->compiler);
        $source = $this->compiler->getSource();

        $this->assertEquals($expectedSource, $source);
    }

    public function testCompileWithLinkUrl()
    {
        $expectedSource = <<<'EOD'
$highlighter = \Ramsey\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter("pygments", array(0 => "/usr/local/bin/pygmentize"));
$highlightedCode = $highlighter->highlight("<?php
phpinfo();", array("format" => "html", "lang" => "php", "title" => "Test Title", "linkUrl" => "http://example.org"));
$figcaption = "<figcaption class=\"code-highlight-caption\"><span class=\"code-highlight-caption-title\">Test Title</span><a class=\"code-highlight-caption-link\" href=\"http://example.org\">link</a></figcaption>";
echo sprintf("<figure class=\"code-highlight-figure\">%s%s</figure>\n", $figcaption, $highlightedCode);

EOD;

        $node = new CodeBlockNode(
            'pygments',
            [
                '/usr/local/bin/pygmentize',
            ],
            [
                'format' => 'html',
                'lang' => 'php',
                'title' => 'Test Title',
                'linkUrl' => 'http://example.org',
            ],
            $this->bodyNode,
            1
        );

        $node->compile($this->compiler);
        $source = $this->compiler->getSource();

        $this->assertEquals($expectedSource, $source);
    }

    public function testCompileWithLinkText()
    {
        $expectedSource = <<<'EOD'
$highlighter = \Ramsey\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter("pygments", array(0 => "/usr/local/bin/pygmentize"));
$highlightedCode = $highlighter->highlight("<?php
phpinfo();", array("format" => "html", "lang" => "php", "title" => "Test Title", "linkUrl" => "http://example.org", "linkText" => "My Listing"));
$figcaption = "<figcaption class=\"code-highlight-caption\"><span class=\"code-highlight-caption-title\">Test Title</span><a class=\"code-highlight-caption-link\" href=\"http://example.org\">My Listing</a></figcaption>";
echo sprintf("<figure class=\"code-highlight-figure\">%s%s</figure>\n", $figcaption, $highlightedCode);

EOD;

        $node = new CodeBlockNode(
            'pygments',
            [
                '/usr/local/bin/pygmentize',
            ],
            [
                'format' => 'html',
                'lang' => 'php',
                'title' => 'Test Title',
                'linkUrl' => 'http://example.org',
                'linkText' => 'My Listing',
            ],
            $this->bodyNode,
            1
        );

        $node->compile($this->compiler);
        $source = $this->compiler->getSource();

        $this->assertEquals($expectedSource, $source);
    }
}

class MockTwigCompiler extends \Twig_Compiler
{
    public function __construct()
    {
        // Override parent method for mock
    }

    public function addDebugInfo(\Twig_NodeInterface $node)
    {
        // Override parent method for mock
    }
}
