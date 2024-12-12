<?php

namespace Dgudovic\Framework\Routing;

use Dgudovic\Framework\Http\HttpException;
use Dgudovic\Framework\Http\HttpRequestMethodException;
use Dgudovic\Framework\Http\Request;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    /**
     * @throws HttpException
     */
    public function dispatch(Request $request): array
    {
        [$handler, $vars] = $this->extractRouteInfo($request);

        if (is_array($handler)) {
            [$controller, $method] = $handler;
            $handler = [new $controller, $method];
        }

        return [$handler, $vars];
    }

    /**
     * @throws HttpException
     */
    private function extractRouteInfo(Request $request): array
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) {
            $routes = include BASE_PATH . '/routes/web.php';

            foreach ($routes as $route) {
                $routeCollector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPathInfo()
        );

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new HttpRequestMethodException('Route not found', 404);
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode(', ', $routeInfo[1]);
                throw new HttpRequestMethodException("Method not allowed. Allowed methods: $allowedMethods", 405);
            default:
                return [$routeInfo[1], $routeInfo[2]];
        }

    }
}