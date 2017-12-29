<?php
namespace App\Controller;

class Index extends \Smallphp\Mvc\Controller {
	
	public function index() {

		$db = \Smallphp\Di::get('db');
		$r = $db->select("select * FROM test")->fetchAll();
		var_dump($r);
	}
}