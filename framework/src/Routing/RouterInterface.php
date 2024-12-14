<?php

namespace Dgudovic\Framework\Routing;

use Dgudovic\Framework\Http\Request;
use Psr\Container\ContainerInterface;

interface RouterInterface
{
    public function dispatch(Request $request, ContainerInterface $container): array;
    public function setRoutes(array $routes): void;
}