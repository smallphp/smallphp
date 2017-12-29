<?php
namespace Smallphp;

class Database {

	private static $adapter = null;

	public function __construct() {
		$config = \Smallphp\Di::get('config')->load('database');
		if (!self::$adapter) {
			self::$adapter = new $config['adapter']($config);
		}
	}

	public function select($sql) {
		return self::$adapter->query($sql);
	}
}