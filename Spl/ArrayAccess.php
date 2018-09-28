<?php

namespace Smallphp\Spl;

class ArrayAccess implements \ArrayAccess
{

    private $container = array();

    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        if (isset($this->container[$offset])) {
            return $this->container[$offset];
        }
        return NULL;
    }

    public function offsetSet($offset, $value)
    {
        $this->container[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if (isset($this->container[$offset])) {
            unset($this->container[$offset]);
        }
    }
}