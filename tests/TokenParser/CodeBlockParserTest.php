<?php

namespace Ramsey\Twig\CodeBlock\Test\TokenParser;

use Ramsey\Twig\CodeBlock\Test\TestCase;
use Ramsey\Twig\CodeBlock\CodeBlockExtension;
use Ramsey\Twig\CodeBlock\TokenParser\CodeBlockParser;

class CodeBlockParserTest extends TestCase
{
    protected $env;

    public function setUp()
    {
        $loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/twig');
        $this->env = new \Twig_Environment($loader, ['debug' => true]);
        $this->env->addExtension(new CodeBlockExtension('pygments', ['/foo/bin/pygmentize']));
        $this->env->setParser(new MockParser($this->env));
        $this->env->getParser()->setExpressionParser(
            new \Twig_ExpressionParser(
                $this->env->getParser(),
                $this->env->getUnaryOperators(),
                $this->env->getBinaryOperators()
            )
        );
    }

    public function testBasicParse()
    {
        $template = $this->env->getLoader()->getSource('codeblock-basic.html');
        $stream = $this->env->tokenize($template);
        $this->env->getParser()->setStream($stream);

        $codeblock = $this->env->getTokenParsers()->getParsers()['codeblock'];
        $codeblock->setParser($this->env->getParser());

        $node = null;
        while (!$stream->getCurrent()->test(\Twig_Token::BLOCK_END_TYPE)) {
            if ($stream->getCurrent()->test(\Twig_Token::NAME_TYPE)
                && $stream->getCurrent()->getValue() == 'codeblock') {
                $node = $codeblock->parse($stream->getCurrent());
                break;
            }
            $stream->next();
        }

        $this->assertInstanceOf('Ramsey\\Twig\\CodeBlock\\Node\\CodeBlockNode', $node);
        $this->assertEquals('pygments', $node->getHighlighterName());
        $this->assertEquals(['/foo/bin/pygmentize'], $node->getHighlighterArgs());
        $this->assertTrue($node->hasAttribute('lang'));
        $this->assertEquals('php', $node->getAttribute('lang'));
        $this->assertTrue($node->hasNode('body'));
        $this->assertEquals("<?php\nphpinfo();\n", $node->getNode('body')->getAttribute('data'));
    }

    public function testAdvancedParse()
    {
        $template = $this->env->getLoader()->getSource('codeblock-advanced.html');
        $stream = $this->env->tokenize($template);
        $this->env->getParser()->setStream($stream);

        $codeblock = $this->env->getTokenParsers()->getParsers()['codeblock'];
        $codeblock->setParser($this->env->getParser());

        $node = null;
        while (!$stream->getCurrent()->test(\Twig_Token::BLOCK_END_TYPE)) {
            if ($stream->getCurrent()->test(\Twig_Token::NAME_TYPE)
                && $stream->getCurrent()->getValue() == 'codeblock') {
                $node = $codeblock->parse($stream->getCurrent());
                break;
            }
            $stream->next();
        }

        $this->assertInstanceOf('Ramsey\\Twig\\CodeBlock\\Node\\CodeBlockNode', $node);
        $this->assertEquals('pygments', $node->getHighlighterName());
        $this->assertEquals(['/foo/bin/pygmentize'], $node->getHighlighterArgs());
        $this->assertTrue($node->hasAttribute('lang'));
        $this->assertEquals('php', $node->getAttribute('lang'));
        $this->assertTrue($node->hasAttribute('format'));
        $this->assertEquals('html', $node->getAttribute('format'));
        $this->assertTrue($node->hasAttribute('start'));
        $this->assertEquals('1', $node->getAttribute('start'));
        $this->assertTrue($node->hasAttribute('end'));
        $this->assertEquals('2', $node->getAttribute('end'));
        $this->assertTrue($node->hasAttribute('range'));
        $this->assertEquals('1-2', $node->getAttribute('range'));
        $this->assertTrue($node->hasAttribute('mark'));
        $this->assertEquals('2-3,4', $node->getAttribute('mark'));
        $this->assertTrue($node->hasAttribute('linenos'));
        $this->assertTrue($node->getAttribute('linenos'));
        $this->assertTrue($node->hasAttribute('phpopentag'));
        $this->assertTrue($node->getAttribute('phpopentag'));
        $this->assertTrue($node->hasAttribute('title'));
        $this->assertEquals('Title Parameter', $node->getAttribute('title'));
        $this->assertTrue($node->hasAttribute('linkUrl'));
        $this->assertEquals('http://example.org/listing', $node->getAttribute('linkUrl'));
        $this->assertTrue($node->hasAttribute('linkText'));
        $this->assertEquals('Link Text', $node->getAttribute('linkText'));
        $this->assertTrue($node->hasNode('body'));
        $this->assertEquals("<?php\nphpinfo();\n", $node->getNode('body')->getAttribute('data'));
    }

    /**
     * @expectedException Ramsey\Twig\CodeBlock\Exception\SyntaxException
     * @expectedExceptionMessage The linenos option must be boolean true or false (i.e. linenos:false)
     */
    public function testLinenosException()
    {
        $template = $this->env->getLoader()->getSource('codeblock-linenos-exception.html');
        $stream = $this->env->tokenize($template);
        $this->env->getParser()->setStream($stream);

        $codeblock = $this->env->getTokenParsers()->getParsers()['codeblock'];
        $codeblock->setParser($this->env->getParser());

        $node = null;
        while (!$stream->getCurrent()->test(\Twig_Token::BLOCK_END_TYPE)) {
            if ($stream->getCurrent()->test(\Twig_Token::NAME_TYPE)
                && $stream->getCurrent()->getValue() == 'codeblock') {
                $node = $codeblock->parse($stream->getCurrent());
                break;
            }
            $stream->next();
        }
    }

    /**
     * @expectedException Ramsey\Twig\CodeBlock\Exception\SyntaxException
     * @expectedExceptionMessage The phpopentag option must be boolean true or false (i.e. phpopentag:false)
     */
    public function testPhpopentagException()
    {
        $template = $this->env->getLoader()->getSource('codeblock-phpopentag-exception.html');
        $stream = $this->env->tokenize($template);
        $this->env->getParser()->setStream($stream);

        $codeblock = $this->env->getTokenParsers()->getParsers()['codeblock'];
        $codeblock->setParser($this->env->getParser());

        $node = null;
        while (!$stream->getCurrent()->test(\Twig_Token::BLOCK_END_TYPE)) {
            if ($stream->getCurrent()->test(\Twig_Token::NAME_TYPE)
                && $stream->getCurrent()->getValue() == 'codeblock') {
                $node = $codeblock->parse($stream->getCurrent());
                break;
            }
            $stream->next();
        }
    }

    /**
     * @expectedException Ramsey\Twig\CodeBlock\Exception\RuntimeException
     * @expectedExceptionMessage Expected string token but received 'name' instead
     */
    public function testParseStringAttributeException()
    {
        $template = $this->env->getLoader()->getSource('codeblock-phpopentag-exception.html');
        $stream = $this->env->tokenize($template);
        $this->env->getParser()->setStream($stream);

        $codeblock = $this->env->getTokenParsers()->getParsers()['codeblock'];
        $codeblock->setParser($this->env->getParser());

        $reflection = new \ReflectionClass(get_class($codeblock));
        $method = $reflection->getMethod('parseStringAttribute');
        $method->setAccessible(true);

        $method->invokeArgs($codeblock, [
            new \Twig_Token(\Twig_Token::NAME_TYPE, 'foo', 1),
            $stream
        ]);
    }

    /**
     * @expectedException Ramsey\Twig\CodeBlock\Exception\RuntimeException
     * @expectedExceptionMessage Expected 'bar' token but received 'foo' instead
     */
    public function testTestTokenException()
    {
        $template = $this->env->getLoader()->getSource('codeblock-phpopentag-exception.html');
        $stream = $this->env->tokenize($template);
        $this->env->getParser()->setStream($stream);

        $codeblock = $this->env->getTokenParsers()->getParsers()['codeblock'];
        $codeblock->setParser($this->env->getParser());

        $reflection = new \ReflectionClass(get_class($codeblock));
        $method = $reflection->getMethod('testToken');
        $method->setAccessible(true);

        $method->invokeArgs($codeblock, [
            'bar',
            new \Twig_Token(\Twig_Token::NAME_TYPE, 'foo', 1),
            $stream
        ]);
    }
}

class MockParser extends \Twig_Parser
{
    public function setStream($stream)
    {
        $this->stream = $stream;
    }

    public function setExpressionParser($expressionParser)
    {
        $this->expressionParser = $expressionParser;
    }
}
