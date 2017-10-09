<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/9/20
 * Time: 12:26
 */

class Model {
    //保存连接信息
    public static $link = null;
    //保存表名
    protected $table = null;
    //初始化信息
    private $opt;
    //记录发送的sql
    public static $sqls = array();

    public function __construct($table = null){
        $this->table = is_null($table) ? C('DB_PREFIX') . $this->table : C('DB_PREFIX') . $table;
        //连接数据库empty
        $this->_connect();

        //初始化sql信息
        $this->_opt();
    }

    /**
     * 连接数据库
     */
    private function _connect(){
        if(is_null(self::$link)){
            if(C('DB_DATABASE') == '') halt('请先配置数据库');
            $link = new Mysqli(C('DB_HOST'), C('DB_USER'),C('DB_PASSWORD'), C('DB_DATABASE') , C('DB_PORT'));
            if($link->connect_error) halt('数据库连接失败，请检查数据库配置项');
            $link->set_charset(C('DB_CHARSET'));
            self::$link = $link;
        }
    }

    /**
     *  配置数据库类相关操作属性
     */
    private function _opt(){
        $this->opt = array(
            'field' => '*',
            'where' => '',
            'group' => '',
            'having' => '',
            'order' => '',
            'limit' => ''
        );
    }

    /**
     * @param $sql
     * @return array
     */
    public function query($sql) {
        self::$sqls[] = $sql;
        $link = self::$link;
        $result = $link->query($sql);
        if($link->errno) halt('【Mysql错误】：' . $link->error . '<br />【SQL语句】：' . $sql);
        $rows = array();
        while($row = $result->fetch_assoc()){
            $rows[] = $row;
        }
        //释放资源
        $result->free();
        $this->_opt();
        //返回查询结果
        return $rows;

    }

    /**
     * @return array
     */
    public function all(){
        $sql = "SELECT " . $this->opt['field'] . " FROM " .$this->table . $this->opt['where'] . $this->opt['group'] . $this->opt['having'] . $this->opt['order'] . $this->opt['limit'];
        return $this->query($sql);
    }

    /**
     * @param $field
     * @return $this
     */
    public function field($field){
        $this->opt['field'] = $field;
        return $this;
    }

    /**
     * @param $where
     * @return $this
     */
    public function where($where){
        $this->opt['where'] = " WHERE " . $this->_safe_str($where);
        return $this;
    }

    /**
     * @param $order
     */
    public function order($order){
        $this->opt['order'] = " ORDER BY " . $order;
        return $this;
    }

    /**
     * @param $limit
     */
    public function limit($limit){
        $this->opt['limit'] = " LIMIT " . $limit;
        return $this;
    }

    /**
     * 查找一条数据
     * @return mixed
     */
    public function find(){
        $data = $this->limit(1)->all();
        return current($data);
    }

    /**
     * 执行非返回资源的sql函数
     * @param $sql
     * @return mixed
     */
    public function exe($sql){
        self::$sqls[] = $sql;
        $link = self::$link;
        $bool = $link->query($sql);
        $this->_opt();

        if(is_object($bool)){
            halt("请用query()方法使用sql语句");
        }

        if($bool){
            return $link->insert_id ? $link->insert_id : $link->affected_rows;
            $bool->free();
        }else{
            halt('【Mysql语法错误】' . $link->error . '<br />【SQL】' . $sql);
        }

    }

    /**
     * 删除指定条件的数据
     * @return mixed
     */
    public function delete(){
        if(empty($this->opt['where'])) halt('执行删除语句，必须有where条件！');
        $sql = "DELETE FROM " . $this->table . $this->opt['where'];
        return $this->exe($sql);
    }

    /**
     * 对数据进行转义
     * @param $str
     * @return mixed
     */
    private function _safe_str($str){
        if(get_magic_quotes_gpc()){
            $str = stripslashes($str);
        }

        return self::$link->real_escape_string($str);
            
    }

    /**
     * 添加数据
     * @param null $data
     * @return mixed
     */
    public function add($data=null){
        //判断data数据是否为空，为空则直接取POST值
        if(is_null($data)) $data = $_POST;
        $fields = '';
        $values = '';
        //insert into admin( username,passwd) values ('adad','111111');
        //按照INSERT INTO语句格式，拼接sql语句
        foreach ($data as $f => $v){
            $fields .= "`" . $this->_safe_str($f) . "`" . ",";
            $values .= "'" . $this->_safe_str($v) . "'" . ",";
        }
        $fields = trim($fields, ',');
        $values = trim($values, ',');
        $sql = '';
        $sql = "INSERT INTO " . $this->table . "($fields)" . " VALUES " . "($values)";
        //执行sql无结果集查询
        return $this->exe($sql);
    }

    /**
     * 修改数据
     * @param null $data
     * @return mixed
     */
    public function update($data=null){
        //执行更新语句时，where条件不能为空
        if(empty($this->opt['where'])) halt("执行update语句，where条件不能空，请重新设置！");
        //判断data数据是否为空，为空则直接取POST值
        if(is_null($data)) $data = $_POST;
        $values = '';
        //update `admin` set username='usersss',passwd='11111' where id = 4;
        //按照INSERT INTO语句格式，拼接sql语句
        foreach ($data as $f => $v){
            $values .= "`" . $this->_safe_str($f) . "`" . "='" . $this->_safe_str($v) . "',";
        }
        $values = trim($values, ',');
        $sql = '';
        $sql = "UPDATE " . $this->table . " SET " . $values . $this->opt['where'];
        return $this->exe($sql);
    }
    
    
    
    
    
    
}