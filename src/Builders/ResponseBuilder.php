<?php

namespace Micronative\MockServer\Builders;

use Micronative\MockServer\Config\Response;

class ResponseBuilder implements BuilderInterface
{
    private array $responses;

    /**
     * @param array $responses
     */
    public function __construct(array $responses)
    {
        $this->responses = $responses;
    }

    /**
     * @return Response[]
     */
    public function build(): array
    {
        $responses = [];
        foreach ($this->responses as $code => $response) {
            $responses[] = new Response((int)$code, $response['format'], $response['content']);
        }

        return $responses;
    }
}