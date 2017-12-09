<?php
namespace Smallphp;

class Database {

	public function __construct($config) {
		$driver = $config['class'];
		$object = new $driver();
	}
}