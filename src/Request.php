<?php

namespace Core;

class Request
{
    protected string $method;
    protected string $uri;
    protected array $data;

    public function __construct(string $uri,  string $method, array $data = [])
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->data = $data;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getData(): array
    {
        return $this->data;
    }


}