<?php

namespace Dgudovic\Framework\Http\Middleware;

use Dgudovic\Framework\Http\Request;
use Dgudovic\Framework\Http\Response;

class RequestHandler implements RequestHandlerInterface
{
    private array $middleware = [
        Authenticate::class,
        Success::class
    ];

    public function handle(Request $request): Response
    {
        if (empty($this->middleware)) {
            return new Response('No middleware defined', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $middlewareClass = array_shift($this->middleware);

        return (new $middlewareClass())->process($request, $this);
    }
}