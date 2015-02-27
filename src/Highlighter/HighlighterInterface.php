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

namespace Ramsey\Twig\CodeBlock\Highlighter;

use Ramsey\Twig\CodeBlock\TokenParser\CodeBlockParser;

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
     * @param array $options Parsed codeblock options that may be used when highlighting
     *    the code; see {@see Ramsey\Twig\CodeBlock\TokenParser\CodeBlockParser::$attributes}
     *    for option details
     * @return string
     */
    public function highlight($code, array $options = []);
}
