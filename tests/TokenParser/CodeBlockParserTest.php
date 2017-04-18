<?php

namespace Ramsey\Twig\CodeBlock\Test\TokenParser;

use Ramsey\Twig\CodeBlock\Exception\RuntimeException;
use Ramsey\Twig\CodeBlock\TokenParser\CodeBlockParser;
use Ramsey\Twig\CodeBlock\Test\TestCase;
use Twig_Token;
use Twig_TokenStream;

class CodeBlockParserTest extends TestCase
{
    public function testTestTokenThrowsExceptionForFailure()
    {
        $parser = \Mockery::mock(CodeBlockParser::class)->makePartial();

        $tokenFoo = new Twig_Token(Twig_Token::STRING_TYPE, 'foo', 1234);
        $tokenBar = new Twig_Token(Twig_Token::STRING_TYPE, 'bar', 1235);

        $stream = new Twig_TokenStream([$tokenFoo, $tokenBar]);

        $testTokenMethod = new \ReflectionMethod($parser, 'testToken');
        $testTokenMethod->setAccessible(true);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Expected 'foo' token but received 'bar' instead at line 1234.");

        $testTokenMethod->invoke($parser, 'foo', $tokenBar, $stream);
    }
}
