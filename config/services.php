<?php

use Dgudovic\Framework\Http\Kernel;
use Dgudovic\Framework\Routing\Router;
use Dgudovic\Framework\Routing\RouterInterface;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(BASE_PATH . '/.env');

$container = new Container();

$container->delegate(new ReflectionContainer(true));

# parameters for application config
$routes = require_once BASE_PATH . '/routes/web.php';
$appEnv = $_ENV['APP_ENV'];

$container->add('APP_ENV', new StringArgument($appEnv));

# services
$container->add(RouterInterface::class, Router::class)->addMethodCall('setRoutes', [new ArrayArgument($routes)]);

$container->add(Kernel::class)->addArgument(RouterInterface::class)->addArgument($container);

return $container;