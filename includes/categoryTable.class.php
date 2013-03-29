<?php
class categoryTable extends mysqlDB {
	//表名属性 一个没有前缀的表名
	protected $table_name = 'category';
	//表的字段列表
	protected $fields;
	 
	//取得商品分类列表数据
	public function getList(){
		$query = "select * from {$this->getTableName()} order by sort_order asc";
		$rows = $this->getAll($query);
		return $this->getTree($rows, 0, 0);
	}
	 
	//完成在所有数据内按级别关系进行查找的 方法
	public function getTree($arr,$parent_id,$level){
		static $tree = array();
		foreach ($arr as $row){
			if($row['parent_id'] == $parent_id){
				$row['level'] = $level;
				$tree[] = $row;
				$this->getTree($arr, $row['category_id'],$level+1);
			}
		}
		return $tree;
	}
	 
	/**
	 *
	 * 插入一条记录
	 * @param array() $data
	 */
	public function insertCategory($data){
		$query = "insert into {$this->getTableName()} values (null,'{$data['category_name']}','{$data['sort_order']}','{$data['parent_id']}')";
		return $this->query($query);
	}
	 
	/**
	 * 删除分类
	 * @param int $category_id
	 * return bool,删除的结果，成功或失败
	 */
	public function delCategory($category_id){
		$query = "delete from {$this->getTableName()} where category_id = {$category_id}";
		return $this->query($query);
	}
	 
	/**
	 * 判断是否是末级分类
	 * 只要判断当前分类下是否有子分类即可
	 * @param $category_id int
	 * @return bool
	 */
	public function isLeaf($category_id){
		$query = "select * from {$this->getTableName()} where parent_id = {$category_id}";
		//判断是否能找到某些元素
		if($this->getRow($query)){
			//能够找到，非叶子
			return false;
		}else {
			//本结点是叶子结点
			return true;
		}
	}
	 
	/**
	 *
	 * 根据主键取得一条记录
	 * @param int $category_id
	 * @return array() 一维数组
	 */
	public function getById($category_id){
		$query = "select * from {$this->getTableName()} where category_id = {$category_id}";
		return $this->getRow($query);
	}
	/**
	 *
	 * 更新记录
	 * @param array() $data
	 * @return bool
	 */
	public function updateCategory($data){
		$query = "update {$this->getTableName()} set category_name = '{$data['category_name']}',sort_order = '{$data['sort_order']}',parent_id = '{$data['parent_id']}' where category_id = '{$data['category_id']}'";
		return $this->query($query);
	}
}
?>