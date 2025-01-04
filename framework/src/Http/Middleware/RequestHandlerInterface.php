<?php

namespace Dgudovic\Framework\Http\Middleware;

use Dgudovic\Framework\Http\Request;
use Dgudovic\Framework\Http\Response;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;
}