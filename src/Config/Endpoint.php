<?php

namespace Micronative\MockServer\Config;

class Endpoint
{
    private ?string $path;
    private ?array $methods;
    private ?Response $response;

    /**
     * @param string|null $path
     * @param array|null $methods
     * @param Response|null $response
     */
    public function __construct(string $path = null, array $methods = null, Response $response = null)
    {
        $this->path = $path;
        $this->methods = $methods;
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Endpoint
     */
    public function setPath(string $path): Endpoint
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param array $methods
     * @return Endpoint
     */
    public function setMethods(array $methods): Endpoint
    {
        $this->methods = $methods;
        return $this;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @param Response $response
     * @return Endpoint
     */
    public function setResponse(Response $response): Endpoint
    {
        $this->response = $response;
        return $this;
    }
}
