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

namespace Ramsey\Twig\CodeBlock;

use Ramsey\Twig\CodeBlock\Highlighter\HighlighterReference;
use Twig\Extension\ExtensionInterface;

/**
 * A Twig extension providing codeblock tag functionality for marking up
 * blocks of source code in content (i.e., syntax highlighting)
 */
final readonly class CodeBlockExtension implements ExtensionInterface
{
    /**
     * @param HighlighterReference $highlighterReference Reference details for the highlighter to use
     */
    public function __construct(private HighlighterReference $highlighterReference)
    {
    }

    /**
     * @inheritDoc
     */
    public function getFilters(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getFunctions(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getNodeVisitors(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getOperators(): array
    {
        return [[], []];
    }

    /**
     * @inheritDoc
     */
    public function getTests(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getTokenParsers(): array
    {
        return [
            new TokenParser\CodeBlockParser($this->highlighterReference),
        ];
    }
}
