<?php

declare(strict_types=1);

namespace Gooyer\Container\Tests;

use Gooyer\Container\Container;
use Gooyer\Container\Exceptions\NotFoundException;
use Gooyer\Container\Tests\Complex\Complex;
use Gooyer\Container\Tests\Complex\ComplexInterface;
use Gooyer\Container\Tests\Simple\ExampleClass;
use Gooyer\Container\Tests\Simple\ExampleInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Example;

class ContainerTest extends \PHPUnit\Framework\TestCase
{
    public function testContainerInstance()
    {
        $container = new Container();
        $this->assertInstanceOf(\Gooyer\Container\Contracts\Container::class, $container);
        $this->assertInstanceOf(\Gooyer\Container\Contracts\Container::class, Container::instance());

    }

    public function testBindMethod()
    {
        $container = new Container();
        $id = "id.test";
        $container->bind($id, $id);
        $this->assertTrue($container->has($id));
        $this->assertSame($id, $container->get($id));
    }

    public function testBindMethodExpectException()
    {
        $container = new Container();
        $id = "id.test";
        $this->expectException(NotFoundException::class);
        $container->get($id);
    }

    public function testBindInterface()
    {
        $container = new Container();
        $container->bind(ExampleInterface::class, ExampleClass::class);
        $instance = $container->make(ExampleInterface::class);
        $this->assertInstanceOf(ExampleInterface::class, $instance);
        $this->assertEquals(1, $instance->getValue());
    }

    public function testBindInterfaceComplexClass()
    {
        $container = new Container();
        $container->bind(ExampleInterface::class, ExampleClass::class);
        $container->bind(ComplexInterface::class, Complex::class);

        $complex = $container->make(ComplexInterface::class);
        $this->assertInstanceOf(ComplexInterface::class, $complex);
    }

    public function testBindSingletonInstance()
    {
        $container = new Container();
        $container->bind(ExampleInterface::class, ExampleClass::class);
        $container->bind(ComplexInterface::class, Complex::class);

        $complexSingletonFirst = $container->make(ComplexInterface::class);
        $complexSingletonSecond = $container->make(ComplexInterface::class);
        $this->assertInstanceOf(ComplexInterface::class, $complexSingletonFirst);
        $this->assertInstanceOf(ComplexInterface::class, $complexSingletonSecond);
    }
    public function testBindClosure()
    {
        $container = new Container();
        $closure = fn () => ['test' => 1];
        $container->bind("config", $closure);
        $config = $container->make("config");
        $this->assertIsArray($config);
        $this->assertSame($config, $closure());

    }
}