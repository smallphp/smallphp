<?php
namespace App\Controller;

class Index extends \Smallphp\Mvc\Controller {
	
	public function index() {
		$view = \Smallphp\Di::get('view'); //view
		$model = new \App\Model\Test();	   //model
		echo '<pre>';
		print_r($model->limit(0, 5)->getAll());
		print_r($model->getCount());
		$view->assign('title', 'Hello World');
		$view->render('index/index.php');
	}
}