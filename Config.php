<?php

namespace Smallphp;

class Config extends \Smallphp\Spl\ArrayAccess
{

    private static $path = '';

    /**
     * 配置文件存放目录
     */
    public function __construct($path = './')
    {
        if (self::$path == '') {
            self::$path = $path;
        }
    }

    /**
     * 载入指定配置文件
     */
    public function load($name)
    {
        $file = $name . '.php';
        if (is_file(self::$path . $file)) {
            $return = include self::$path . $file;
            if (!is_array($return)) {
                $return = array();
            }
            return $this[$name] = $return;
        }
        throw new \Exception("Configure File {$file} is Not  Found");
    }
}