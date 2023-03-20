<?php

namespace Micronative\MockServer\Builders;

use Micronative\MockServer\Config\Endpoint;
use Micronative\MockServer\Config\Response;
use Micronative\MockServer\Exceptions\ConfigException;
use Micronative\MockServer\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Yaml\Exception\ParseException;

class RouterBuilder implements BuilderInterface
{
    const DEFAULT_CODE = 200;
    const SERVER_MODE = 'serverMode';

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

    /**
     * @param Response $response
     * @return void
     */
    private function sendResponse(Response $response)
    {
        $symfonyResponse = new SymfonyResponse($response->getContent(), $response->getCode());
        $symfonyResponse->send();
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    private function log(Request $request, Response $response)
    {
        error_log(print_r('-- Request: ' . $request->getRequestUri(), true));
        error_log(print_r('-- Response: ' . $response->getContent(), true));
    }
}