<?php

namespace Dgudovic\Framework\Http;

use Dgudovic\Framework\Routing\RouterInterface;
use Exception;
use League\Container\Container;
use Psr\Container\ContainerInterface;

readonly class Kernel
{
    private string $appEnv;
    public function __construct(private RouterInterface $router, private ContainerInterface $container)
    {
        $this->appEnv = $this->container->get('APP_ENV');
    }

    public function handle(Request $request): Response
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);
            $response = call_user_func_array($routeHandler, $vars);
        } catch (Exception $ex) {
            $response = $this->createExceptionResponse($ex);
        }

        return $response;
    }

    /**
     * @throws Exception
     */
    private function createExceptionResponse(Exception $ex): Response
    {
        if(in_array($this->appEnv, ['dev', 'test'])) {
            throw $ex;
        }

        if ($ex instanceof HttpException) {
            return new Response($ex->getMessage(), $ex->getStatusCode());
        }

        return new Response($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}