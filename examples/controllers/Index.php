<?php
namespace App\Controller;

class Index extends \Smallphp\Mvc\Controller {
	
	public function index() {
		$db = \Smallphp\Di::get('db');
		$db->insert('insert into test(name)values("z")');
		echo $db->lastInsertId();
	}
}