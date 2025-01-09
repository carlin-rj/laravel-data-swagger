<?php

namespace  Carlin\LaravelDataSwagger;

use Illuminate\Routing\Route;

class Util
{
    public static function getRouteByControllerAndMethod(string $controllerName, string $methodName): ?string
    {
        /** @var Route $route */
        foreach (\Illuminate\Support\Facades\Route::getRoutes() as $route) {
            if (str_contains($route->getActionName(), '@')) {
                [$controller, $method] = explode('@', $route->getActionName());
                if ($controller === $controllerName && $method === $methodName) {
                    return $route->uri();
                }
            }
        }

        return null;
    }
}
