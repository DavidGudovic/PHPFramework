<?php

namespace Dgudovic\Framework\Http\Middleware;

use Dgudovic\Framework\Http\Middleware\MiddlewareInterface;
use Dgudovic\Framework\Http\Request;
use Dgudovic\Framework\Http\Response;

class Authenticate implements MiddlewareInterface
{
    private bool $authenticated = true;
    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {
        if (!$this->authenticated) {
            return new Response('Authentication required', Response::HTTP_UNAUTHORIZED);
        }

        return $requestHandler->handle($request);
    }
}