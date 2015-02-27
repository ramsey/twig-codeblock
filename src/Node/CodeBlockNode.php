<?php
/**
 * This file is part of the Rhumsaa\Twig\CodeBlock extension for Twig
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey (http://benramsey.com)
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace Rhumsaa\Twig\CodeBlock\Node;

/**
 * Represents a codeblock node in Twig
 */
class CodeBlockNode extends \Twig_Node
{
    /**
     * Name or fully-qualified classname of the highlighter
     *
     * @var string
     */
    protected $highlighterName;

    /**
     * Array of constructor arguments to pass to the $highlighterName class
     * upon instantiation
     *
     * @var string
     */
    protected $highlighterArgs;

    /**
     * Creates a codeblock node
     *
     * @param string $highligherName Name or fully-qualified classname of the
     *     highlighter to use
     * @param array $highlighterArgs Array of constructor arguments to pass to
     *     the $highlighterName class upon instantiation
     * @param array $attributes The attributes set on the codeblock tag
     * @param \Twig_NodeInterface $body The body node contained within the codeblock tag
     * @param int $lineno The line number of this node (for debugging)
     * @param string $tag The name of the tag
     */
    public function __construct(
        $highlighterName,
        array $highlighterArgs,
        array $attributes,
        \Twig_NodeInterface $body,
        $lineno,
        $tag = 'codeblock'
    ) {
        $this->highlighterName = (string) $highlighterName;
        $this->highlighterArgs = $highlighterArgs;
        parent::__construct(['body' => $body], $attributes, $lineno, $tag);
    }

    /**
     * Compiles the node into PHP code for execution by Twig
     *
     * @param \Twig_Compiler $compiler The compiler to which we add the node's PHP code
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        // Instantiate the highlighter using the HighlighterFactory
        $compiler
            ->write('$highlighter = \Rhumsaa\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter(')
            ->string($this->getHighlighterName())
            ->raw(', ')
            ->repr($this->getHighlighterArgs())
            ->raw(");\n");

        // Echo the highlighted string
        $compiler
            ->write('echo $highlighter->highlight(')
            ->string($this->getNode('body')->getAttribute('data'))
            ->raw(', ')
            ->repr($this->attributes)
            ->raw(");\n");
    }

    /**
     * Returns the name of the highlighter used by this code block
     *
     * @return string
     */
    public function getHighlighterName()
    {
        return $this->highlighterName;
    }

    /**
     * Returns the highlighter arguments used by this code block
     *
     * @return array
     */
    public function getHighlighterArgs()
    {
        return $this->highlighterArgs;
    }
}
