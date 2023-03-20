<?php

namespace Micronative\MockServer;


use Micronative\MockServer\Builders\EndpointBuilder;
use Micronative\MockServer\Builders\RouterBuilder;
use Micronative\MockServer\Exceptions\ConfigException;
use Micronative\MockServer\Exceptions\RequestMethodException;
use Micronative\MockServer\Routing\Router;
use PHPUnit\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class Server implements ServerInterface
{
    private array $configFiles;
    private Router $router;

    /**
     * @param array $configFiles
     * @throws ConfigException
     */
    public function __construct(array $configFiles)
    {
        $this->configFiles = $configFiles;
        $endpoints = (new EndpointBuilder($this->configFiles))->build();
        $this->router = (new RouterBuilder($endpoints))->build();
    }

    public function run(): void
    {
        $request = Request::createFromGlobals();
        try {
            $response = $this->router->process($request);
        } catch (\Exception $exception) {
            $response = $this->errorResponse($exception);
        }

        $this->log($request, $response);
        $response->send();
    }

    /**
     * @param \Exception $exception
     * @return Response
     */
    private function errorResponse(\Exception $exception): Response
    {
        return new Response($exception->getMessage(), 404);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    private function log(Request $request, Response $response): void
    {
        error_log(print_r('-- Request: ' . $request->getRequestUri(), true));
        error_log(print_r('-- Response: ' . $response->getContent(), true));
    }
}