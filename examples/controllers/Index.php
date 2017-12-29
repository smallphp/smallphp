<?php
namespace App\Controller;

class Index extends \Smallphp\Mvc\Controller {
	
	public function index() {
	

















		$db = \Smallphp\Di::get('db');
		$db->select("select * FROM users");
	}
}