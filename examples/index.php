<?php
define('APPPATH', dirname(__FILE__));  
include '../Loader.php';  
$loader = new \Smallphp\Loader();   
$loader->registerNamespace([
	'App\Library'=>APPPATH.'/library',
]);
$loader -> registerAutoload();  

\Smallphp\Di :: set('view', function() {
		return new \Smallphp\Mvc\View(APPPATH . '/views/');
	} 
);  
\Smallphp\Di :: set('loader', $loader);  
\Smallphp\Di :: set('mongodb', function() {
		return new \Smallphp\Nosql\Mongodb();
	} 
);  
\Smallphp\Di :: set('config', function() {
		return new \Smallphp\Config(APPPATH . '/config/');
	} 
);  

try {  
	Smallphp\Request :: factory() -> method('POST') -> params(['name' => 'zhangjie']) -> execute(); 
  }   catch (\Exception $e) {    
	echo $e->getMessage();    
}