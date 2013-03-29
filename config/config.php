<?php
//配置文件
return array(
    //将所以数据库的配置，全部写到DB的元素呢
    'DB' => array(
        'host' => 'localhost',
        'port' => '3306',
        'user' => 'root',
        'pass' => '',
        'dbname' => 'shop',
        'prefix' => 'cz_',
        'charset' => 'utf8'
    ),
    'APP' => array(),
    'ADMIN' => array(
    	'goods_list_pagesize' => 5,
    	'trash_list_pagesize' => 5,
    	'goods_click_count_default' => 100,
        'upload_allow_types' => 'image/jpeg,image/png,image/gif',
        'upload_max_size' => 1000000,//1M
   		'water_src_filename' => UPLOAD_DIR . 'water.jpg'
    ),
    'HOME' => array(
    	'index_best_goods_num' => 3
    )
);