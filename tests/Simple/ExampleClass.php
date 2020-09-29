<?php

declare(strict_types=1);

namespace Gooyer\Container\Tests\Simple;

class ExampleClass implements ExampleInterface
{
    public function __construct()
    {
        // Empty Constructor
    }
    public function getValue(): int
    {
        return 1;
    }
}