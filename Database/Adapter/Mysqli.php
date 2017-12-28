<?php
namespace Smallphp\Database\Adapter;

class Mysqli implements \Smallphp\Database\Adapter {

	public function __construct($config) {

	}

	public function select($sql) {
		return new \Smallphp\Database\Adapter\Mysqli\Result();
	}

	public function update($sql) {
		
	}

	public function insert($sql) {
		
	}

	public function delete($sql) {
		
	}
}