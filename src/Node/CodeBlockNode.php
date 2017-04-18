<?php
/**
 * This file is part of the Ramsey\Twig\CodeBlock extension for Twig
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey (http://benramsey.com)
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace Ramsey\Twig\CodeBlock\Node;

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
     * @var array
     */
    protected $highlighterArgs;

    /**
     * Creates a codeblock node
     *
     * @param string $highlighterName Name or fully-qualified classname of the
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
        \Twig_Node $body,
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

        $compiler
            ->write('$highlighter = \Ramsey\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter(')
            ->string($this->getHighlighterName())
            ->raw(', ')
            ->repr($this->getHighlighterArgs())
            ->raw(");\n");

        $compiler
            ->write('$highlightedCode = $highlighter->highlight(')
            ->string($this->getNode('body')->getAttribute('data'))
            ->raw(', ')
            ->repr($this->attributes)
            ->raw(");\n");

        if ($this->hasAttribute('format') && strtolower($this->getAttribute('format')) === 'html') {
            $classnames = 'code-highlight-figure';
            if ($this->hasAttribute('class')) {
                $classnames .= ' ' . $this->getAttribute('class');
            }

            $compiler
                ->write('$figcaption = ')
                ->string($this->getFigcaption())
                ->raw(";\n")
                ->write('echo sprintf(')
                ->raw('"<figure class=\"' . $classnames . '\">%s%s</figure>\n",')
                ->raw(' $figcaption, $highlightedCode')
                ->raw(");\n");
        } else {
            $compiler->write('echo $highlightedCode;' . "\n");
        }
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

    /**
     * Returns the figcaption HTML element for the codeblock
     *
     * @return string
     */
    protected function getFigcaption()
    {
        $figcaption = '';

        if ($this->hasAttribute('title')) {
            $figcaption = '<figcaption class="code-highlight-caption">';
            $figcaption .= '<span class="code-highlight-caption-title">';
            $figcaption .= $this->getAttribute('title');
            $figcaption .= '</span>';
            $figcaption .= $this->getFigcaptionLink();
            $figcaption .= '</figcaption>';
        }

        return $figcaption;
    }

    /**
     * Returns the link for the figcaption, if applicable
     *
     * @return string
     */
    protected function getFigcaptionLink()
    {
        $link = '';

        if ($this->hasAttribute('linkUrl')) {
            $link = '<a class="code-highlight-caption-link" href="'
                . $this->getAttribute('linkUrl')
                . '">';
            $link .= $this->getFigcaptionLinkText();
            $link .= '</a>';
        }

        return $link;
    }

    /**
     * Returns the link text for the figcaption link
     *
     * @return string
     */
    protected function getFigcaptionLinkText()
    {
        if ($this->hasAttribute('linkText')) {
            return $this->getAttribute('linkText');
        }

        return 'link';
    }
}
