<?php

namespace Micronative\MockServer\Config;

class Response
{
    private int $code;
    private string $format;
    private string $content;

    /**
     * @param int|null $code
     * @param string|null $format
     * @param string|null $content
     */
    public function __construct(?int $code = null, ?string $format = null, ?string $content = null)
    {
        $this->code = $code;
        $this->format = $format;
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @return Response
     */
    public function setCode(int $code): Response
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return Response
     */
    public function setFormat(string $format): Response
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Response
     */
    public function setContent(string $content): Response
    {
        $this->content = $content;
        return $this;
    }
}
