<?php

namespace AppBundle\Service;

class LogService
{
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function read(int $lines)
    {
        $file = escapeshellarg($this->path);
        return `tail -n $lines $file`;
    }

    public function logSubmitted(string $name)
    {
        $file = fopen($this->path, 'a+');
        fwrite($file, sprintf('Form *%s* has been submitted at %s' . PHP_EOL, $name, microtime(true)));
        fclose($file);
    }

    public function logProcessed(string $name)
    {
        $file = fopen($this->path, 'a+');
        fwrite($file, sprintf('Form *%s* has been processed at %s !!!' . PHP_EOL, $name, microtime(true)));
        fclose($file);
    }
}