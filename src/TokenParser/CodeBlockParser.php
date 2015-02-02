<?php
/**
 * This file is part of the Rhumsaa\Twig\CodeBlock extension for Twig
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey (http://benramsey.com)
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace Rhumsaa\Twig\CodeBlock\TokenParser;

use Rhumsaa\Twig\CodeBlock\Exception\RuntimeException;
use Rhumsaa\Twig\CodeBlock\Exception\SyntaxException;
use Rhumsaa\Twig\CodeBlock\Node\CodeBlockNode;

/**
 * Parses a codeblock tag for Twig
 */
class CodeBlockParser extends \Twig_TokenParser
{
    /**
     * An array of codeblock attributes; may contain the following keys:
     *
     * * `lang`: Programming language for the code
     * * `format`: Output format for the code (defaults to "html")
     * * `linenos`: `true` if line numbers should be included in the highlighted code
     * * `start`: Starting line number for the code, if linenos is true
     * * `end`: Ending line number for the code, if linenos is true
     * * `range`: Provides starting and ending line numbers, if linenos is true (overrides start and end)
     * * `mark`: Mark one or more lines of code in the output. Accepts one number, numbers separated by
     *     commas, and number ranges. Example `mark:1,5-8` will mark lines 1,5,6,7,8. Note: If you've changed the beginning line number be sure these match rendered line numbers
     * * `title`: The figcaption title for the code block
     * * `linkUrl`: Download or reference link for the code
     * * `linkText`: Text for the linkUrl, defaults to "link"
     *
     * @var array
     */
    protected $attributes = ['format' => 'html'];

    /**
     * The code to highlight
     *
     * @var string
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
     * @var string
     */
    protected $highlighterArgs;

    /**
     * Creates a codeblock tag parser
     *
     * @param string $highligherName Name or fully-qualified classname of the
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
        $this->parseCodeBlock($token);

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
     * @return \Twig_Token
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Parses the options found on the codeblock tag for use by the node
     *
     * @param \Twig_Token $token The codeblock tag token
     */
    protected function parseCodeBlock(\Twig_Token $token)
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

            case 'end':
                $this->attributes['end'] = $this->parseEndOption($token, $stream);
                break;

            case 'range':
                $this->attributes['range'] = $this->parseRangeOption($token, $stream);
                break;

            case 'mark':
                $this->attributes['mark'] = $this->parseMarkOption($token, $stream);
                break;

            case 'linenos':
                $this->attributes['linenos'] = $this->parseLinenosOption($token, $stream);
                break;

            default:
                if ($token->test(\Twig_Token::STRING_TYPE)) {
                    $this->parseStringAttribute($token, $stream);
                }
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
                sprintf("Expected '%s' token but received '%s' instead", $value, $token->getValue()),
                $stream->getCurrent()->getLine(),
                $stream->getFilename()
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

        $stream->next();
        $stream->expect(\Twig_Token::PUNCTUATION_TYPE); // colon (:) separator
        $langValue = $stream->expect(\Twig_Token::NAME_TYPE);

        return $langValue->getValue();
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

        $stream->next();
        $stream->expect(\Twig_Token::PUNCTUATION_TYPE); // colon (:) separator
        $formatValue = $stream->expect(\Twig_Token::NAME_TYPE);

        return $formatValue->getValue();
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

        $stream->next();
        $stream->expect(\Twig_Token::PUNCTUATION_TYPE); // colon (:) separator
        $startValue = $stream->expect(\Twig_Token::NUMBER_TYPE);

        return $startValue->getValue();
    }

    /**
     * Returns the end option value from the end token
     *
     * @param \Twig_Token $token The token to parse
     * @param \Twig_TokenStream $stream The token stream being traversed
     * @return integer
     */
    protected function parseEndOption(\Twig_Token $token, \Twig_TokenStream $stream)
    {
        $this->testToken('end', $token, $stream);

        $stream->next();
        $stream->expect(\Twig_Token::PUNCTUATION_TYPE); // colon (:) separator
        $endValue = $stream->expect(\Twig_Token::NUMBER_TYPE);

        return $endValue->getValue();
    }

    /**
     * Returns the range option value from the range token
     *
     * @param \Twig_Token $token The token to parse
     * @param \Twig_TokenStream $stream The token stream being traversed
     * @return string
     */
    protected function parseRangeOption(\Twig_Token $token, \Twig_TokenStream $stream)
    {
        $this->testToken('range', $token, $stream);

        $stream->next();
        $stream->expect(\Twig_Token::PUNCTUATION_TYPE); // colon (:) separator
        $rangeLeft = $stream->expect(\Twig_Token::NUMBER_TYPE);
        $stream->expect(\Twig_Token::OPERATOR_TYPE); // range dash (-)
        $rangeRight = $stream->expect(\Twig_Token::NUMBER_TYPE);

        return $rangeLeft->getValue() . '-' . $rangeRight->getValue();
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

        $stream->next();
        $stream->expect(\Twig_Token::PUNCTUATION_TYPE); // colon (:) separator
        $markValue = $stream->expect(\Twig_Token::NUMBER_TYPE)->getValue();

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

        $stream->next();
        $stream->expect(\Twig_Token::PUNCTUATION_TYPE); // colon (:) separator
        $expr = $this->parser->getExpressionParser()->parseExpression();

        if (!($expr instanceof \Twig_Node_Expression_Constant) || !is_bool($expr->getAttribute('value'))) {
            throw new SyntaxException(
                'The linenos option must be boolean true or false (i.e. linenos:false)',
                $stream->getCurrent()->getLine(),
                $stream->getFilename()
            );
        }

        return $expr->getAttribute('value');
    }

    /**
     * Parses a string attribute token, saving it as either the title, link URL, or link text
     *
     * @param \Twig_Token $token The token to parse
     * @param \Twig_TokenStream $stream The token stream being traversed
     * @return void
     */
    protected function parseStringAttribute(\Twig_Token $token, \Twig_TokenStream $stream)
    {
        if (!$token->test(\Twig_Token::STRING_TYPE)) {
            throw new RuntimeException(
                sprintf(
                    "Expected string token but received '%s' instead",
                    $value,
                    \Twig_Token::typeToEnglish($token->getType())
                ),
                $stream->getCurrent()->getLine(),
                $stream->getFilename()
            );
        }

        // If any of these values has already been set, then set the next
        // property with this string. The string order that must be followed
        // in the codeblock tag is: title, url, link text
        if (empty($this->attributes['title'])) {
            $this->attributes['title'] = $token->getValue();
        } elseif (empty($this->attributes['linkUrl'])) {
            $this->attributes['linkUrl'] = $token->getValue();
        } elseif (empty($this->attributes['linkText'])) {
            $this->attributes['linkText'] = $token->getValue();
        }

        $stream->next();
    }
}
