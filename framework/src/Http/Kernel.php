<?php

namespace Dgudovic\Framework\Http;

use Dgudovic\Framework\Http\Middleware\RequestHandlerInterface;
use Dgudovic\Framework\Routing\RouterInterface;
use Exception;
use Psr\Container\ContainerInterface;

readonly class Kernel
{
    private string $appEnv;
    public function __construct(
        private RouterInterface $router,
        private ContainerInterface $container,
        private RequestHandlerInterface $requestHandler
    )
    {
        $this->appEnv = $this->container->get('APP_ENV');
    }

    public function handle(Request $request): Response
    {
        try {
            $response = $this->requestHandler->handle($request);
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

        return new Response('Internal Server Error', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function terminate(Request $request, Response $response): void
    {
        $request->getSession()?->clearFlash();
    }
}