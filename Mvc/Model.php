<?php
namespace Smallphp\Mvc;

abstract class Model {
	
	protected $db = NULL;

	public function __construct() {
		if ($this->db === NULL) {
			$this->db = new \Smallphp\Database();
		}
	}
}