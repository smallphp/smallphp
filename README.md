# smallphp
Smallphp Framework框架专注高效、敏捷开发项目

入口文件index.php  
define('APPPATH', dirname(__FILE__));  
include '/data/Smallphp/Loader.php';  
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
	\Smallphp\Request :: factory() -> execute(); 
} catch (\Exception $e) {    
	echo $e->getMessage();    
}    
