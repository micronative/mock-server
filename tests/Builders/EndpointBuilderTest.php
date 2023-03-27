<?php

namespace Tests\Builders;

use Micronative\MockServer\Builders\EndpointBuilder;
use Micronative\MockServer\Config\Endpoint;
use Micronative\MockServer\Config\Response;
use PHPUnit\Framework\TestCase;

class EndpointBuilderTest extends TestCase
{
    private EndpointBuilder $builder;

    protected function setUp(): void
    {
        $dir = dirname(__FILE__, 2) . '/assets/config/';
        $this->builder = new EndpointBuilder([$dir . 'product-api.yml', $dir . 'user-api.yml']);
        parent::setUp();
    }

    public function testBuild()
    {
        $endpoints = $this->builder->build();

        $this->assertIsArray($endpoints);
        $this->assertEquals(5, count($endpoints));

        $endpoint = $endpoints[0];
        $this->assertInstanceOf(Endpoint::class, $endpoint);
        $this->assertEquals('/product/{id}', $endpoint->getPath());
        $this->assertEquals(['GET', 'POST'], $endpoint->getMethods());

        $response = $endpoint->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('json', $response->getFormat());
        $this->assertEquals('{ "id":"5e8e44a0-2552-4acc-9964-f5ca07948486", "payload":{ "name":"Lamp" }, "created_at":"2022-09-05 03:37:54" }', $response->getContent());
    }
}