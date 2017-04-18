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

namespace Ramsey\Twig\CodeBlock\TokenParser;

use Ramsey\Twig\CodeBlock\Exception\RuntimeException;
use Ramsey\Twig\CodeBlock\Exception\SyntaxException;
use Ramsey\Twig\CodeBlock\Node\CodeBlockNode;

/**
 * Parses a codeblock tag for Twig
 */
class CodeBlockParser extends \Twig_TokenParser
{
    /**
     * An array of codeblock attributes
     *
     * @var array
     */
    protected $attributes = [
        'format' => 'html',
        'linenos' => true,
    ];

    /**
     * The code to highlight
     *
     * @var \Twig_NodeInterface
     */
    protected $body;

    /**
     * Name or fully-qualified classname of the highlighter
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
     * Creates a codeblock tag parser
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
     * Parses the codeblock tag and returns a node for Twig to use
     *
     * @param \Twig_Token $token The codeblock tag to parse
     * @return CodeBlockNode
     */
    public function parse(\Twig_Token $token)
    {
        $this->parseCodeBlock();

        return new CodeBlockNode(
            $this->highlighterName,
            $this->highlighterArgs,
            $this->getAttributes(),
            $this->getBody(),
            $token->getLine(),
            $this->getTag()
        );
    }

    /**
     * Returns boolean true if $token is the endcodeblock tag
     *
     * @param \Twig_Token $token Token to test for endcodeblock
     * @return boolean
     */
    public function decideBlockEnd(\Twig_Token $token)
    {
        return $token->test('endcodeblock');
    }

    /**
     * Returns the name of the codeblock tag
     *
     * @return string
     */
    public function getTag()
    {
        return 'codeblock';
    }

    /**
     * Returns the options parsed from the codeblock tag
     *
     * @return array
     * @see self::$attributes
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Returns a token representing the codeblock source code
     *
     * @return \Twig_NodeInterface
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Parses the options found on the codeblock tag for use by the node
     */
    protected function parseCodeBlock()
    {
        $stream = $this->parser->getStream();

        while (!$stream->getCurrent()->test(\Twig_Token::BLOCK_END_TYPE)) {
            $this->parseEncounteredToken($stream->getCurrent(), $stream);
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);
        $this->body = $this->parser->subparse(array($this, 'decideBlockEnd'), true);
        $stream->expect(\Twig_Token::BLOCK_END_TYPE);
    }

    /**
     * Parses each specific token found when looping through the codeblock tag
     *
     * @param \Twig_Token $token The token being parsed
     * @param \Twig_TokenStream $stream The token stream being traversed
     */
    protected function parseEncounteredToken(\Twig_Token $token, \Twig_TokenStream $stream)
    {
        switch ($token->getValue()) {
            case 'lang':
                $this->attributes['lang'] = $this->parseLangOption($token, $stream);
                break;

            case 'format':
                $this->attributes['format'] = $this->parseFormatOption($token, $stream);
                break;

            case 'start':
                $this->attributes['start'] = $this->parseStartOption($token, $stream);
                break;

            case 'mark':
                $this->attributes['mark'] = $this->parseMarkOption($token, $stream);
                break;

            case 'linenos':
                $this->attributes['linenos'] = $this->parseLinenosOption($token, $stream);
                break;

            case 'class':
                $this->attributes['class'] = $this->parseClassOption($token, $stream);
                break;

            case 'title':
                $this->attributes['title'] = $this->parseTitleOption($token, $stream);
                break;

            case 'link':
                $this->attributes['linkUrl'] = $this->parseLinkOption($token, $stream);
                break;

            case 'link_text':
                $this->attributes['linkText'] = $this->parseLinkTextOption($token, $stream);
                break;
        }
    }

    /**
     * Returns true if the token matches the expected value
     *
     * @param string $value The expected value
     * @param \Twig_Token $token The token to test
     * @param \Twig_TokenStream $stream The token stream being traversed
     * @param boolean
     */
    protected function testToken($value, \Twig_Token $token, \Twig_TokenStream $stream)
    {
        if (!$token->test($value)) {
            throw new RuntimeException(
                sprintf("Expected '%s' token but received '%s' instead.", $value, $token->getValue()),
                $stream->getCurrent()->getLine(),
                $this->getStreamFilename($stream)
            );
        }

        return true;
    }

    /**
     * Returns the programming language option value from the lang token
     *
     * @param \Twig_Token $token The token to parse
     * @param \Twig_TokenStream $stream The token stream being traversed
     * @return string
     */
    protected function parseLangOption(\Twig_Token $token, \Twig_TokenStream $stream)
    {
        $this->testToken('lang', $token, $stream);

        return $this->getNextExpectedStringValueFromStream($stream, \Twig_Token::STRING_TYPE);
    }

    /**
     * Returns the output format option value from the format token
     *
     * @param \Twig_Token $token The token to parse
     * @param \Twig_TokenStream $stream The token stream being traversed
     * @return string
     */
    protected function parseFormatOption(\Twig_Token $token, \Twig_TokenStream $stream)
    {
        $this->testToken('format', $token, $stream);

        return $this->getNextExpectedStringValueFromStream($stream, \Twig_Token::STRING_TYPE);
    }

    /**
     * Returns the start option value from the start token
     *
     * @param \Twig_Token $token The token to parse
     * @param \Twig_TokenStream $stream The token stream being traversed
     * @return integer
     */
    protected function parseStartOption(\Twig_Token $token, \Twig_TokenStream $stream)
    {
        $this->testToken('start', $token, $stream);

        return $this->getNextExpectedStringValueFromStream($stream, \Twig_Token::NUMBER_TYPE);
    }

    /**
     * Returns the mark option value from the mark token
     *
     * @param \Twig_Token $token The token to parse
     * @param \Twig_TokenStream $stream The token stream being traversed
     * @return string
     */
    protected function parseMarkOption(\Twig_Token $token, \Twig_TokenStream $stream)
    {
        $this->testToken('mark', $token, $stream);

        $markValue = $this->getNextExpectedStringValueFromStream($stream, \Twig_Token::NUMBER_TYPE);

        while ($stream->test(\Twig_Token::OPERATOR_TYPE)
            || $stream->test(\Twig_Token::PUNCTUATION_TYPE)
            || $stream->test(\Twig_Token::NUMBER_TYPE)
        ) {
            $markValue .= $stream->getCurrent()->getValue();
            $stream->next();
        }

        return $markValue;
    }

    /**
     * Returns the linenos option value from the linenos token
     *
     * @param \Twig_Token $token The token to parse
     * @param \Twig_TokenStream $stream The token stream being traversed
     * @return boolean
     */
    protected function parseLinenosOption(\Twig_Token $token, \Twig_TokenStream $stream)
    {
        $this->testToken('linenos', $token, $stream);

        return $this->getNextExpectedBoolValueFromStream($stream, 'linenos');
    }

    /**
     * Returns the class option value from the class token
     *
     * @param \Twig_Token $token The token to parse
     * @param \Twig_TokenStream $stream The token stream being traversed
     * @return string
     */
    protected function parseClassOption(\Twig_Token $token, \Twig_TokenStream $stream)
    {
        $this->testToken('class', $token, $stream);

        return $this->getNextExpectedStringValueFromStream($stream, \Twig_Token::STRING_TYPE);
    }

    /**
     * Returns the title option value from the title token
     *
     * @param \Twig_Token $token The token to parse
     * @param \Twig_TokenStream $stream The token stream being traversed
     * @return string
     */
    protected function parseTitleOption(\Twig_Token $token, \Twig_TokenStream $stream)
    {
        $this->testToken('title', $token, $stream);

        return $this->getNextExpectedStringValueFromStream($stream, \Twig_Token::STRING_TYPE);
    }

    /**
     * Returns the link option value from the link token
     *
     * @param \Twig_Token $token The token to parse
     * @param \Twig_TokenStream $stream The token stream being traversed
     * @return string
     */
    protected function parseLinkOption(\Twig_Token $token, \Twig_TokenStream $stream)
    {
        $this->testToken('link', $token, $stream);

        return $this->getNextExpectedStringValueFromStream($stream, \Twig_Token::STRING_TYPE);
    }

    /**
     * Returns the link_text option value from the link_text token
     *
     * @param \Twig_Token $token The token to parse
     * @param \Twig_TokenStream $stream The token stream being traversed
     * @return string
     */
    protected function parseLinkTextOption(\Twig_Token $token, \Twig_TokenStream $stream)
    {
        $this->testToken('link_text', $token, $stream);

        return $this->getNextExpectedStringValueFromStream($stream, \Twig_Token::STRING_TYPE);
    }

    /**
     * Helper method for the common operation of grabbing the next string value
     * from the stream
     *
     * @param \Twig_TokenStream $stream
     * @param int $type
     * @return string
     */
    protected function getNextExpectedStringValueFromStream(
        \Twig_TokenStream $stream,
        $type
    ) {
        $stream->next();
        $stream->expect(\Twig_Token::PUNCTUATION_TYPE);

        return $stream->expect($type)->getValue();
    }

    /**
     * Helper method for the common operation of grabbing the next boolean value
     * from the stream
     *
     * @param \Twig_TokenStream $stream
     * @param string $optionName
     * @return string
     */
    protected function getNextExpectedBoolValueFromStream(
        \Twig_TokenStream $stream,
        $optionName
    ) {
        $stream->next();
        $stream->expect(\Twig_Token::PUNCTUATION_TYPE);
        $expr = $this->parser->getExpressionParser()->parseExpression();

        if (!($expr instanceof \Twig_Node_Expression_Constant) || !is_bool($expr->getAttribute('value'))) {
            throw new SyntaxException(
                sprintf(
                    'The %s option must be boolean true or false (i.e. %s:false).',
                    $optionName,
                    $optionName
                ),
                $stream->getCurrent()->getLine(),
                $this->getStreamFilename($stream)
            );
        }

        return $expr->getAttribute('value');
    }

    /**
     * Returns the filename for the given stream
     *
     * @param \Twig_TokenStream $stream
     * @return string
     */
    private function getStreamFilename(\Twig_TokenStream $stream)
    {
        if (method_exists($stream, 'getFilename')) {
            // Support for 1.x versions of Twig.
            // @codeCoverageIgnoreStart
            return $stream->getFilename();
            // @codeCoverageIgnoreEnd
        }

        return $stream->getSourceContext()->getName();
    }
}
