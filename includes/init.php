<?php
header('Content-Type: text/html; charset=utf-8');

//定义公共的常量

//目录常量
//利用__DIR__获得网站根目录
//C:\wamp\www\shop\admin\includes
//变成 斜杠需要变（windows支持 / \ linux 支持 /)
//需要 将 admin/includes 去掉
//C:/amp/apache/htdocs/shop/
define('ROOT_DIR', str_replace('includes', '', str_replace('\\', '/', __DIR__)));
define('CONFIG_DIR', ROOT_DIR . 'config/');
define('UPLOAD_DIR', ROOT_DIR . 'upload/');
//注意 如果php版本低于5.3 没有__DIR__这个常量，可以使用__FILE__来替代使用
//define('ROOT_DIR', str_replace('admin/includes/init.php', '', str_replace('\\', '/', __FILE__)));
define('INCLUDE_DIR', ROOT_DIR . 'includes/');
define('TEMPLATE_DIR', ROOT_DIR . 'templates/');
//define('ADMIN_DIR', ROOT_DIR . 'admin/');
//define('ADMIN_INCLUDE_DIR', ADMIN_DIR . 'includes/');
//define('ADMIN_TEMPLATE_DIR', ADMIN_DIR . 'templates/');


//加载配置文件
//$config = require CONFIG_DIR . 'config.php';
//保证config的全局性，强制声明为全局的：
$GLOBALS['config'] = require CONFIG_DIR . 'config.php';

//引入公共的函数库文件
require INCLUDE_DIR . 'functions.php';

//设置环境
//错误信息
ini_set('error_reporting', E_ALL & ~E_NOTICE);
//ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
ini_set('display_errors', '1');//1为显示 0为不显示错误

new sessionsTable();

?>