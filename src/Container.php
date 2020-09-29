<?php

declare(strict_types=1);

namespace Gooyer\Container;

use Gooyer\Container\Exceptions\ContainerException;
use Gooyer\Container\Exceptions\NotFoundException;
use Gooyer\Container\Exceptions\ResolveException;

class Container implements Contracts\Container
{

    /**
     * Container instance
     * @var Container|null $instance
     */
    protected static ?Container $instance = null;

    /**
     * Container binding's
     * @var array<string, mixed>
     */
    protected array $bindings = [];

    /**
     * Instances resolved bindings
     * @var array
     */
    protected array $instances = [];

    /**
     * @param string $class
     * @return mixed|object
     * @throws ResolveException
     */
    private function resolveClass(string $class)
    {
        try {
            try {
                $classRef = new \ReflectionClass($this->get($class));
            } catch (NotFoundException $exception) {
                $classRef = new \ReflectionClass($class);
            }

            $constructorRef = $classRef->getConstructor();

            if (!$classRef->isInstantiable()) {
                throw new ResolveException("{$classRef->name} is not instantiable.");
            }
            if (is_null($constructorRef)) {
                return $classRef->newInstance();
            } else {
                $args = $this->resolveParameters(...$constructorRef->getParameters());
                $result = $classRef->newInstanceArgs($args);
                $this->instances[$class] = $result;
                return $result;
            }
        } catch (\ReflectionException $reflectionException) {
            throw new ResolveException($reflectionException->getMessage());
        }
    }

    /**
     * @param \ReflectionParameter ...$parameters
     * @return array<int, mixed>
     * @throws ContainerException
     * @throws NotFoundException
     * @throws ResolveException
     */
    protected function resolveParameters(\ReflectionParameter ...$parameters): array
    {
        $results = [];

        foreach ($parameters as $parameter) {
            $paramClass = $parameter->getClass();
            if (is_null($paramClass)) {
                if ($parameter->getType()->isBuiltin()) {
                    array_push($results, $this->get($parameter->getName()));
                }
            } else {
                array_push($results, $this->resolveClass($paramClass->getName()));
            }
        }

        return $results;
    }
    /**
     * @param string $abstract
     * @param array|null $parameters
     * @return mixed|void
     * @throws ContainerException
     * @throws NotFoundException
     * @throws \Gooyer\Container\Exceptions\ResolveException
     */
    public function make(string $abstract, array $parameters = [])
    {
        try {
            $resolvedAbstract = $this->get($abstract);
            if (is_string($resolvedAbstract)) {
                if (isset($this->instances[$resolvedAbstract])) {
                    return $this->instances[$resolvedAbstract];
                } else {
                    return $this->resolveClass($resolvedAbstract);
                }
            } elseif ($resolvedAbstract instanceof \Closure) {
                return $resolvedAbstract->call($this, ...$parameters);
            }
        } catch (NotFoundException $exception) {
            if (class_exists($abstract, true)) {
                return $this->resolveClass($abstract);
            }
            throw $exception;
        }

    }

    public function bind(string $abstract, $result): void
    {
        $this->bindings[$abstract] = $result;
    }

    public static function instance(): Container
    {
        return is_null(self::$instance)
            ? self::$instance = new Container()
            : self::$instance;
    }

    /**
     * @param string $id
     * @return mixed
     * @throws NotFoundException
     * @throws ContainerException
     */
    public function get($id)
    {
        if ($this->has($id)) {
            return $this->bindings[$id];
        }

        throw new NotFoundException("Abstract {$id} not found.");
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return isset($this->bindings[$id]);
    }
}
