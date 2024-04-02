<?php

namespace App\Lib\Route;

use Symfony\Component\Routing\Loader\AttributeClassLoader;
use Symfony\Component\Routing\Route;

class AttributeRouteControllerLoader extends AttributeClassLoader
{
    /**
     * Configures the _controller default parameter of a given Route instance.
     */
    protected function configureRoute(Route $route, \ReflectionClass $class, \ReflectionMethod $method, object $annot): void
    {
        $route->setDefault('_controller', $this->toSnakeCase($class->getShortName()).'::'.$method->getName());
    }

    private function toSnakeCase($controllerName) : string {
        $parts = explode('Controller', $controllerName);
        $namePart = strtolower($parts[0]);
        $namePart = str_replace('_', ' ', $namePart);
        $namePart = ucwords($namePart);
        $namePart = str_replace(' ', '', $namePart);
        if (str_contains($namePart, 'api')) $namePart = str_replace('api', 'Api', $namePart);
        return $namePart . 'Controller';
    }

}