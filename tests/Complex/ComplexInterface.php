<?php

declare(strict_types=1);

namespace Gooyer\Container\Tests\Complex;

use Gooyer\Container\Tests\Simple\ExampleInterface;

interface ComplexInterface
{
    public function getValue(): ExampleInterface;
}