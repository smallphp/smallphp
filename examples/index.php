<?php
define('APPPATH', dirname(__FILE__));  
include '/data/www/wwwroot/vendor/autoload.php';
$loader = new \Smallphp\Loader();   
$loader->registerNamespace([
	'App\Library'=>APPPATH.'/library',
	'App\Model'=>APPPATH.'/models',
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

\Smallphp\Di :: set('db', function() {
		return new \Smallphp\Database();
	} 
);

try {  
	\Smallphp\Request :: factory() -> execute(); 
} catch (\Exception $e) {    
	echo $e->getMessage();    
}