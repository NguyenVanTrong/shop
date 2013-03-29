<?php

/**
 * 自动加载函数
 * @param $class_name string 当前需要的类名
 */
function __autoload($class_name) {
    require_once INCLUDE_DIR . $class_name . '.class.php';
}

/**
 * 后台自动跳转的函数
 *
 * @param $url string 跳转到的地址
 * @param $msg string 跳转前的提示信息
 *
 */
function admin_redirect($url, $msg = '') {
    //实现思路，
    //需要引入一个后台的 templates 内的文件即可。
    include ADMIN_TEMPLATE_DIR . 'redirect.php';
    exit;//跳转 意味着当前脚本不需要再执行了 强制停掉。
}