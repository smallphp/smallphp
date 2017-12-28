<?php
namespace Smallphp;

class Database {
	
	public function __construct($index='master') {
		$config = \Smallphp\Di::get('config')->load('database');
		if (isset($config[$index])) {
			$db = new $config[$index]['adapter']($config[$index]);
			$result = $db->select('fdf');
		}
	}
}