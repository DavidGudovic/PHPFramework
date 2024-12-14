<?php

namespace Dgudovic\Framework\Tests;

use Dgudovic\Framework\Container\Container;
use Dgudovic\Framework\Container\ContainerException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    #[Test] public function a_service_can_be_retrieved_from_the_container(): void
    {
        $container = new Container();

        $container->add('dependant-class', DependantClass::class);

        $this->assertInstanceOf(DependantClass::class, $container->get('dependant-class'));
    }

    #[Test] public function a_ContainerException_is_thrown_if_a_service_does_not_exist()
    {
        $container = new Container();

        $this->expectException(ContainerException::class);

        $container->add('non-existent-class');
    }

    #[Test] public function a_container_can_be_checked_for_a_service()
    {
        $container = new Container();

        $container->add('dependant-class', DependantClass::class);

        $this->assertTrue($container->has('dependant-class'));
    }

    #[Test] public function a_container_can_be_checked_for_a_non_existent_service()
    {
        $container = new Container();

        $this->assertFalse($container->has('non-existent-class'));
    }

    #[Test] public function services_can_be_recursively_autowired()
    {
        $container = new Container();

        $container->add('dependant-class', DependantClass::class);

        $dependantClass = $container->get('dependant-class');

        $this->assertInstanceOf(DependencyClass::class, $dependantClass->getDependency());
        $this->assertInstanceOf(SubDependency::class, $dependantClass->getDependency()->getSubDependency());
    }

    #[Test] public function a_service_can_be_added_to_the_container_as_an_instance()
    {
        $container = new Container();

        $dependency = new DependencyClass(new SubDependency());

        $container->add('dependency', $dependency);

        $this->assertSame($dependency, $container->get('dependency'));
    }

    #[Test] public function a_service_can_be_resolved_without_adding_it_to_the_container()
    {
        $container = new Container();

        $this->assertInstanceOf(DependantClass::class, $container->get(DependantClass::class));
    }
}