<?php
	//所有的商品分类相关的操作
	$act = isset($_REQUEST['act'])?$_REQUEST['act']:'list';
	
	//引入公共文件`
	require 'includes/init.php';
	
	if($act == 'list'){
		//先获得需要展示的数据
		$table_category = new categoryTable();
		//var_dump($table_category);
		$list = $table_category->getList();
		//var_dump($list);
		//再载入模板文件显示数据
		require ADMIN_TEMPLATE_DIR . 'category_list.php';
	}elseif ($act == 'add'){
		$table_category = new categoryTable();
		$category_list = $table_category->getList();
		require ADMIN_TEMPLATE_DIR . 'category_add.php';
	}elseif ($act == 'insert'){
		$data = array(
			'category_name' => $_POST['category_name'],
			'sort_order' => $_POST['sort_order'],
			'parent_id' => $_POST['parent_id']
		);
		$table_category = new categoryTable();
		if($table_category->insertCategory($data)){
			admin_redirect('category.php?act=list','添加成功');
		}else{
			admin_redirect('category.php?act=add','添加失败');
		}
	}elseif ($act == 'del'){
		$category_id = $_GET['id'];
		$table_category = new categoryTable();
		if($table_category->isLeaf($category_id)){//判断是否为叶子结点
			$table_category->delCategory($category_id);
			admin_redirect('category.php?act=list','删除成功');
		}else{
			admin_redirect('category.php?act=list','不是末级分级，请确认');
		}
	}
	//显示编辑数据表单动作
	elseif ($act == 'edit'){
		$category_id = $_GET['id'];
		$table_category = new categoryTable();
		$row = $table_category->getById($category_id);
		$category_list = $table_category->getList();
		//显示更新表单
		require ADMIN_TEMPLATE_DIR . 'category_edit.php';
	}	
	elseif ($act == 'update'){
		$data = array(
			'category_id' => $_POST['category_id'],
			'category_name' => $_POST['category_name'],
			'sort_order' => $_POST['sort_order'],
			'parent_id' => $_POST['parent_id']
		);
		$table_category = new categoryTable();
		if($table_category->updateCategory($data)){
			admin_redirect('category.php?act=list','编辑成功');
		}else{
			admin_redirect('category.php?act=edit&id='.$data['category_id'],'编辑失败');
		}
	}
?>