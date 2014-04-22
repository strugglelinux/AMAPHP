<?php
/**
 * 模型类
 * Created by PhpStorm.
 * User: struggle
 * Date: 14-3-21
 * Time: 上午10:10
 */
class Model {
    private $db= null;
    //表名
    private $table ='';
    //带前缀的表名
    protected $tablePrefix ='';
    //真实表明
    protected $trueTable ='';

    protected $options =array();
    //当前model名称
    protected  $name = '';

    public function __construct(){
        $this->connect();
    }
    /**
     *链接默认数据库
     */
    private function connect(){
        try{
            $this->db = loadDb('default'); //链接默认数据库
            $config = $this->db->parasConfig();
            $this->tablePrefix = empty($prefix)?$config['dbprefix']:$prefix;
            $this->table = empty($table)? $this->getModelName():$table;
            $this->trueTable =$this->tablePrefix.$this->table;
            $this->db->setTable($this->trueTable);
        }catch (Exception $e){
            halt($e->getMessage());
        }
    }

    /**
     * @param $name
     * @param $val
     */
    public function __set($name,$val){
        $this->$name = $val;
    }
    public function __get($name){
        if(isset($this->$name))
            return $this->$name;
        return null;
    }
    /**
     * 查询条件
     * @param array $data
     */
    protected function where($data=array()){
        if(isset($this->options['where'])){
            $this->options['where'] =   array_merge($this->options['where'],$data);
        }else{
            $this->options['where'] =   $data;
        }
        return $this;
    }

    /**
     * distinct
     * @param $distinct
     */
    protected function distinct($distinct){
        $this->options['distinct'] = $distinct;
        return this;
    }
    /**
     * 指定查询字段 支持字段排除
     * @access public
     * @param mixed $field
     * @param boolean $except 是否排除
     * @return Model
     */
    protected function field($field ,$except=false){
        if(true ===$field ) {// 获取全部字段
            $fields     =  $this->getTableFields();
            $field      =  $fields?$fields:'*';
        }
        if($except) {// 字段排除
            if(is_string($field)) {
                $field  =  explode(',',$field);
            }
            $fields     =  $this->getTableFields();
            $field      =  $fields?array_diff($fields,$field):$field;
        }
        if(is_null($field)){
            $field = '*';
        }
        $this->options['field']   =   $field;
        return $this;
    }

    /**
     * 查询
     * @param $sql
     */
    protected function query( $sql ){
       return  $this->db->select($sql);
    }
    /**
     * 查询
     */
    protected function select(){
        $options = $this->_parseOptions();
        $sql =$this->db->parseSql($this->selectSql,$options);
        $this->options = array();
        return  $this->db->select($sql);
    }

    /**
     * 查询单条数据
     */
    protected function  select_one(){
        $sql = $$this->db->parseSql($this->selectSql,$this->options);
        $this->options = array();
        return  $this->db->query($sql)->fetch();
    }
    /**
     * 插入数据
     * @param array $data
     */
    protected function insert($data,$options=array(),$replace=false){
        $options = $this->_parseOptions($options);
        return $this->db->insert($data,$options,$replace);
    }

    /**
     * 更新数据
     * @param array $data
     */
    protected function update($data=array(),$options=array()){
        $options = $this->_parseOptions($options);
        return $this->db->update($data,$options);
    }

    /**
     * 删除数据
     * @param $table
     * @param array $option
     */
    protected function delete($options=array()){
        $options = $this->_parseOptions($options);
        return $this->db->delete($options);
    }
    /**
     * 获取最后执行的sql语句
     */
    protected function getLastSql(){
        return $this->db->getLastSql();
    }
    /**
     * 获取表字段
     */
    public function getTableFields(){
        return $this->db->getFields($this->trueTable);
    }
    /**
     * 获取表名称
     * @param string $name
     * @param string $prefix
     */
    protected function table($name='',$prefix = ''){
        if(!empty($name)){
            $this->table = $name;
            $this->tablePrefix = $prefix;
            $this->trueTable= $this->tablePrefix.$this->table;
        }
        $this->options['table'] = $this->trueTable;
        return $this;
    }

    /**
     * order
     * $data = array('字段'=>'ASC | DESC ','字段1'=>'ASC | DESC ')
     * * @param array $data
     */
    protected function order( $data=array() ){
        $this->options['order'] =$data;
        return $this;
    }

    /**
     * 返回记录条数
     * @param $offset  起始位置
     * @param $length 返回条数
     */
    protected function limit($offset,$length=null){
        $this->options['limit'] =   is_null($length)?$offset:$offset.','.$length;
        return $this;
    }

    /**
     * 获取插入记录的ID
     */
    public function lastInsertId(){
       return  $this->db->lastInsertId();
    }
    /**
    * 得到当前的Model对象名称
    * @access private
    * @return string
    */
    private function getModelName() {
        if(empty($this->name))
            $this->name =   substr(get_class($this),0,-5);
        return $this->name;
    }
    /**
     * 开启事务
     * @return resource
     */
    protected function startTrans() {
        return $this->db->startTrans();
    }

    /**
     * 提交事务
     * @return resource
     */
    protected function commit() {
       return $this->db->commit();
    }
    /**
     * 事务回滚
     * @access public
     * @return boolen
     */
    public function rollback() {
        return $this->db->rollback();
    }
    /**
     * 分析表达式
     * @access private
     * @param array $options 表达式参数
     * @return array
     */
    private function _parseOptions($options=array()) {
        if(is_array($options))
            $options =  array_merge($this->options,$options);
        else
            $options = $this->options;
        // 查询过后清空sql表达式组装 避免影响下次查询
        $this->options  =   array();
        if(!isset($options['table'])){
            // 自动获取表名
            $options['table']   =   $this->trueTable;
        }
        return $options;
    }
} 