<?php

declare(strict_types=1);

namespace Gooyer\Container\Contracts;

use Gooyer\Container\Exceptions\ContainerException;
use Gooyer\Container\Exceptions\NotFoundException;
use Psr\Container\ContainerInterface;

interface Container extends ContainerInterface
{
    /**
     * @param string $abstract
     * @return mixed|void
     * @throws NotFoundException
     * @throws ContainerException
     */
    public function make(string $abstract);

    /**
     * @param string $abstract
     * @param mixed $result
     * @return mixed
     */
    public function bind(string $abstract, $result);

    /**
     * @return \Gooyer\Container\Container
     */
    public static function instance(): \Gooyer\Container\Container;
}
