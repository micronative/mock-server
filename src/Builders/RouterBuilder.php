<?php

namespace Micronative\MockServer\Builders;

use Micronative\MockServer\Config\Endpoint;
use Micronative\MockServer\Exceptions\ConfigException;
use Micronative\MockServer\Routing\Router;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Yaml\Exception\ParseException;

class RouterBuilder implements BuilderInterface
{
    /** @var Endpoint[] */
    private array $endpoints;

    /**
     * @param array $endpoints
     */
    public function __construct(array $endpoints)
    {
        $this->endpoints = $endpoints;
    }

    /**
     * @return Router
     * @throws ConfigException
     */
    public function build(): Router
    {
        try {
            $routes = new RouteCollection();
            foreach ($this->endpoints as $endpoint){
                $route = new Route($endpoint->getPath(), ['_endpoint' => $endpoint]);
                $routes->add($endpoint->getPath(), $route);
            }
            return new Router($routes);
        } catch (ParseException $exception) {
            throw new ConfigException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
