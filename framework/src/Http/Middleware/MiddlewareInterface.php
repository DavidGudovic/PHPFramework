<?php

namespace Dgudovic\Framework\Http\Middleware;

use Dgudovic\Framework\Http\Request;
use Dgudovic\Framework\Http\Response;

interface MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $requestHandler): Response;
}