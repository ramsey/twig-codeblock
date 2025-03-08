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

use Ramsey\Pygments\Pygments;

use function array_merge;
use function explode;
use function implode;
use function is_int;
use function is_string;
use function range;
use function str_contains;
use function stripos;
use function strtolower;

/**
 * A syntax-highlighter that uses [Python Pygments](http://pygments.org/) and
 * the [ramsey/pygments](https://github.com/ramsey/pygments) library for
 * highlighting
 */
final readonly class PygmentsHighlighter implements HighlighterInterface
{
    private const DEFAULT_LEXER = 'text';

    /**
     * @param string $pygmentizePath The path to pygmentize or just "pygmentize,"
     *     if it's in the PATH
     */
    public function __construct(private string $pygmentizePath = 'pygmentize')
    {
    }

    /**
     * @inheritdoc
     */
    public function highlight(string $code, array $options = []): string
    {
        /** @var string $formatter */
        $formatter = $options['format'] ?? 'html';

        $lexer = $this->parseLexer($options);
        $pygmentsOptions = $this->parsePygmentsOptions($options, $formatter, $code);

        return $this->getPygments()->highlight($code, $lexer, $formatter, $pygmentsOptions);
    }

    /**
     * Returns the programming language from the options
     *
     * @param array<scalar | array<scalar | null> | null> $options
     */
    private function parseLexer(array $options): string
    {
        if (isset($options['lang']) && is_string($options['lang'])) {
            return strtolower($options['lang']) === 'plain' ? self::DEFAULT_LEXER : $options['lang'];
        }

        return self::DEFAULT_LEXER;
    }

    /**
     * Returns an array of options formatted for use with pygmentize
     *
     * @param array<scalar | array<scalar | null> | null> $options
     *
     * @return array{
     *     encoding: 'utf-8',
     *     linenos?: 'True' | 'table',
     *     linenostart?: int,
     *     hl_lines?: string,
     *     startinline?: 'True',
     * }
     */
    private function parsePygmentsOptions(array $options, string $formatter, string $code): array
    {
        $pygmentsOptions = ['encoding' => 'utf-8'];

        if (isset($options['linenos']) && $options['linenos'] === true) {
            $pygmentsOptions['linenos'] = $formatter === 'html' ? 'table' : 'True';
        }

        if (isset($options['start']) && is_int($options['start'])) {
            $pygmentsOptions['linenostart'] = $options['start'];
        }

        if (isset($options['mark']) && is_string($options['mark'])) {
            $pygmentsOptions['hl_lines'] = $this->parseMarks($options['mark']);
        }

        if (
            isset($options['lang'])
            && is_string($options['lang'])
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
     */
    private function parseMarks(string $marks): string
    {
        $markedLines = [];

        foreach (explode(',', $marks) as $nums) {
            if (str_contains($nums, '-')) {
                [$from, $to] = explode('-', $nums);
                $markedLines = array_merge($markedLines, range($from, $to));
            } else {
                $markedLines[] = $nums;
            }
        }

        return implode(' ', $markedLines);
    }

    private function getPygments(): Pygments
    {
        return new Pygments($this->pygmentizePath);
    }
}
