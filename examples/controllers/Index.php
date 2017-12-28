<?php
namespace App\Controller;

class Index extends \Smallphp\Mvc\Controller {
	
	public function index($index='master') {
		$db = \Smallphp\Di::get('db');
	}
}