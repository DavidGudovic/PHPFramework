<?php declare(strict_types=1);

use Dgudovic\Framework\Http\Kernel;
use Dgudovic\Framework\Http\Request;
use Dgudovic\Framework\Routing\Router;

define('BASE_PATH', dirname(__DIR__));

require_once __DIR__ . '/../vendor/autoload.php';

$request = Request::createFromGlobals();

$kernel = new Kernel(new Router());
$kernel->handle($request)->send();