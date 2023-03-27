<?php

namespace Tests\Config;

use Micronative\MockServer\Config\Endpoint;
use Micronative\MockServer\Config\Response;
use PHPUnit\Framework\TestCase;

class EndpointTest extends TestCase
{
    /**
     * @dataProvider getData
     * @param array $data
     * @return void
     */
    public function testEndpoint(array $data): void
    {
        $endpoint = new Endpoint($data['path'], $data['methods'], $data['response']);

        $this->assertEquals($data['path'], $endpoint->getPath());
        $this->assertEquals($data['methods'], $endpoint->getMethods());
        $this->assertEquals($data['response'], $endpoint->getResponse());
    }

    public function getData(): array
    {
        return [
            [
                [
                    'path' => '/product/{id}',
                    'methods' => ['GET', 'POST'],
                    'response' => new Response(200, 'json', '{ "id":"5e8e44a0-2552-4acc-9964-f5ca07948486", "payload":{ "error":"Resource Not Found" }, "created_at":"2022-09-05 03:37:54" }')
                ],
            ]
        ];
    }
}