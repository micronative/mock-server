<?php

namespace Micronative\MockServer\Builders;

use Micronative\MockServer\Config\Endpoint;
use Symfony\Component\Yaml\Yaml;

class EndpointBuilder implements BuilderInterface
{
    private array $configFiles;

    /**
     * @param array $configFiles
     */
    public function __construct(array $configFiles)
    {
        $this->configFiles = $configFiles;
    }

    /**
     * @return Endpoint[]
     */
    public function build(): array
    {
        $endpoints = [];
        foreach ($this->configFiles as $file) {
            $configs = Yaml::parseFile($file);
            foreach ($configs as $path => $item) {
                $response = (new ResponseBuilder($item['response']))->build();
                $endpoint = new Endpoint();
                $endpoint
                    ->setPath($path)
                    ->setMethods($item['methods'])
                    ->setResponse($response);
                $endpoints[] = $endpoint;
            }
        }
        return $endpoints;
    }
}
