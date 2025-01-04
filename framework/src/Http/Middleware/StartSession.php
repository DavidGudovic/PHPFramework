<?php

namespace Dgudovic\Framework\Http\Middleware;

use Dgudovic\Framework\Http\Middleware\MiddlewareInterface;
use Dgudovic\Framework\Http\Request;
use Dgudovic\Framework\Http\Response;
use Dgudovic\Framework\Session\SessionInterface;

class StartSession implements MiddlewareInterface
{
    public function __construct(private SessionInterface $session)
    {
    }

    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {
        $this->session->start();

        $request->setSession($this->session);

        return $requestHandler->handle($request);
    }
}