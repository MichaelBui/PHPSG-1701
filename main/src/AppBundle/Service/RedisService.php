<?php

namespace AppBundle\Service;

class RedisService extends \Redis
{
    public function __construct(string $host, int $port)
    {
        $this->pconnect($host, $port);
    }
}