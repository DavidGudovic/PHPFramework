<?php declare(strict_types=1);

use Dgudovic\Framework\Http\{Kernel, Request};

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

$container = require_once BASE_PATH . '/config/services.php';

$request = Request::createFromGlobals();

$kernel = $container->get(Kernel::class);

$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);