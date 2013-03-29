<?php
class mysqlDB {
	
    protected $host;//主机地址
    protected $port;//端口号
    protected $user;//用户名
    protected $pass;//密码
    protected $dbname;//数据库名
    protected $charset;//字符集
    protected $prefix;//表前缀

    protected $link;//当前的连接资源

    /**
     * 构造方法，用于完成初始化属性
     * 连接数据库服务器 并 选择数据库
     *
     * @param $attrs array 连接属性
     */
    public function __construct($attrs = array()) {
        //通过判断 参数是否为空，来决定是否自己载入使用配置文件内的配置信息
        if(empty($attrs)) {
            $attrs = $GLOBALS['config']['DB'];
        }
        //设置属性，在设置时，如果用户没正确设置，则使用默认值
        $this->host = isset($attrs['host'])?$attrs['host']:'localhost';
        $this->port = isset($attrs['port'])?$attrs['port']:'3306';
        $this->user = isset($attrs['user'])?$attrs['user']:'root';
        $this->pass = isset($attrs['pass'])?$attrs['pass']:'';
        $this->charset = isset($attrs['charset'])?$attrs['charset']:'utf8';
        $this->dbname = isset($attrs['dbname'])?$attrs['dbname']:'shop';
        $this->prefix = isset($attrs['prefix'])?$attrs['prefix']:'';

        //调用连接数据库的方法完成连接
        $this->connect();
        //选择数据库
        $this->selectDB();
        //设定字符集
        $this->setCharset();
        //获得当前表结构
        $this->getFields();

    }

    /**
     *连接数据库的方法
     */
    protected function connect() {
        $this->link = mysql_connect("{$this->host}:{$this->port}", $this->user, $this->pass);
        if(!$this->link) {
            echo 'connect failed!数据库连接失败!请确定主机，用户名，密码的信息!';
        }
    }
    /**
     * 选择数据库的方法
     */
    protected function selectDB() {
        $this->query("use {$this->dbname}");
    }

    /**
     *设定字符集
     */
    protected function setCharSet() {
        $this->query("set names {$this->charset}");
    }
    
    /**
     * 获得当前的表结构
     */
    protected function getFields(){
    	$query = "desc {$this->getTableName()}";
    	//$rows是一个二维数组，里面的每个元素(一维数组)的值是一个字段信息
    	$rows = $this->getAll($query);
    	//遍历desc的结果，每一条记录都是一个字段信息
    	foreach ($rows as $row){
    		//将字段的名称保存在属性fields内
    		$this->fields[] = $row['Field'];
    		//判断当前是否为主键
    		if($row['Key'] == 'PRI'){
    			//记录下主键字段的名称
    			$pk = $row['Field'];
    		}
    	}
    	//在fields属性内增加一个_pk元素表示主键
    	$this->fields['_pk'] = $pk;
    }

    /**
     *定义一个执行sql的方法
     *
     * @param $query, string 需要执行的sql语句
     * @return 返回正确的执行结果
     */
    protected function query($query) {
        //执行 如果有错误，exit 并输出错误信息
        $result = mysql_query($query, $this->link);
        if(!$result) {
            //执行失败
            exit(mysql_errno() . '-'. mysql_error());
        }

        return $result;
    }


    /**
     * 根据sql语句获得符合条件的全部数据
     * @param $query string ,一条select语句
     * @return array, 所有结果形成的2维数组
     */
    public function getAll($query) {
        $result = $this->query($query);

        $rows = array();
        while($row = mysql_fetch_assoc($result)) {
            $rows[] = $row;
        }
        //释放掉 用完的结果集资源
        mysql_free_result($result);

        return $rows;
    }

    /**
     * 只需获得符合条件的第一条记录
     * @param $query ,string 一条select语句
     * @return array, 一维数组。
     */
    public function getRow($query) {
        //优化此处的$query,自动增加limit
        if(strpos($query, 'limit') === false) {
            //没有 limit 增加
            $query .= " limit 1";//select goods_id from goods limit 1;
        }

        $result = $this->query($query);

        $row = mysql_fetch_assoc($result);
        //释放掉 用完的结果集资源
        mysql_free_result($result);
        return $row;
    }
    
    /**
     * 获得当前真实表名的方法
     * @return string 真实的表名
     */
    public function getTableName(){
    	return $this->prefix . $this->table_name;
    }
    
    /**
     * 自动插入的方法
     * 先拼凑SQL语句
     * 再执行Insert即可
     * @param array $data
     * @return bool 
     */
    public function autoInsert($data){
    	//先清理掉 无效的数据（字段名错误的数据）
        foreach($data as $key=>$value) {
            //判断当前的字段名是否存在于字段数组内
            if(!in_array($key, $this->fields)) {
                //如果不存在 则直接 删除这个元素即可
                unset($data[$key]);
            }
        }
        //开始部分
        $query = "insert into {$this->getTableName()}";
        //形成字段部分
        //先获得所有的元素的键（字段）
        $fields = array_keys($data);
        //再利用 逗号连接 即可
        $fields_str = implode(', ', $fields);
        //拼到query上
        $query .= "({$fields_str}) ";

        //拼凑 值 的部分
        //先将所有的值 使用单引号引起来
    	function add_marks($item) {
            return "'" . $item . "'";
        }
        $values = array_map("add_marks", $data);//对数组内的每个元素使用某个函数，函数的功能是给元素值增加引号
        //使用 逗号连接
        $values_str = implode(', ', $values);
        //拼到 $query 上
        $query .= " values ({$values_str}) ";

        //执行
        return $this->query($query);
    }
    
    /**
     *封装一个 自动 更新的方法
     * @param $data array 需要更新的字段和值的关联数组
     * @param $where_str string 更新条件 默认没有条件
     * @return bool 更新成功为真 失败为假
     */
    public function autoUpdate($data, $where_str='') {

        //语句开始
        $query = "update {$this->getTableName()} set ";
        //拼凑字段了

        //清理字段
        foreach($data as $key=>$value) {
            if(in_array($key, $this->fields)) {
                //正确的字段
                //将符合条件的字段 先 组成 key='value'的形式放入一个数组
                $fields[] = "{$key}='{$value}'";
            } else {
                unset($data[$key]);
            }
        }
        //拼凑字段部分
        $fields_str = implode(', ', $fields);
        $query .= $fields_str;

        //拼凑条件部分
		//"where category_id='1'"
        if($where_str == '') {
            //没有传递
            //判断主键
            $_pk = $this->fields['_pk'];
            if(array_key_exists($_pk, $data)) {
                //主键存在，使用主键作为条件
                $where_str = " where {$_pk}='{$data[$_pk]}' ";
            } else {
                //主键不存在 不予更新
                return false;
            }
        } else {
            //传递
            $where_str = " where {$where_str} ";
        }
        $query .= $where_str;

        return $this->query($query);

    }

    /**
     * 自动删除 根据传递过来的主键的值 完成删除
     * 拼凑 delete语句的过程
     * @param $pks mixed array 主键的集合array(1,23); string int 主键为某个值 4 '4,5,7'
     * @return bool删除的结果
     */
    public function autoDelete($pks) {
        if(is_array($pks)) {
            //是 in
            function add_marks($item) { 
            	return "'" . $item . "'";
            }
            $quote_pks = array_map("add_marks", $pks);
            $cond = " in (" . implode(', ', $quote_pks) . ")";
        } else {
            //不是 =
            $cond = "='{$pks}'";
        }

        $where_str = " where {$this->fields['_pk']} {$cond}";

        $query = "delete from {$this->getTableName()} {$where_str}";

        return $this->query($query);
    }


    /**
     *析构函数，用户释放连接资源
     */
    public function __destruct() {
        //此处不应该注销这个连接
        //因为 页面结束时 是先释放变量
        //才会将处理存储session数据
        //意味着 会 先释放掉new sessionsTable()所实例化的对象
        //然后才 会执行 将session数据保存到数据的操作。
        //因此 一旦对象被释放 则会执行该析构方法，就会释放连接
        //但是 在将session数据保存到数据库时 还需要这个连接，因此就会出现这个问题
        // 通常 这个mysql连接不用手动关闭，只需要等页面结束自动关闭即可。
        //mysql_close($this->link);
    }
    
}
?>