<?php



class adminUserTable extends mysqlDB {

    //表名属性 一个没有前缀的表名
    protected $table_name = 'admin_user';
    //表的字段列表
    protected $fields;

    /**
     * 利用用户名和密码 找到管理员信息
     *
     * @param $username string 管理员名
     * @param $password string 密码
     *
     * @return mixed 找到管理员时 返回管理员信息；找不到 返回false。
     */
    public function getByUserAndPass($username, $password) {
        //制作sql语句
        //由于 密码已经被加密，应该将用户填写的密码 同样的方式加密后在比较
        //$password = md5($password);
        $query = "select * from {$this->getTableName()} where username='{$username}' and password='{$password}'";

        return $this->getRow($query);
    }
    
    /**
     * 利用用户id 找到管理员信息
     *
     * @param admin_id 管理员id
     *
     * @return mixed 找到管理员时 返回管理员信息；找不到 返回false。
     */
    public function getById($admin_id){
    	$query = "select * from {$this->getTableName()} where admin_id='{$admin_id}'";
        return $this->getRow($query);
    }

    /**
     * 更新用户登录信息的方法
     *
     */
    public function setLoginInfo() {
        //设置更新语句
//        $query = "update cz_admin_user set last_login=unix_timestamp(now())";
        //虽然上面的代码可以完成，但是 为了最大程度的保证 代码的可移植性，
        //需要尽可能少的 使用数据库特性，而是去使用 php的操作完成。
        $last_login = time();//利用php的time()函数获得当前的时间戳
        //条件是 admin_id 为 当前 session内的admin_id,也就是当前登录用户
        $query = "update {$this->getTableName()} set last_login={$last_login} where admin_id={$_SESSION['admin']['admin_id']}";
        //执行
        return $this->query($query);


    }

}