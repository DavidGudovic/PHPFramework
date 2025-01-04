<?php

use Dgudovic\Framework\{Console\Application,
    Console\Command\MigrateDatabase,
    Controller\AbstractController,
    Dbal\ConnectionFactory,
    Http\Kernel,
    Http\Middleware\RequestHandler,
    Http\Middleware\RequestHandlerInterface,
    Routing\Router,
    Routing\RouterInterface,
    Session\Session,
    Session\SessionInterface,
    Template\TwigFactory};
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

$container->add(RequestHandlerInterface::class, RequestHandler::class)->addArgument($container);

$container->add(Kernel::class)
    ->addArguments([
        RouterInterface::class,
        $container,
        RequestHandlerInterface::class
    ]);

$container->add(\Dgudovic\Framework\Console\Kernel::class)
    ->addArguments([$container, Application::class]);

$container->addShared(SessionInterface::class, Session::class);

$container->add('template-renderer-factory', TwigFactory::class)
    ->addArguments([
        SessionInterface::class,
        new StringArgument($templatesPath)
    ]);

$container->addShared('twig', function () use ($container) {
    return $container->get('template-renderer-factory')->create();
});

$container->inflector(AbstractController::class)->invokeMethod('setContainer', [$container]);

$container->add(ConnectionFactory::class)->addArguments([new StringArgument($databaseUrl)]);

$container->addShared(Connection::class, fn() => $container->get(ConnectionFactory::class)->create());

$container->add('database:migrations:migrate', MigrateDatabase::class)
    ->addArguments([Connection::class, new StringArgument(BASE_PATH . '/migrations')]);

return $container;