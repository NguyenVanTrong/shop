<?php
//先获得当前act的值,同时确定默认请求参数动作
//$act = isset($_GET['act'])?$_GET['act']:'login';
//$act = isset($_GET['act'])?$_GET['act']:isset($_POST['act'])?$_POST['act']:'login';
$act = isset($_REQUEST['act'])?$_REQUEST['act']:'login';
//引入公共文件
require 'includes/init.php';

//判断当前是什么act
if($act == 'login') {
    //显示登录表单
    include ADMIN_TEMPLATE_DIR . 'login.php';
}
//验证用户名和密码是否正确的功能
elseif ($act == 'signin') {
	//看验证码是否输入正确
//	$captcha = new captcha();
//	if(!$captcha->checkCaptcha($_POST['captcha'])){
//		admin_redirect('privilege.php?act=login','验证码错误');
//	}
    //判断用户信息
    //先获得填写表单数据
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    //拿着数据去数据库的管理员表验证
    $table_admin_user = new adminUserTable();
    //调用方法获得用户信息
    $result = $table_admin_user->getByUserAndPass($username, $password);
    //判断结果
    if($result) {
        //正确的用户
        //开启session机制, 在init.php内开启
        //在会话数据内，增加一个变量, 用来保存当前管理员信息
        $_SESSION['admin'] = $result;
////        $_SESSION['if_login'] = true;//设置一个登录状态标志$if_login

        //记录当前的登录信息
        $table_admin_user->setLoginInfo();
        
        //判断是否选择了保存用户登陆信息
        if(isset($_POST['remember'])){
        	//若选择了则将admin用户的登陆信息保存一个月
        	setcookie('admin_id',$result['admin_id'],time()+2*30*24*3600);
        }

        admin_redirect('index.php', '欢迎!');
    } else {
        admin_redirect('privilege.php?act=login', '用户名或密码错误，跳到登录页');
    }

    //如果成功，说明有这个用户，则让其登录进入后台
    //如果失败，说明用户信息错误，则提示错误然后返回登录页面
    echo '判断用户信息';
}
//处理退出
elseif($act == 'logout') {
    //删除session数据
    unset($_SESSION['admin']);
    //跳转到登录页面
    admin_redirect('privilege.php?act=login', '退出成功');
}
//生成验证码的操作
elseif ($act == 'captcha'){
	$captcha = new captcha();
	$captcha->generate(145, 20);
}
?>