<?php

namespace Dgudovic\Framework\Http;

use Dgudovic\Framework\Routing\RouterInterface;
use Exception;

readonly class Kernel
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function handle(Request $request): Response
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request);
            $response = call_user_func_array($routeHandler, $vars);
        } catch (HttpException $exception) {
            $response = new Response($exception->getMessage(), $exception->getCode());
        } catch (Exception $exception) {
            $response = new Response('Internal server error', 500);
        }

        return $response;
    }
}