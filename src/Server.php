<?php

namespace Micronative\MockServer;

use Bramus\Router\Router;
use Micronative\MockServer\Builders\EndpointBuilder;
use Micronative\MockServer\Builders\RouterBuilder;
use Micronative\MockServer\Exceptions\ConfigException;

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
        $this->router->run();
    }
}