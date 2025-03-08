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

namespace Ramsey\Twig\CodeBlock\TokenParser;

use Ramsey\Twig\CodeBlock\Attributes;
use Ramsey\Twig\CodeBlock\Exception\SyntaxException;
use Ramsey\Twig\CodeBlock\Highlighter\HighlighterReference;
use Ramsey\Twig\CodeBlock\Node\CodeBlockNode;
use Twig\Error\SyntaxError;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Node;
use Twig\Parser;
use Twig\Source;
use Twig\Token;
use Twig\TokenParser\TokenParserInterface;
use Twig\TokenStream;

use function assert;
use function is_bool;
use function is_scalar;

/**
 * Parses a codeblock tag for Twig
 */
final class CodeBlockParser implements TokenParserInterface
{
    private readonly Attributes $attributes;

    private Node $body;

    private Parser $parser;

    /**
     * @param HighlighterReference $highlighterReference Reference details for the highlighter to use
     */
    public function __construct(private readonly HighlighterReference $highlighterReference)
    {
        $this->attributes = new Attributes();
    }

    public function setParser(Parser $parser): void
    {
        $this->parser = $parser;
    }

    /**
     * @throws SyntaxError
     */
    public function parse(Token $token): Node
    {
        $this->parseCodeBlock();

        return new CodeBlockNode(
            $this->highlighterReference,
            $this->getAttributes(),
            $this->getBody(),
            $token->getLine(),
        );
    }

    public function getTag(): string
    {
        return 'codeblock';
    }

    /**
     * Returns true if $token is the endcodeblock tag
     *
     * @param Token $token Token to test for endcodeblock
     */
    public function decideBlockEnd(Token $token): bool
    {
        return $token->test('endcodeblock');
    }

    /**
     * Returns the attributes parsed from the codeblock tag
     */
    public function getAttributes(): Attributes
    {
        return $this->attributes;
    }

    /**
     * Returns a token representing the codeblock source code
     */
    public function getBody(): Node
    {
        return $this->body;
    }

    /**
     * Parses the options found on the codeblock tag for use by the node
     *
     * @throws SyntaxError
     */
    private function parseCodeBlock(): void
    {
        $stream = $this->parser->getStream();

        while (!$stream->getCurrent()->test(Token::BLOCK_END_TYPE)) {
            $this->parseEncounteredToken($stream->getCurrent(), $stream);
        }

        $stream->expect(Token::BLOCK_END_TYPE);
        $this->body = $this->parser->subparse([$this, 'decideBlockEnd'], true);
        $stream->expect(Token::BLOCK_END_TYPE);
    }

    /**
     * Parses each specific token found when looping through the codeblock tag
     *
     * @param Token $token The token being parsed
     * @param TokenStream $stream The token stream being traversed
     *
     * @throws SyntaxError
     */
    private function parseEncounteredToken(Token $token, TokenStream $stream): void
    {
        switch ($token->getValue()) {
            case 'lang':
                $this->attributes->lang = $this->getNextExpectedStringValueFromStream($stream);

                break;
            case 'format':
                $this->attributes->format = $this->getNextExpectedStringValueFromStream($stream);

                break;
            case 'start':
                $this->attributes->start = $this->getNextExpectedNumberValueFromStream($stream);

                break;
            case 'mark':
                $this->attributes->mark = $this->parseMarkOption($stream);

                break;
            case 'linenos':
                $this->attributes->linenos = $this->parseLinenosOption($stream);

                break;
            case 'class':
                $this->attributes->class = $this->getNextExpectedStringValueFromStream($stream);

                break;
            case 'title':
                $this->attributes->title = $this->getNextExpectedStringValueFromStream($stream);

                break;
            case 'link':
                $this->attributes->linkUrl = $this->getNextExpectedStringValueFromStream($stream);

                break;
            case 'link_text':
                $this->attributes->linkText = $this->getNextExpectedStringValueFromStream($stream);

                break;
        }
    }

    /**
     * Returns the mark option value from the mark token
     *
     * @throws SyntaxError
     */
    private function parseMarkOption(TokenStream $stream): string
    {
        $markValue = $this->getNextExpectedNumberValueFromStream($stream);

        while (
            $stream->test(Token::OPERATOR_TYPE)
            || $stream->test(Token::PUNCTUATION_TYPE)
            || $stream->test(Token::NUMBER_TYPE)
        ) {
            $value = $stream->getCurrent()->getValue();
            assert(is_scalar($value));
            $markValue .= $value;
            $stream->next();
        }

        return (string) $markValue;
    }

    /**
     * Returns the linenos option value from the linenos token
     *
     * @throws SyntaxError
     */
    private function parseLinenosOption(TokenStream $stream): bool
    {
        $stream->next();
        $stream->expect(Token::PUNCTUATION_TYPE);

        $expr = $this->parser->getExpressionParser()->parseExpression();

        if (!($expr instanceof ConstantExpression) || !is_bool($expr->getAttribute('value'))) {
            throw new SyntaxException(
                'The linenos option must be boolean true or false (i.e. linenos:false).',
                $stream->getCurrent()->getLine(),
                new Source(
                    $stream->getSourceContext()->getCode(),
                    $stream->getSourceContext()->getName(),
                    $stream->getSourceContext()->getPath(),
                ),
            );
        }

        return $expr->getAttribute('value');
    }

    /**
     * Helper method for the common operation of grabbing the next string value
     * from the stream
     *
     * @throws SyntaxError
     */
    private function getNextExpectedStringValueFromStream(TokenStream $stream): string
    {
        $stream->next();
        $stream->expect(Token::PUNCTUATION_TYPE);

        /** @var string */
        return $stream->expect(Token::STRING_TYPE)->getValue();
    }

    /**
     * Helper method for the common operation of grabbing the next int value
     * from the stream
     *
     * @throws SyntaxError
     */
    private function getNextExpectedNumberValueFromStream(TokenStream $stream): int
    {
        $stream->next();
        $stream->expect(Token::PUNCTUATION_TYPE);

        /** @var int */
        return $stream->expect(Token::NUMBER_TYPE)->getValue();
    }
}
