<?php

namespace Smallphp\Cache\Adapter;

class Memcache extends \Memcache
{

    private static $object = NULL;

    public static function factory()
    {
        if (self::$object === NULL) {
            self::$object = new self();
        }
        return self::$object;
    }

    public function __construct()
    {
        self::$object = $this;
        parent::connect('127.0.0.1', '11211');
    }
}