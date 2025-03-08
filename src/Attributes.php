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

use function array_filter;

/**
 * Attributes defined on the Twig `{% codeblock %}` tag
 */
final class Attributes
{
    /**
     * Additional CSS class to add to the rendered code block.
     *
     * If specifying more than one CSS class, separate each with a space.
     *
     * Note: Not all highlighters support this attribute.
     */
    public ?string $class = null;

    /**
     * The formatter to use when rendering the code block.
     *
     * For example, Pygments supports formatting as html, svg, latex, png, etc.
     *
     * Note: Not all highlighters support this attribute, and those that do may
     * support different formatters. Check with the highlighters' documentation
     * for more information.
     *
     * This defaults to "html."
     */
    public string $format = 'html';

    /**
     * The language of the code in the code block.
     *
     * For example: php, javascript, html, rust, c, bash, html+php, etc.
     *
     * Note: Some highlighters may attempt to guess the language if it is not
     * specified.
     */
    public ?string $lang = null;

    /**
     * Whether to display line numbers with the rendered code block.
     *
     * Note: Not all highlighters support this attribute.
     */
    public bool $linenos = true;

    /**
     * Figure captions may include links. If a `title` and `link_url` are present,
     * then `link_text` is the clickable text for the link alongside the caption
     * title.
     *
     * Note: Not all highlighters support this attribute.
     *
     * This defaults to "link."
     */
    public string $linkText = 'link';

    /**
     * Figure captions may include links. If a `title` and `link_url` are present,
     * a link will be rendered alongside the caption title.
     *
     * Note: Not all highlighters support this attribute.
     */
    public ?string $linkUrl = null;

    /**
     * Lines to be marked (highlighted) in the rendered code block.
     *
     * When marking lines, the lines are 1-indexed and always begin at 1, even
     * if `start` is a different value. Multiple lines may be specified,
     * separated by commas, and ranges may be specified using dashes.
     *
     * For example: `7,11-14,18`
     *
     * Note: Not all highlighters support this attribute.
     */
    public ?string $mark = null;

    /**
     * If `linenos` is `true`, then `start` may be used to set a different
     * starting number.
     *
     * Note: Not all highlighters support this attribute.
     */
    public ?int $start = null;

    /**
     * A figure caption title to render with the code block.
     *
     * Note: Not all highlighters support this attribute.
     */
    public ?string $title = null;

    /**
     * @return array<string, bool | int | string>
     */
    public function toArray(): array
    {
        return array_filter((array) $this, fn ($value) => $value !== null);
    }
}
