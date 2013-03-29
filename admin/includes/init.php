<?php
//初始化 后台的 所有的功能
header('Content-Type: text/html; charset=utf-8');

//定义公共的常量

//目录常量
//利用__DIR__获得网站根目录
//C:\wamp\www\shop\admin\includes
//变成 斜杠需要变（windows支持 / \ linux 支持 /)
//需要 将 admin/includes 去掉
//e:/amp/apache/htdocs/shop/
define('ROOT_DIR', str_replace('admin/includes', '', str_replace('\\', '/', __DIR__)));
//注意 如果php版本低于5.3 没有__DIR__这个常量，可以使用__FILE__来替代使用
//define('ROOT_DIR', str_replace('admin/includes/init.php', '', str_replace('\\', '/', __FILE__)));
define('ADMIN_DIR', ROOT_DIR . 'admin/');
define('INCLUDE_DIR', ROOT_DIR . 'includes/');
define('ADMIN_INCLUDE_DIR', ADMIN_DIR . 'includes/');
define('ADMIN_TEMPLATE_DIR', ADMIN_DIR . 'templates/');
define('CONFIG_DIR', ROOT_DIR . 'config/');
define('UPLOAD_DIR', ROOT_DIR . 'upload/');

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


//开启session
//session_start();
new sessionsTable();


$script_name = basename($_SERVER['SCRIPT_NAME']);

if($script_name == 'privilege.php' && ($act == 'captcha' || $act == 'login' || $act == 'signin')) {
    
	//当前的请求不需要
    //do nothing....
}else{
	//先判断当前用户是否有能力 查看这个页面
	//判断 登录标志来获得
	//为了使用 session数据，先开启， 在init.php内开启
	if(isset($_SESSION['admin'])) {//直接判断 $_SESSION内的标志即可
		//已经登录
	}
	elseif (isset($_COOKIE['admin_id'])) {
		//说明选择了保存登陆信息
		$table_admin_user = new adminUserTable();
		$admin = $table_admin_user->getById($_COOKIE['admin_id']);
		$_SESSION['admin'] = $admin;
		$table_admin_user->setLoginInfo();
	}
	else {
		//没有登录
		admin_redirect('privilege.php?act=login', '请先登录！');
	}
}

?>