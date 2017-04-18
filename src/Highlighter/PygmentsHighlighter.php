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

use Ramsey\Pygments\Pygments;

/**
 * A syntax-highlighter that uses [Python Pygments](http://pygments.org/) and
 * the [ramsey/pygments](https://github.com/ramsey/pygments) library for
 * highlighting
 */
class PygmentsHighlighter implements HighlighterInterface
{
    /**
     * Default lexer
     */
    const DEFAULT_LEXER = 'text';

    /**
     * The path to pygmentize or just "pygmentize" if it's in the PATH
     *
     * @var string
     */
    protected $pygmentizePath;

    /**
     * Creates a highlighter that uses pygmentize
     *
     * @param string $pygmentizePath The path to pygmentize or just "pygmentize"
     *     if it's in the PATH
     */
    public function __construct($pygmentizePath = 'pygmentize')
    {
        $this->pygmentizePath = $pygmentizePath;
    }

    /**
     * {@inheritdoc}
     */
    public function highlight($code, array $options = [])
    {
        $lexer = $this->parseLexer($options);
        $format = $options['format'];
        $pygmentsOptions = $this->parsePygmentsOptions($options, $code);

        return $this
            ->getPygments($this->pygmentizePath)
            ->highlight($code, $lexer, $format, $pygmentsOptions);
    }

    /**
     * Returns the programming language from the options
     *
     * @return string
     */
    protected function parseLexer(array $options)
    {
        if (!empty($options['lang']) && strtolower($options['lang']) === 'plain') {
            return static::DEFAULT_LEXER;
        }

        if (!empty($options['lang'])) {
            return $options['lang'];
        }

        return static::DEFAULT_LEXER;
    }

    /**
     * Returns an array of options formatted for use with pygmentize
     *
     * @param array $options Options passed to the highlighter
     * @param string $code The code to highlight
     * @return array
     */
    protected function parsePygmentsOptions(array $options, $code)
    {
        $pygmentsOptions = ['encoding' => 'utf-8'];

        if (!empty($options['linenos']) && $options['linenos'] === true) {
            $pygmentsOptions['linenos'] = 'table';
        }

        if (!empty($options['start'])) {
            $pygmentsOptions['linenostart'] = $options['start'];
        }

        if (!empty($options['mark'])) {
            $pygmentsOptions['hl_lines'] = $this->parseMarks($options['mark']);
        }

        if (!empty($options['lang'])
            && strtolower($options['lang']) === 'php'
            && stripos($code, '<?php') === false
        ) {
            $pygmentsOptions['startinline'] = 'True';
        }

        return $pygmentsOptions;
    }

    /**
     * Parses the marked lines in a way that pygmentize can use
     *
     * @param string $marks The marks received from the codeblock
     * @return string
     */
    protected function parseMarks($marks)
    {
        $markedLines = [];

        foreach (explode(',', $marks) as $nums) {
            if (strpos($nums, '-') !== false) {
                list($from, $to) = explode('-', $nums);
                $markedLines = array_merge($markedLines, range($from, $to));
            } else {
                $markedLines[] = $nums;
            }
        }

        return implode(' ', $markedLines);
    }

    /**
     * Returns a Pygments instance object
     *
     * @param string $pygmentizePath The path to pygmentize or just "pygmentize" if it's in the PATH
     * @return Pygments
     * @codeCoverageIgnore
     */
    protected function getPygments($pygmentizePath)
    {
        return new Pygments($pygmentizePath);
    }
}
