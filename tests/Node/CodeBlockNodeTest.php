<?php

namespace Ramsey\Twig\CodeBlock\Test\Node;

use Ramsey\Twig\CodeBlock\Test\TestCase;
use Ramsey\Twig\CodeBlock\Test\Mock\TwigCompilerMock;
use Ramsey\Twig\CodeBlock\Node\CodeBlockNode;

class CodeBlockNodeTest extends TestCase
{
    protected $bodyNode;
    protected $compiler;

    public function setUp()
    {
        $this->compiler = new TwigCompilerMock();
        $this->bodyNode = new \Twig_Node([], ['data' => "<?php\nphpinfo();"], 1);
    }

    public function testCompile()
    {
        $expectedSource = file_get_contents(
            __DIR__ . '/expectations/CodeBlockNodeTest_testCompile.txt'
        );

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
        $expectedSource = file_get_contents(
            __DIR__ . '/expectations/CodeBlockNodeTest_testCompileWithTitle.txt'
        );

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
        $expectedSource = file_get_contents(
            __DIR__ . '/expectations/CodeBlockNodeTest_testCompileWithLinkUrl.txt'
        );

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
        $expectedSource = file_get_contents(
            __DIR__ . '/expectations/CodeBlockNodeTest_testCompileWithLinkText.txt'
        );

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
