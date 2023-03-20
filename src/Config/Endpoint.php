<?php

namespace Micronative\MockServer\Config;

class Endpoint
{
    private string $path;
    private array $methods;

    /** @var Response[] */
    private array $responses;

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
     * @return Response[]
     */
    public function getResponses(): array
    {
        return $this->responses;
    }

    /**
     * @param Response[] $responses
     * @return Endpoint
     */
    public function setResponses(array $responses): Endpoint
    {
        $this->responses = $responses;
        return $this;
    }

    /**
     * @param int $code
     * @return Response|null
     */
    public function getResponseByCode(int $code): ?Response
    {
        foreach ($this->getResponses() as $response) {
            if ($response->getCode() == $code) {
                return $response;
            }
        }

        return null;
    }
}