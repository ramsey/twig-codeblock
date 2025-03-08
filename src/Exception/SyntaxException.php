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

namespace Ramsey\Twig\CodeBlock\Exception;

use Twig\Error\SyntaxError;

/**
 * Exception thrown when an error occurs when parsing the syntax of the Twig extension
 */
class SyntaxException extends SyntaxError
{
}
