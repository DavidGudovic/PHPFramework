<?php

namespace Dgudovic\Framework\Http\Middleware;

use Dgudovic\Framework\Http\Request;
use Dgudovic\Framework\Http\Response;
use Dgudovic\Framework\Routing\RouterInterface;
use Psr\Container\ContainerInterface;

readonly class RouterDispatch implements MiddlewareInterface
{
    public function __construct(
        private RouterInterface    $router,
        private ContainerInterface $container
    )
    {
    }

    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {
        [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);

        return call_user_func_array($routeHandler, $vars);
    }
}