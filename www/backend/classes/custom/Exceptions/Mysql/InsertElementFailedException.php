<?php

namespace Custom\Exceptions\Mysql;

class InsertElementFailedException extends \Exception
{
    private string $parameters;

    function __construct($id)
    {
        $this->parameters = $id;
    }

    public function getParameters()
    {
        return $this->parameters;
    }
}