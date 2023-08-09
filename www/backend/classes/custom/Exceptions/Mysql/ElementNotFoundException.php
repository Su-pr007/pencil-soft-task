<?php

namespace Custom\Exceptions\Mysql;

class ElementNotFoundException extends \Exception
{
    private string $elementId;

    function __construct($id)
    {
        $this->elementId = $id;
    }

    public function getElementId()
    {
        return $this->elementId;
    }
}