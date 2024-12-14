<?php

namespace Dgudovic\Framework\Http;

use Dgudovic\Framework\Routing\RouterInterface;
use Exception;
use League\Container\Container;
use Psr\Container\ContainerInterface;

readonly class Kernel
{


    public function __construct(private RouterInterface $router, private ContainerInterface $container)
    {

    }

    public function handle(Request $request): Response
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);
            $response = call_user_func_array($routeHandler, $vars);
        } catch (Exception $ex) {
            $response = new Response($ex->getMessage(), 500);
        }

        return $response;
    }
}