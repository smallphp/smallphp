<?php
namespace App\Controller;

class Index extends \Smallphp\Mvc\Controller {
	
	public function index() {
		$id = 15201817856;
		$view = \Smallphp\Di::get('view'); //view
		$model = new \App\Model\Test();	   //model
		$list = $model->limit(0, 10)->where(['id[>]'=>1])->order(['id'=>'desc', 'name'=>'asc'])->group(['name', 'id'])->getAll();
		$view->assign('list', $list);
		$view->render('index/index.php');
	}
}