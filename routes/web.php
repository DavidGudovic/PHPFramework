<?php

use App\Controller\{HomeController, PostsController};
use Dgudovic\Framework\Http\Response;

return [
    ['GET', '/', [HomeController::class, 'index']],
    ['GET', '/posts/{id:\d+}', [PostsController::class, 'show']],
    ['GET', '/hello/{name:.+}', function (string $name) {
        return new Response("<h1>Hello, $name!</h1>");
    }]
];