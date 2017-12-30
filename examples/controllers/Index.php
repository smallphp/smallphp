<?php
namespace App\Controller;

class Index extends \Smallphp\Mvc\Controller {
	
	public function index() {
		$view = \Smallphp\Di::get('view');
		$view->assign('title', 'Hello World');
		$view->render('index/index.php');
	}
}