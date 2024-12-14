<?php

use Dgudovic\Framework\Http\Kernel;
use Dgudovic\Framework\Routing\Router;
use Dgudovic\Framework\Routing\RouterInterface;
use League\Container\Container;

$container = new Container();

$container->add(RouterInterface::class, Router::class);
$container->add(Kernel::class)->addArgument(RouterInterface::class);

return $container;