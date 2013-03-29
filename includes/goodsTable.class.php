<?php



class goodsTable extends mysqlDB {

    //表名属性 一个没有前缀的表名
    protected $table_name = 'goods';
    //表的字段列表
    protected $fields;
    
	//取得商品分类列表数据
	public function getList($page,$pagesize,$is_delete=0){
		$whereStr = " where is_delete = {$is_delete} ";
		$offset = ($page - 1) * $pagesize;//偏移量
		$query = "select * from {$this->getTableName()} {$whereStr}  order by goods_id asc limit {$offset},{$pagesize}";
		$list = $this->getAll($query);
		
		$query = "select count(*) as c from {$this->getTableName()} {$whereStr} ";
		$row = $this->getRow($query);
		$total = $row['c'];
		
		return array('list'=>$list,'total'=>$total);
	}
	
	/**
	 * 获取精品信息
	 * @param unknown_type $index_best_goods_num
	 * @return array
	 */
	public function getBest($index_best_goods_num){
		$query = "select * from {$this->getTableName()} where is_best = 1 limit {$index_best_goods_num}";
		return $this->getAll($query);
	}
	 
	/**
	 *
	 * 插入一条记录
	 * @param array() $data
	 */
	public function insertGoods($data){
		//$query = "insert into {$this->getTableName()} values (null,'{$data['category_name']}','{$data['sort_order']}','{$data['parent_id']}')";
		return $this->autoInsert($data);
	}
	 
	/**
	 * 删除分类
	 * @param int $goods_id
	 * return bool,删除的结果，成功或失败
	 */
	public function delGoods($goods_id){
		$query = "delete from {$this->getTableName()} where goods_id = {$goods_id}";
		return $this->query($query);
	}
	 
	/**
     * 利用用户id 找到商品信息
     * @param goods_id 商品id
     * @return mixed 找到时返回商品信息；找不到 返回false。
     */
    public function getById($goods_id){
    	$query = "select * from {$this->getTableName()} where goods_id = '{$goods_id}'";
        return $this->getRow($query);
    }
	/**
	 *
	 * 更新记录
	 * @param array() $data
	 * @return bool
	 */
	public function updateGoods($data){
		return $this->autoUpdate($data);
	}
	
	/**
	 * 
	 * 将商品放入回收站
	 * @param int $goods_id
	 * @return bool
	 */
	public function pushTrash($goods_id){
		$query = "update {$this->getTableName()} set is_delete = 1 where goods_id = {$goods_id}";
		return $this->query($query);
	}

	/**
	 * 
	 * 将商品从回收站还原
	 * @param int $goods_id
	 * @return bool
	 */
	public function pollTrash($goods_id){
		$query = "update {$this->getTableName()} set is_delete = 0 where goods_id = {$goods_id}";
		return $this->query($query);
	}

}