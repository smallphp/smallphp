<?php

namespace Smallphp;

class Request
{
    private $url = '';

    private $protocol = 'http';

    private $params = array();

    private static $instance = null;

    public function __construct($url = '')
    {
        if ($url == '') {
            $this->url = \Smallphp\Mvc\Url:: getPathinfo();
        } else {
            $this->url = $url;
        }
    }

    public function method($method = 'GET')
    {
        return $this;
    }

    public function params($params = [])
    {
        if ($params) {
            $this->params = $params;
        } else {
            return $this->params;
        }
        return $this;
    }

    public function execute()
    {
        if (preg_match('~^' . $this->protocol . '~', $this->url)) {
        } else {
            $router = new \Smallphp\Mvc\Router();
            (new \Smallphp\Dispatch($router->execute($this->url)))->execute($this);
        }
    }

    public static function factory($url = '')
    {
        if (self:: $instance === null) {
            self:: $instance = new self($url = '');
        }
        return self:: $instance;
    }
} 
