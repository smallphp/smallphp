<?php
namespace App\Controller;

class Index extends \Smallphp\Mvc\Controller {
	
	public function index() {
		$view = \Smallphp\Di::get('view'); //view
		$model1 = new \App\Model\Test();	   //model
		$model2 = new \App\Model\User();	   //model
		echo '<pre>';
		print_r($model1);
		print_r($model2);
		$view->assign('title', 'Hello World');
		$view->render('index/index.php');
	}
}