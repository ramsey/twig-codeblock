<?php

namespace Rhumsaa\Tests\Twig\CodeBlock\Node;

use Rhumsaa\Tests\Twig\CodeBlock\TestCase;
use Rhumsaa\Twig\CodeBlock\Node\CodeBlockNode;

class CodeBlockNodeTest extends TestCase
{
    protected $bodyNode;
    protected $compiler;

    public function setUp()
    {
        $this->compiler = new MockTwigCompiler();
        $this->bodyNode = new \Twig_Node([], ['data' => "<?php\nphpinfo();"], 1);
    }

    /**
     * @covers Rhumsaa\Twig\CodeBlock\Node\CodeBlockNode::__construct
     * @covers Rhumsaa\Twig\CodeBlock\Node\CodeBlockNode::compile
     */
    public function testCompile()
    {
        $expectedSource = <<<'EOD'
$highlighter = \Rhumsaa\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter("pygments", array(0 => "/usr/local/bin/pygmentize"));
echo $highlighter->highlight("<?php
phpinfo();", array("lang" => "php", "format" => "bbcode", "linenos" => true, "start" => 1, "end" => 30, "mark" => "1,5-8,15-20,24", "phpopentag" => false));

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
