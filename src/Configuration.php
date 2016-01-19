<?php

namespace Radweb\EC2SSH;

class Configuration
{
    private $location;

    public function __construct($location)
    {
        $this->location = $location;
    }

    public function read()
    {
        if ( ! file_exists($this->location)) {
            return null;
        }

        $contents = json_decode(file_get_contents($this->location), true);

        if (json_last_error()) {
            return null;
        }

        return $contents;
    }

    public function write(array $contents)
    {
        file_put_contents($this->location, json_encode($contents));
    }
}