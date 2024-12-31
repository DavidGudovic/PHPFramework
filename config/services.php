<?php

use Dgudovic\Framework\{Console\Application,
    Console\Command\MigrateDatabase,
    Controller\AbstractController,
    Dbal\ConnectionFactory,
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
use Doctrine\DBAL\Connection;
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
$databaseUrl = 'sqlite:///' . BASE_PATH . '/var/db.sqlite';

$container->add('base-commands-namespace', new StringArgument('Dgudovic\\Framework\\Console\\Command\\'));

# services
$container->add(RouterInterface::class, Router::class)->addMethodCall('setRoutes', [new ArrayArgument($routes)]);

$container->add(Application::class)
    ->addArgument($container);

$container->add(Kernel::class)
    ->addArgument(RouterInterface::class)
    ->addArgument($container);

$container->add(\Dgudovic\Framework\Console\Kernel::class)
    ->addArguments([$container, Application::class]);

$container->addShared('filesystem-loader', FilesystemLoader::class)->addArgument(new StringArgument($templatesPath));
$container->addShared('twig', Environment::class)->addArgument('filesystem-loader');

$container->inflector(AbstractController::class)->invokeMethod('setContainer', [$container]);

$container->add(ConnectionFactory::class)->addArguments([new StringArgument($databaseUrl)]);

$container->addShared(Connection::class, fn() => $container->get(ConnectionFactory::class)->create());

$container->add('database:migrations:migrate', MigrateDatabase::class)->addArgument(Connection::class);

return $container;