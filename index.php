<?php
	$act = isset($_REQUEST['act'])?$_REQUEST['act']:'index';
	//加载初始化文件
	require 'includes/init.php';
	
	$index_best_goods_num = isset($GLOBALS['config']['HOME']['index_best_goods_num'])?$GLOBALS['config']['HOME']['index_best_goods_num']:3;
	
	if($act == 'index'){
		$table_goods = new goodsTable();
		$best_list = $table_goods->getBest($index_best_goods_num);
		include TEMPLATE_DIR . 'index.php';
	}
?>