<?php
namespace Smallphp;

class Loader {
	/**
	 * 命名空间
	 * 
	 * @var array 
	 */
	private $namespaces = array();

	public function __construct() {
		// default namespace
		$this -> namespaces[__NAMESPACE__ . '\\'] = dirname(__FILE__) . DIRECTORY_SEPARATOR;
	} 

	/**
	 * 注册自动载入机制
	 * +----------------
	 * 
	 * @param void $ 
	 * @return bool 
	 */
	public function registerAutoload() {
		return spl_autoload_register(array(&$this, 'loadfile'));
	} 

	/**
	 * 注册命名空间前缀
	 * +------------------------
	 * 
	 * @param array $namespaces 
	 * @return self 
	 */
	public function registerNamespace($namespaces) {
		if (is_array($namespaces)) {
			foreach ($namespaces as $prefix => $folder) {
				$prefix = rtrim($prefix, '\\') . '\\';
				$folder = rtrim($folder, '/') . DIRECTORY_SEPARATOR;
				if (!isset($this -> namespaces[$prefix])) {
					$this -> namespaces[$prefix] = $folder;
				} 
			} 
		} 
		return $this;
	} 

	/**
	 * 载入类及文件
	 * +--------------------
	 * 
	 * @param string $class 
	 * @return void 
	 */
	private function loadfile($class) {
		$prefix = $class;
		while (false !== ($pos = strrpos($prefix, '\\'))) {
			$prefix = substr($class, 0, $pos + 1);
			$suffix = substr($class, $pos + 1);
			if (isset($this -> namespaces[$prefix])) {
				$file = $this -> namespaces[$prefix] . str_replace('\\', DIRECTORY_SEPARATOR, $suffix) . '.php';
				if (is_file($file) && is_readable($file)) include $file;
			} 
			$prefix = rtrim($prefix, '\\');
		} 
	} 
} 
