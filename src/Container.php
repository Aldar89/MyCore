<?php

namespace Core;

class Container
{
    private array $services = [];
    public function get(string $class): object
    {
        if (!isset($this->services[$class])) {
            return new $class();
        }
        $collback = $this->services[$class] ;

        return $collback($this);
    }

    public function set(string $class, callable $callback): void
    {
        $this->services[$class] = $callback;
    }

}