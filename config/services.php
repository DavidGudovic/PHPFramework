<?php

use Dgudovic\Framework\{
    Http\Kernel,
    Routing\Router,
    Routing\RouterInterface
};
use League\Container\{
    Argument\Literal\ArrayArgument,
    Argument\Literal\StringArgument,
    Container,
    ReflectionContainer
};
use Symfony\Component\Dotenv\Dotenv;
use Twig\{
    Environment,
    Loader\FilesystemLoader
};

$dotenv = new Dotenv();
$dotenv->load(BASE_PATH . '/.env');

$container = new Container();

$container->delegate(new ReflectionContainer(true));

# parameters for application config
$routes = require_once BASE_PATH . '/routes/web.php';
$appEnv = $_ENV['APP_ENV'];

$templatesPath = BASE_PATH . '/templates';

$container->add('APP_ENV', new StringArgument($appEnv));

# services
$container->add(RouterInterface::class, Router::class)->addMethodCall('setRoutes', [new ArrayArgument($routes)]);

$container->add(Kernel::class)
    ->addArgument(RouterInterface::class)
    ->addArgument($container);

$container->addShared('filesystem-loader', FilesystemLoader::class)->addArgument(new StringArgument($templatesPath));
$container->addShared(Environment::class)->addArgument('filesystem-loader');

return $container;