<?php

namespace Smallphp;

class Dispatch
{
    /**
     * class name
     */
    private $class = '';

    /**
     * action name
     */
    private $action = '';

    /**
     * params
     */
    private $params = array();

    /**
     * construct
     * +---------
     */
    public function __construct($params)
    {
        // namespace
        $namespace = isset($params['namespace']) ? $params['namespace'] : 'App\Controller\\';
        \Smallphp\Di:: get('loader')->registerNamespace(array($namespace => APPPATH . '/controllers/',
        ));
        $this-> class = $namespace;
		// directory
		if (isset($params['directory'])) {
            $this-> class .= ucfirst($params['directory']) . '\\';
		} 
		// controler
		$this-> class .= ucfirst($params['controller']);
		if (isset($params['action'])) {
            $this->action = $params['action'];
        }
		$this->params = $params;
	}

    /**
     * execute
     * +------
     */
    public function execute(\Smallphp\Request &$request)
    {
        if ($this-> class && $this->action) {
        $reflectionClass = new \ReflectionClass($this-> class);
			if (!$reflectionClass->isSubclassOf('\Smallphp\Mvc\Controller')) {
                throw new \Exception('No inherited controller');
            }
			$request->params($this->params);
			$class = new $this-> class($request);
			if (method_exists($class, $this->action)) {
                $method = new \ReflectionMethod($class, $this->action);
                if ($method->isPublic()) {
                    return $method->invoke($class);
                }
            }
			throw new \Exception("Method '" . $this->action . "' No Exists");
		} 
		throw new \Exception('Router Failed');
	}
} 
