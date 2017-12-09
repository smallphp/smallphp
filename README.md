# smallphp
Smallphp Framework框架专注敏捷高效开发

框架入库index.pohp
define('APPPATH', dirname(__FILE__));
include '/data/Smallphp/Loader.php';
$loader = new \Smallphp\Loader();
$loader->registerNamespace([
	'App\Library'=>APPPATH.'/library',
]);
$loader -> registerAutoload();
define('ISMOBILE', \App\Library\Common::ismobile());
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
} catch (\Exception $e) {
	echo $e->getMessage();
}
