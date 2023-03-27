<?php

namespace Builders;

use Micronative\MockServer\Builders\EndpointBuilder;
use Micronative\MockServer\Builders\RouterBuilder;
use Micronative\MockServer\Routing\Router;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouteCollection;

class RouterBuilderTest extends TestCase
{
    private RouterBuilder $builder;

    protected function setUp(): void
    {
        $dir = dirname(__FILE__, 2) . '/assets/config/';
        $endpointBuilder = new EndpointBuilder([$dir . 'product-api.yml', $dir . 'user-api.yml']);
        $endpoints = $endpointBuilder->build();
        $this->builder = new RouterBuilder($endpoints);
        parent::setUp();
    }

    public function testBuild()
    {
        $router = $this->builder->build();

        $this->assertInstanceOf(Router::class, $router);
        $this->assertInstanceOf(RouteCollection::class, $router->getRoutes());
        $this->assertEquals(5, $router->getRoutes()->count());
    }
}