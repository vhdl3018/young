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
     *
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
        $this->opt['where'] = " WHERE " . $where;
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

}