<?php

namespace Dgudovic\Framework\Http\Middleware;

use Dgudovic\Framework\Http\Middleware\MiddlewareInterface;
use Dgudovic\Framework\Http\Request;
use Dgudovic\Framework\Http\Response;

class Success implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {
        return new Response("OK", Response::HTTP_OK);
    }
}