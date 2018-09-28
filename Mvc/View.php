<?php

namespace Smallphp\Mvc;

class View
{
    /**
     * variable
     */
    private static $variables = [];

    /**
     * directory
     */
    private static $directory = '';

    /**
     * construct
     */
    public function __construct($directory = '')
    {
        if ($directory) {
            self:: $directory = $directory;
        }
    }

    /**
     * assign
     */
    public function assign($key, $val)
    {
        self:: $variables [$key] = $val;
    }

    /**
     * render
     */
    public function render($file)
    {
        $file = self:: $directory . $file;
        if (!is_file($file)) {
            throw new \Exception("the view file {$file} is not found");
        }
        extract(self:: $variables);
        include $file;
    }
} 
