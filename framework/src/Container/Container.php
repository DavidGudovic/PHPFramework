<?php

namespace Dgudovic\Framework\Container;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

class Container implements ContainerInterface
{

    private array $services = [];

    /**
     * @throws ContainerException
     */
    public function add(string $id, string|object $concrete = null): void
    {
        if (is_null($concrete)) {
            if (!class_exists($id)) {
                throw new ContainerException("Class $id does not exist");
            }

            $concrete = $id;
        }

        $this->services[$id] = $concrete;
    }

    public function get(string $id)
    {
        if (!$this->has($id)) {
            if (!class_exists($id)) {
                throw new ContainerException("Class $id could not be resolved");
            }

            $this->add($id);
        }

        return $this->resolve($this->services[$id]);
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    /**
     * @throws ReflectionException
     */
    private function resolve(string|object $class): object
    {
        if (is_object($class)) {
            return $class;
        }

        $reflector = new ReflectionClass($class);

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return $reflector->newInstance();
        }

        $constructorParameters = $constructor->getParameters();

        $classDependencies = $this->resolveDependencies($constructorParameters);

        return $reflector->newInstanceArgs($classDependencies);
    }

    private function resolveDependencies(array $reflectionParameters): array
    {
        $dependencies = [];

        /** @var ReflectionParameter $parameter */
        foreach ($reflectionParameters as $parameter) {
            $parameterType = $parameter->getType();

            $dependency = $this->get($parameterType->getName());

            $dependencies[] = $dependency;
        }

        return $dependencies;
    }
}