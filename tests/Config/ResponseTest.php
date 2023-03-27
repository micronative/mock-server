<?php

namespace Tests\Config;

use Micronative\MockServer\Config\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    /**
     * @dataProvider getData
     * @param array $data
     * @return void
     */
    public function testEndpoint(array $data): void
    {
        $response = new Response($data['code'], $data['format'], $data['content']);

        $this->assertEquals($data['code'], $response->getCode());
        $this->assertEquals($data['format'], $response->getFormat());
        $this->assertEquals($data['content'], $response->getContent());
    }

    public function getData(): array
    {
        return [
            [
                [
                    'code' => 200,
                    'format' => 'json',
                    'content' => '{ "id":"5e8e44a0-2552-4acc-9964-f5ca07948486", "payload":{ "error":"Resource Not Found" }, "created_at":"2022-09-05 03:37:54" }'
                ],
            ]
        ];
    }
}