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

namespace Ramsey\Twig\CodeBlock\Highlighter;

final readonly class HighlighterReference
{
    /**
     * @param class-string<HighlighterInterface> $highlighter Fully-qualified
     *     classname of the highlighter
     * @param array<scalar | array<scalar | null> | null> $arguments Array of arguments
     *     to pass to the highlighter upon instantiation
     */
    public function __construct(
        public string $highlighter,
        public array $arguments = [],
    ) {
    }
}
