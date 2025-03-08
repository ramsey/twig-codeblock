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

use Ramsey\Twig\CodeBlock\Exception\RuntimeException;

use function is_a;
use function sprintf;

/**
 * Factory to get a highlighter by name or by fully-qualified classname
 */
final class HighlighterFactory
{
    /**
     * Returns an instance of a highlighter
     *
     * @param class-string<HighlighterInterface> $highlighter Fully-qualified
     *     classname of the highlighter
     * @param array<scalar | array<scalar | null> | null> $arguments Array of
     *     arguments to pass to the highlighter upon instantiation
     *
     * @throws RuntimeException if highlighter class does not exist or is not an instance of HighlighterInterface
     */
    public static function getHighlighter(string $highlighter, array $arguments = []): HighlighterInterface
    {
        if (is_a($highlighter, HighlighterInterface::class, true)) {
            return new $highlighter(...$arguments);
        }

        throw new RuntimeException(
            sprintf("'%s' must be an instance of '%s'", $highlighter, HighlighterInterface::class),
        );
    }

    /**
     * @throws RuntimeException
     */
    public static function getHighlighterFromReference(HighlighterReference $reference): HighlighterInterface
    {
        return self::getHighlighter($reference->highlighter, $reference->arguments);
    }
}
