<?php

namespace App\Services;

class Service
{
    public static function instance()
    {
        return new static;
    }

    public function setAttribute($name, $value)
    {
        $this->$name = $value;
        return $this;
    }
}
