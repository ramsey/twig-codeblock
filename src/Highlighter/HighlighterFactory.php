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

/**
 * Factory to get a highlighter by name or by fully-qualified classname
 */
class HighlighterFactory
{
    /**
     * Returns an instance of a highlighter by name or fully-qualified classname
     *
     * @param string $highlighter Name or fully-qualified classname of the highlighter
     * @param array $arguments Array of arguments to pass to the highlighter upon instantiation
     * @return HighlighterInterface
     * @throws \RuntimeException if highlighter class does not exist or is not an instance of HighlighterInterface
     */
    public static function getHighlighter($highlighter = null, array $arguments = [])
    {
        if ($highlighter instanceof HighlighterInterface) {
            return $highlighter;
        }

        switch ($highlighter) {
            case 'pygments':
            case null:
                $highlighterClass = PygmentsHighlighter::class;
                break;
            default:
                // A different class name must have been specified
                $highlighterClass = (string) $highlighter;
                break;
        }

        try {
            $reflection = new \ReflectionClass($highlighterClass);
        } catch (\ReflectionException $e) {
            throw new \RuntimeException($e->getMessage());
        }

        if ($reflection->implementsInterface(HighlighterInterface::class)) {
            return $reflection->newInstanceArgs($arguments);
        }

        throw new \RuntimeException(
            sprintf(
                "'%s' must be an instance of '%s'.",
                $highlighterClass,
                HighlighterInterface::class
            )
        );
    }
}
