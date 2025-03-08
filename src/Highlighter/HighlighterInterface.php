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

/**
 * Enforces a common interface for all highlighters used by the
 * Ramsey\Twig\CodeBlock extension
 */
interface HighlighterInterface
{
    /**
     * Returns the syntax-highlighted code
     *
     * @param string $code The source code to highlight
     * @param array<scalar | array<scalar | null> | null> $options Parsed
     *     codeblock options that may be used when highlighting the code; see
     *     {@see \Ramsey\Twig\CodeBlock\Attributes} for option details
     */
    public function highlight(string $code, array $options = []): string;
}
