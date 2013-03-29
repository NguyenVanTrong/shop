<?php
//先获得当前act的值,同时确定默认请求参数动作
$act = isset($_REQUEST['act'])?$_REQUEST['act']:'index';

//加载初始化 文件
require 'includes/init.php';

//判断当前的act 执行 不同的功能
if($act == 'index') {
    //引入后台首页主体代码
    include ADMIN_TEMPLATE_DIR . 'index.php';
}
//top部分
elseif($act == 'top') {
    include ADMIN_TEMPLATE_DIR . 'top.php';
}
//menu部分
elseif($act == 'menu') {
    include ADMIN_TEMPLATE_DIR . 'menu.php';
}
//drag部分
elseif($act == 'drag') {
    include ADMIN_TEMPLATE_DIR . 'drag.php';
}
//menu部分
elseif($act == 'main') {
    include ADMIN_TEMPLATE_DIR . 'main.php';
}
?>