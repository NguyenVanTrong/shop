<?php

class sessionsTable extends mysqlDB {
    protected $table_name = 'sessions';
    protected $fields;

    public function __construct() {
        //调用父类的构造方法
        parent::__construct();
        //设置session处理器
        session_set_save_handler(
            array($this, 'open'),
            array($this, 'close'),
            array($this, 'read'),
            array($this, 'write'),
            array($this, 'destroy'),
            array($this, 'gc')
        );
        //开启session
        session_start();
    }

    public function open($save_path, $sess_name) {
        return true;
    }
    public function close() {
        return true;
    }
    public function read($sess_id) {
        $query = "select sess_data from cz_sessions where sess_id='{$sess_id}'";
        $row = $this->getRow($query);
        return $row['sess_data'];

    }
    public function write($sess_id, $sess_data) {
        $expire = time();
        $query = "insert into cz_sessions values ('{$sess_id}', '{$sess_data}', {$expire}) on duplicate key update sess_data='{$sess_data}', expire={$expire}";
        return $this->query($query);
    }
    public function destroy($sess_id) {
        $query = "delete from cz_sessions where sess_id='{$sess_id}'";
        return $this->query($query);
    }
    public function gc($ttl) {
        $expire = time() - $ttl;
        $query = "delete from cz_sessions where expire < {$expire}";
        return $this->query($query);
    }
}