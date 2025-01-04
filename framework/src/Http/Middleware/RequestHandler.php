<?php

namespace Dgudovic\Framework\Http\Middleware;

use Dgudovic\Framework\Http\Request;
use Dgudovic\Framework\Http\Response;
use Psr\Container\ContainerInterface;

class RequestHandler implements RequestHandlerInterface
{
    private array $middleware = [
        Authenticate::class,
        Success::class
    ];

    public function __construct(private readonly ContainerInterface $container)
    {
    }

    public function handle(Request $request): Response
    {
        if (empty($this->middleware)) {
            return new Response('No middleware defined', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $middlewareClass = array_shift($this->middleware);

        $middleware = $this->container->get($middlewareClass);

        return $middleware->process($request, $this);
    }
}