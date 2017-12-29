<?php
namespace Smallphp\Database\Adapter;

class Mysqli implements \Smallphp\Database\Adapter  {
	
	private $config = array();

	private static $identity = null;

	public function __construct($config) {
		$this->config = $config;
	}

	public function query($sql) {
		$this->connect();
	}

	public function lastInsertId() {
	
	}

	public function getErrorCode() {
	
	}

	public function getErrorInfo() {
	
	}
	
	private function connect() {
		if (self::$identity === NULL) {
			self::$identity = new \Mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['passwd']);
		}
	}
}