<?php

namespace Smallphp\Mvc;

abstract class Controller
{
    /**
     * request
     */
    protected $request;

    public function __construct(\Smallphp\Request $request)
    {
        $this->request = $request;
    }
} 
