<?php

namespace Micronative\MockServer\Builders;

use Bramus\Router\Router;
use Micronative\MockServer\Config\Endpoint;
use Micronative\MockServer\Config\Response;
use Micronative\MockServer\Exceptions\ConfigException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
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
        $request = Request::createFromGlobals();
        $code = $request->get(self::SERVER_MODE) ?? self::DEFAULT_CODE;
        try {
            $router = new Router();
            $router->setBasePath('/');
            foreach ($this->endpoints as $endpoint) {
                $response = $endpoint->getResponseByCode($code);
                $router->match(implode('|', array_values($endpoint->getMethods())), $endpoint->getPath(), function () use ($request, $response) {
                    $this->sendResponse($response);
                    $this->log($request, $response);
                });
            }

            return $router;
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