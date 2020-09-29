<?php

declare(strict_types=1);

namespace Gooyer\Container\Tests\Complex;

use Gooyer\Container\Tests\Simple\ExampleInterface;

class Complex implements ComplexInterface
{
    private ExampleInterface $example;

    public function __construct(ExampleInterface $example)
    {
        $this->example = $example;
    }

    public function getValue(): ExampleInterface
    {
        return $this->example;
    }
}