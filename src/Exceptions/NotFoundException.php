<?php

declare(strict_types=1);

namespace Gooyer\Container\Exceptions;

use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends ContainerException implements NotFoundExceptionInterface
{
}
