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

namespace Ramsey\Twig\CodeBlock;

use Ramsey\Twig\CodeBlock\Highlighter\HighlighterFactory;

/**
 * A Twig extension providing codeblock tag functionality for marking up
 * blocks of source code in content (i.e. syntax highlighting)
 */
class CodeBlockExtension extends \Twig_Extension
{
    /**
     * Name or fully-qualified classname of the highlighter to use
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
     * Creates a codeblock Twig extension
     *
     * @param string $highlighterName Name or fully-qualified classname of the
     *     highlighter to use
     * @param array $highlighterArgs Array of constructor arguments to pass to
     *     the $highlighterName class upon instantiation
     */
    public function __construct($highlighterName = 'pygments', array $highlighterArgs = [])
    {
        $this->highlighterName = (string) $highlighterName;
        $this->highlighterArgs = $highlighterArgs;
    }

    /**
     * Returns the name of this extension
     *
     * @return string
     */
    public function getName()
    {
        return 'codeblock';
    }

    /**
     * Returns an array of token parsers provided by this extension
     *
     * @return array
     */
    public function getTokenParsers()
    {
        return [
            new TokenParser\CodeBlockParser($this->highlighterName, $this->highlighterArgs),
        ];
    }
}
