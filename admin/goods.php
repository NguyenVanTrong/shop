<?php
	$act = isset($_REQUEST['act'])?$_REQUEST['act']:'list';
	
	//引入公共文件`
	require 'includes/init.php';
	
	$page = isset($_REQUEST['page'])?$_REQUEST['page']:1;
	$pagesize = $GLOBALS['config']['ADMIN']['goods_list_pagesize'];
	
	if($act == 'list'){
		//先获得需要展示的数据
		$table_goods = new goodsTable();
		//var_dump($table_category);
		$result = $table_goods->getList($page,$pagesize);
		
		$list  = $result['list'];
		$total = $result['total'];
		//$total_page = ceil($total/$pagesize);
		$page_object = new page();
		$page_html = $page_object->show($total, $page, $pagesize, 'goods.php', array('act' => 'list'));
		//var_dump($list);
		//再载入模板文件显示数据
		require ADMIN_TEMPLATE_DIR . 'goods_list.php';
	}
	
	//放入回收站
	elseif ($act == 'del'){
		$table_goods = new goodsTable();
		if($table_goods->pushTrash($_GET['id'])){
			admin_redirect('goods.php?act=list','成功放入回收站');
		}else{
			admin_redirect('goods.php?act=list','操作失败');
		}
	}
	
	//查看回收站里的内容
	elseif ($act == 'trash'){
		$page = isset($_REQUEST['page'])?$_REQUEST['page']:1;
		$pagesize = $GLOBALS['config']['ADMIN']['trash_list_pagesize'];
		$table_goods = new goodsTable();
		$result = $table_goods->getList($page, $pagesize,1);
		$list  = $result['list'];
		$total = $result['total'];
		//$total_page = ceil($total/$pagesize);
		$page_object = new page();
		$page_html = $page_object->show($total, $page, $pagesize, 'goods.php', array('act' => 'trash'));
		//再载入模板文件显示数据
		require ADMIN_TEMPLATE_DIR . 'goods_trash.php';
	}
	elseif ($act == 'restore'){
		$table_goods = new goodsTable();
		if($table_goods->pollTrash($_GET['id'])){
			admin_redirect('goods.php?act=list','成功众回收站还原');
		}else{
			admin_redirect('goods.php?act=list','还原操作失败');
		}
	}
	//彻底删除
	elseif ($act == 'delele'){
		$table_goods = new goodsTable();
		if($table_goods->delGoods($_GET['id'])){
			admin_redirect('goods.php?act=list','成功从回收站彻底删除');
		}else{
			admin_redirect('goods.php?act=list','操作失败');
		}
	}
	
	//显示添加商品的页面
	elseif ($act == 'add'){
		$table_category = new categoryTable();
		$category_list = $table_category->getList();
		require ADMIN_TEMPLATE_DIR . 'goods_add.php';
	}
	
	//处理插入商品的动作
	elseif ($act == 'insert'){
		//收集数据
		$data = array(
			'goods_name' => $_POST['goods_name'],
			'goods_sn' => $_POST['goods_sn'],
			'category_id' => $_POST['category_id'],
			'goods_price' => $_POST['goods_price'],
			'is_special' => isset($_POST['is_special'])?'1':'0',
			'special_price' => isset($_POST['special_price'])?$_POST['special_price']:'0',
			'goods_number' => $_POST['goods_number'],
			'is_on_sale' => isset($_POST['is_on_sale'])?'1':'0',
			'is_promote' => isset($_POST['is_promote'])?'1':'0',
			'is_best' => isset($_POST['is_best'])?'1':'0',
			'is_new' => isset($_POST['is_new'])?'1':'0',
			'is_hot' => isset($_POST['is_hot'])?'1':'0',
			'click_count' => $GLOBALS['config']['ADMIN']['goods_click_count_default'],
			'update_time' => time(),
			'goods_img' => '',
			'goods_desc' => '',
			'goods_thumb' => ''
		);
		//处理图片上传
		$image = new image();
		if($upload_filename = $image->upload($_FILES['goods_img'],'goods_')){
			//上传成功
			$data['goods_img'] = $upload_filename;
			//制作缩略图。
	        if($thumb_filename = $image->makeThumb(UPLOAD_DIR . $upload_filename, 100, 100, 'thumb_')) {
	            //成功
	            $data['goods_thumb'] = $thumb_filename;
	        }
			//为商品图片增加水印
	        if($water_filename = $image->addStamp(UPLOAD_DIR . $upload_filename)) {
	            $data['goods_img'] = $water_filename;
	        }
		}else{
			$img_error = $image->getErrorInfo();
		}
		$table_goods = new goodsTable();
		if($table_goods->insertGoods($data)){
			$mess = '商品' . $data['goods_name'] . '添加成功';
			if(isset($img_error)){
				$mess .= '<br>但是文件上传失败,原因是' . $img_error;
			}
			admin_redirect('goods.php?act=list',$mess);
		}else{
			admin_redirect('goods.php?act=add',"商品{$data['goods_name']}添加失败");
		}
	}
	//显示编辑数据表单动作
	elseif ($act == 'edit'){
		$goods_id = $_GET['id'];
		$table_goods = new goodsTable();
		$row = $table_goods->getById($goods_id);
		
		$table_category = new categoryTable();
		$category_list = $table_category->getList();
		//显示更新表单
		require ADMIN_TEMPLATE_DIR . 'goods_edit.php';
	}	
	//更新数据
	elseif ($act == 'update'){
		//收集数据
		$data = array(
			'goods_id' => $_POST['goods_id'],
			'goods_name' => $_POST['goods_name'],
			'goods_sn' => $_POST['goods_sn'],
			'category_id' => $_POST['category_id'],
			'goods_price' => $_POST['goods_price'],
			'is_special' => isset($_POST['is_special'])?'1':'0',
			'special_price' => isset($_POST['special_price'])?$_POST['special_price']:'0',
			'goods_number' => $_POST['goods_number'],
			'is_on_sale' => isset($_POST['is_on_sale'])?'1':'0',
			'is_promote' => isset($_POST['is_promote'])?'1':'0',
			'is_best' => isset($_POST['is_best'])?'1':'0',
			'is_new' => isset($_POST['is_new'])?'1':'0',
			'is_hot' => isset($_POST['is_hot'])?'1':'0',
			'click_count' => $GLOBALS['config']['ADMIN']['goods_click_count_default'],
			'update_time' => time(),
			'goods_img' => '',
			'goods_desc' => '',
			'goods_thumb' => ''
		);
		//处理图片上传
		$image = new image();
		if($upload_filename = $image->upload($_FILES['goods_img'],'goods_')){
			//上传成功
			$data['goods_img'] = $upload_filename;
			//制作缩略图。
	        if($thumb_filename = $image->makeThumb(UPLOAD_DIR . $upload_filename, 100, 100, 'thumb_')) {
	            //成功
	            $data['goods_thumb'] = $thumb_filename;
	        }
			//为商品图片增加水印
	        if($water_filename = $image->addStamp(UPLOAD_DIR . $upload_filename)) {
	            $data['goods_img'] = $water_filename;
	        }
		}else{
			$img_error = $image->getErrorInfo();
		}
		//执行修改操作
		$table_goods = new goodsTable();
		if($table_goods->updateGoods($data)){
			$mess = '商品' . $data['goods_name'] . '修改成功';
			if(isset($img_error)){
				$mess .= '<br>但是文件上传失败,原因是' . $img_error;
			}
			admin_redirect('goods.php?act=list',$mess);
		}else{
			admin_redirect('goods.php?act=eidt',"商品{$data['goods_name']}修改失败");
		}
	}
?>