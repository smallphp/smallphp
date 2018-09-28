# Smallphp Framework
框架目录
```
├── Cache
│   └── Adapter
│       ├── Memcache.php
│       └── Redis.php
├── composer.json
├── Config.php
├── Database
│   ├── Adapter
│   │   ├── Mysqli
│   │   │   └── Result.php
│   │   └── Mysqli.php
│   └── Adapter.php
├── Database.php
├── Di.php
├── Dispatch.php
├── examples
│   ├── config
│   │   ├── database.php
│   │   └── router.php
│   ├── controllers
│   │   └── Index.php
│   ├── index.php
│   ├── models
│   │   ├── Test.php
│   │   └── User.php
│   └── views
│       └── index
│           └── index.php
├── Loader.php
├── Mvc
│   ├── Controller.php
│   ├── Model.php
│   ├── Router.php
│   ├── Url.php
│   └── View.php
├── Nosql
│   ├── Mongodb
│   │   └── Query.php
│   └── Mongodb.php
├── README.md
├── Request.php
├── Session
│   ├── Adapter
│   │   ├── File.php
│   │   ├── Memcache.php
│   │   └── Redis.php
│   └── Adapter.php
├── Session.php
└── Spl
    └── ArrayAccess.php
```
入口文件examples/index.php
```
<?php
define('APPPATH', dirname(__FILE__));
include '/data/Smallphp/Loader.php';
$loader = new \Smallphp\Loader();
$loader->registerNamespace(['App\Library' => APPPATH . '/library']);

$loader->registerAutoload();

\Smallphp\Di:: set('view', function () {
    return new \Smallphp\Mvc\View(APPPATH . '/views/');
});

\Smallphp\Di:: set('loader', $loader);

\Smallphp\Di:: set('mongodb', function () {
    return new \Smallphp\Nosql\Mongodb();
});

\Smallphp\Di:: set('config', function () {
    return new \Smallphp\Config(APPPATH . '/config/');
});

try {
    \Smallphp\Request:: factory()->execute();
} catch (\Exception $e) {
    echo $e->getMessage();
}
```