<?php

namespace Ramsey\Tests\Twig\CodeBlock;

use Ramsey\Twig\CodeBlock\CodeBlockExtension;

class CodeBlockExtensionTest extends TestCase
{
    protected $highlighterName;
    protected $highlighterArgs;

    public function setUp()
    {
        $class = new \ReflectionClass('Ramsey\Twig\CodeBlock\CodeBlockExtension');

        $this->highlighterName = $class->getProperty('highlighterName');
        $this->highlighterName->setAccessible(true);

        $this->highlighterArgs = $class->getProperty('highlighterArgs');
        $this->highlighterArgs->setAccessible(true);
    }

    /**
     * @covers Ramsey\Twig\CodeBlock\CodeBlockExtension::__construct
     */
    public function testConstructorWithoutArgs()
    {
        $ext = new CodeBlockExtension();

        $this->assertEquals('pygments', $this->highlighterName->getValue($ext));
        $this->assertEquals([], $this->highlighterArgs->getValue($ext));
    }

    /**
     * @covers Ramsey\Twig\CodeBlock\CodeBlockExtension::__construct
     */
    public function testConstructorWithArgs()
    {
        $name = 'foobar';
        $args = ['foo1', 'foo2'];

        $ext = new CodeBlockExtension($name, $args);

        $this->assertEquals($name, $this->highlighterName->getValue($ext));
        $this->assertEquals($args, $this->highlighterArgs->getValue($ext));
    }

    /**
     * @covers Ramsey\Twig\CodeBlock\CodeBlockExtension::getName
     */
    public function testGetName()
    {
        $ext = new CodeBlockExtension();

        $this->assertEquals('codeblock', $ext->getName());
    }

    /**
     * @covers Ramsey\Twig\CodeBlock\CodeBlockExtension::getTokenParsers
     */
    public function testGetTokenParsers()
    {
        $ext = new CodeBlockExtension();

        $this->assertInternalType('array', $ext->getTokenParsers());
        $this->assertContainsOnlyInstancesOf('Twig_TokenParser', $ext->getTokenParsers());
    }
}
