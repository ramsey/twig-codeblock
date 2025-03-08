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

final class Attributes
{
    public ?string $class = null;

    public string $format = 'html';

    public ?string $lang = null;

    public bool $linenos = true;

    public ?string $linkText = null;

    public ?string $linkUrl = null;

    public ?string $mark = null;

    public ?int $start = null;

    public ?string $title = null;

    /**
     * @return array{
     *     class?: string,
     *     format: string,
     *     lang?: string,
     *     linenos: bool,
     *     linkText?: string,
     *     linkUrl?: string,
     *     mark?: string,
     *     start?: int,
     *     title?: string,
     * }
     */
    public function toArray(): array
    {
        return array_filter([
            'class' => $this->class,
            'format' => $this->format,
            'lang' => $this->lang,
            'linenos' => $this->linenos,
            'linkText' => $this->linkText,
            'linkUrl' => $this->linkUrl,
            'mark' => $this->mark,
            'start' => $this->start,
            'title' => $this->title,
        ], fn ($value) => $value !== null);
    }
}
