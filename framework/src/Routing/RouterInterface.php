<?php

namespace Dgudovic\Framework\Routing;

use Dgudovic\Framework\Http\Request;

interface RouterInterface
{
    public function dispatch(Request $request): array;
}