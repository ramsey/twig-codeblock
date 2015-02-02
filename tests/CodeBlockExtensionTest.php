<?php

namespace Rhumsaa\Tests\Twig\CodeBlock;

use Rhumsaa\Twig\CodeBlock\CodeBlockExtension;

class CodeBlockExtensionTest extends TestCase
{
    protected $highlighterName;
    protected $highlighterArgs;

    public function setUp()
    {
        $class = new \ReflectionClass('Rhumsaa\Twig\CodeBlock\CodeBlockExtension');

        $this->highlighterName = $class->getProperty('highlighterName');
        $this->highlighterName->setAccessible(true);

        $this->highlighterArgs = $class->getProperty('highlighterArgs');
        $this->highlighterArgs->setAccessible(true);
    }

    /**
     * @covers Rhumsaa\Twig\CodeBlock\CodeBlockExtension::__construct
     */
    public function testConstructorWithoutArgs()
    {
        $ext = new CodeBlockExtension();

        $this->assertEquals('pygments', $this->highlighterName->getValue($ext));
        $this->assertEquals([], $this->highlighterArgs->getValue($ext));
    }

    /**
     * @covers Rhumsaa\Twig\CodeBlock\CodeBlockExtension::__construct
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
     * @covers Rhumsaa\Twig\CodeBlock\CodeBlockExtension::getName
     */
    public function testGetName()
    {
        $ext = new CodeBlockExtension();

        $this->assertEquals('codeblock', $ext->getName());
    }

    /**
     * @covers Rhumsaa\Twig\CodeBlock\CodeBlockExtension::getTokenParsers
     */
    public function testGetTokenParsers()
    {
        $ext = new CodeBlockExtension();

        $this->assertInternalType('array', $ext->getTokenParsers());
        $this->assertContainsOnlyInstancesOf('Twig_TokenParser', $ext->getTokenParsers());
    }
}
