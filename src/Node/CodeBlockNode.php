<?php

/**
 * This file is part of the ramsey/twig-codeblock library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace Ramsey\Twig\CodeBlock\Node;

use Ramsey\Twig\CodeBlock\Attributes;
use Ramsey\Twig\CodeBlock\Highlighter\HighlighterReference;
use Twig\Attribute\YieldReady;
use Twig\Compiler;
use Twig\Node\Node;

use function assert;
use function is_string;
use function strtolower;

/**
 * Represents a codeblock node in Twig
 */
#[YieldReady]
final class CodeBlockNode extends Node
{
    /**
     * Creates a codeblock node
     *
     * @param HighlighterReference $highlighterReference Reference details for the highlighter to use
     * @param Attributes $codeblockAttributes The attributes set on the codeblock tag
     * @param Node $body The body node contained within the codeblock tag
     * @param int $lineno The line number of this node (for debugging)
     */
    public function __construct(
        private readonly HighlighterReference $highlighterReference,
        private readonly Attributes $codeblockAttributes,
        Node $body,
        int $lineno,
    ) {
        parent::__construct(['body' => $body], $this->codeblockAttributes->toArray(), $lineno);
    }

    /**
     * Compiles the node into PHP code for execution by Twig
     *
     * @param Compiler $compiler The compiler to which we add the node's PHP code
     */
    public function compile(Compiler $compiler): void
    {
        $compiler->addDebugInfo($this);

        $compiler
            ->write('$highlighter = \Ramsey\Twig\CodeBlock\Highlighter\HighlighterFactory::getHighlighter(')
            ->string($this->highlighterReference->highlighter)
            ->raw(', ')
            ->repr($this->highlighterReference->arguments)
            ->raw(");\n");

        $data = $this->getNode('body')->getAttribute('data');
        assert(is_string($data));

        $compiler
            ->write('$highlightedCode = $highlighter->highlight(')
            ->string($data)
            ->raw(', ')
            ->repr($this->codeblockAttributes->toArray())
            ->raw(");\n");

        if (strtolower($this->codeblockAttributes->format) === 'html') {
            $classnames = 'code-highlight-figure';
            if ($this->codeblockAttributes->class !== null) {
                $classnames .= ' ' . $this->codeblockAttributes->class;
            }

            $compiler
                ->write('$classnames = ')
                ->string($classnames)
                ->raw(";\n");

            $compiler
                ->write('$figcaption = ')
                ->string($this->getFigcaption())
                ->raw(";\n");

            $compiler
                ->write('yield sprintf(')
                ->string('<figure class="%s">%s%s</figure>')
                ->raw(', $classnames, $figcaption, $highlightedCode')
                ->raw(");\n");
        } else {
            $compiler->write('yield $highlightedCode;' . "\n");
        }
    }

    private function getFigcaption(): string
    {
        $figcaption = '';

        if ($this->codeblockAttributes->title !== null) {
            $figcaption = '<figcaption class="code-highlight-caption">';
            $figcaption .= '<span class="code-highlight-caption-title">';
            $figcaption .= $this->codeblockAttributes->title;
            $figcaption .= '</span>';
            $figcaption .= $this->getFigcaptionLink();
            $figcaption .= '</figcaption>';
        }

        return $figcaption;
    }

    private function getFigcaptionLink(): string
    {
        $link = '';

        if ($this->codeblockAttributes->linkUrl !== null) {
            $link = '<a class="code-highlight-caption-link" href="' . $this->codeblockAttributes->linkUrl . '">';
            $link .= $this->getFigcaptionLinkText();
            $link .= '</a>';
        }

        return $link;
    }

    private function getFigcaptionLinkText(): string
    {
        return $this->codeblockAttributes->linkText ?? 'link';
    }
}
