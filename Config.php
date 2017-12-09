<?php
namespace Smallphp;

class Config extends \Smallphp\Spl\ArrayAccess {

	private static $path = '';

	public function __construct($path='./') {
		if (self::$path == '') {
			self::$path = $path;
		}
	}
	
	public function load($name) {
		$file = $name.'.php';
		if (is_file(self::$path.$file)) {
			return $this[$name] = include self::$path.$file;
		}
		throw new \Exception("Configure File {$file} is Not  Found");
	}
}