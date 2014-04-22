<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-3-25
 * Time: 上午11:44
 * Mail:<struggleLinux@gmail.com>
 */
class Db extends PDO{
    //sql语句
    private $sql = '';
    // 查询表达式
    protected $selectSql  = 'SELECT%DISTINCT% %FIELD% FROM %TABLE%%JOIN%%WHERE%%GROUP%%HAVING%%ORDER%%LIMIT% %UNION%';
    //PDOStatement 对象
    private $psth = null;
    private $config = array();
    /**
     * 构造函数
     * @param $dns
     * @param $user
     * @param $pwd
     */
    public function __construct($config){
        $this->config = $config;
        $dns = $this->getDbType($config);
        if($dns){
            $pconnect =  $config['pconnect']?$config['pconnect']:false;
            $charset = empty($config['char_set'])?"SET NAMES utf8":"SET NAMES ".$config['char_set'];
            $option = array(
                    PDO::ATTR_PERSISTENT =>$pconnect,
                    PDO::MYSQL_ATTR_INIT_COMMAND=>$charset,
                    PDO::ERRMODE_EXCEPTION => true
            );
         }
        parent::__construct($dns,$config['username'],$config['password'] ,$option);
    }

    /**
     * 获取 数据库类型
     * @param $config
     */
    private  function getDbType($config){
        if( 'mysql' == strtolower($config['dbdriver']) ||  'mysqli' == strtolower($config['dbdriver']) ){
            $dns = 'mysql:dbname='.$config['database'].";host=".$config['hostname'].':'.$config['port'];
        }else{
            halt("错误：不存在".$config['dbdriver'].'->PDO驱动类型');
        }
        return  $dns;
    }

    /**
     * 获取配置信息
     * @return array
     */
    public function parasConfig(){
        return $this->config;
    }
    /**
     * 执行sql
     * @param $sql sql语句
     * @param $params 参数
     */
    public function query($sql, $params=array()){
        if($this->psth)$this->free();
        $this->sql = $sql;
        $this->psth =  $this->prepare($sql);
        $this->psth->execute($params);
        return $this;
    }

    /**
     * 获取全部数据
     */
    public function fetchAll(){
        if(!$this->psth) return false;
        return  $this->psth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 获取一条数据
     */
    public function fetch(){
        if(!$this->psth) return false;
        return  $this->psth->fetch(PDO::FETCH_ASSOC);
    }
    /**
     * 执行Sql 返回受此语句影响的行数。
     * @param $sql
     */
    private function execute($sql){
        return $this->exec($sql);
    }

    /**
     *  返回最后插入行的ID或序列值
     * @return string
     */
    public function lastInsertId(){
        return $this->lastInsertId();
    }
    /**
     * 开启事务
     * @return resource
     */
    public function startTrans() {
        $this->beginTransaction();
    }

    /**
     * 释放资源
     */
    private function free(){
        if($this->psth){
            $this->psth->closeCursor();
        }
        $this->psth = null;
    }


    /**
     * 查询
     */
    public function select($sql){
        $this->sql = $sql;
        return  $this->query($sql)->fetchAll();
    }
    /**
     * 插入数据
     * @param array $data
     */
    public  function insert($data,$options=array(),$replace=false){
        $values  =  $fields    = array();
        foreach ($data as $key=>$val){
            $fields[]   =  $key;
            $values[]   =  $this->parseValue($val);
        }
        $sql   =  ($replace?'REPLACE':'INSERT').' INTO '.$options['table'].' ('.implode(',', $fields).') VALUES ('.implode(',', $values).')';
        $this->sql = $sql;
        return $this->execute($sql);
    }

    /**
     * 获取表字段
     */
    public function getFields($table){
        $result =   $this->query('SHOW COLUMNS FROM '.$table)->fetchAll();
        $info   =   array();
        if($result) {
            foreach ($result as $key => $val) {
                $info[$val['Field']] = array(
                    'name'    => $val['Field'],
                    'type'    => $val['Type'],
                    'notnull' => (bool) ($val['Null'] === ''), // not null is empty, null is yes
                    'default' => $val['Default'],
                    'primary' => (strtolower($val['Key']) == 'pri'),
                    'autoinc' => (strtolower($val['Extra']) == 'auto_increment'),
                );
            }
        }
        return $info;
    }
    /**
     * 更新数据
     * @param array $data
     */
    public function update($data=array(),$options=array()){
        $sql   = 'UPDATE '
            .$this->parseTable($options['table'])
            .$this->parseSet($data)
            .$this->parseWhere(!empty($options['where'])?$options['where']:'')
            .$this->parseOrder(!empty($options['order'])?$options['order']:'')
            .$this->parseLimit(!empty($options['limit'])?$options['limit']:'')
            .$this->parseComment(!empty($options['comment'])?$options['comment']:'');
        $this->sql = $sql;
        return $this->execute($sql);
    }
    public function getLastSql(){
        return $this->sql;
    }
    /**
     * 删除数据
     * @param $table
     * @param array $option
     */
    public  function delete($options=array()){
        $sql   = 'DELETE FROM '
            .$this->parseTable($options['table'])
            .$this->parseWhere(!empty($options['where'])?$options['where']:'');
        $this->sql = $sql;
        return $this->execute($sql);
    }
    /**
     * 过滤值
     */
    public function filterValue($val){
        return  mysql_real_escape_string($val);
    }

    /**
     * 数据表
     * @param $table
     */
    public function setTable($table){
        $this->trueTable = $table;
    }
    /**
     * table 处理
     * @param $tables
     */
    public function parseTable($tables = array()){
        if(is_null($tables)){
            $tables = $this->trueTable;
        }elseif(is_array($tables)) {// 支持别名定义
            $array   =  array();
            foreach ($tables as $table=>$alias){
                $array[] =  $table.' AS  '.$alias;
            }
            $tables  =  implode(',',$tables);
        }
        return $tables;
    }
    /**
     * distinct处理
     * @access public
     * @param mixed $distinct
     * @return string
     */
    public  function parseDistinct($distinct) {
        return !empty($distinct)?   ' DISTINCT ' :'';
    }
    /**
     * limit 处理
     * @param mixed $lmit
     * @return string
     */
    public  function parseLimit($limit) {
        return !empty($limit)?   ' LIMIT '.$limit.' ':'';
    }

    public function parseWhere($data){
        $_str ='';
        if(is_string($data)){
            $_str .= $data;
        }elseif(is_array($data) && !empty($data)){
            $_str_arr = array();
            foreach($data as $key => $val ){
                if(is_array($val)){
                    $_str_arr[] =  $key . ' '.strtoupper($val[0])  .' '. $this->parseValue($val[1]) ;
                }else{
                    $_str_arr[] =  $key .'='. $this->parseValue($val);
                }
            }
            $_str .=   implode(' AND ',$_str_arr);
        }
        return empty($_str)?'':" WHERE " .$_str;
    }
    /**
     * order
     * $data = array('字段'=>'ASC | DESC ','字段1'=>'ASC | DESC ')
     * * @param array $data
     */
    protected function parseOrder($order) {
        if(is_array($order)) {
            $array   =  array();
            foreach ($order as $key=>$val){
                $array[] = $key.' '.$val;
            }
            $order   =  implode(',',$array);
        }
        return !empty($order)?  ' ORDER BY '.$order:'';
    }
    /**
     * field处理
     * @access public
     * @param mixed $fields
     * @return string
     */
    public function parseField($fields) {
        if(is_string($fields) && strpos($fields,',')) {
            $fields    = explode(',',$fields);
        }
        if(is_array($fields)) {
            // 完善数组方式传字段名的支持
            // 支持 'field1'=>'field2' 这样的字段别名定义
            $array   =  array();
            foreach ($fields as $key=>$field){
                $array[] =  $key .' AS '.$field;
            }
            $fieldsStr = implode(',', $array);
        }elseif(is_string($fields) && !empty($fields)) {
            $fieldsStr = $fields;
        }else{
            $fieldsStr = '*';
        }
        //如果是查询全部字段，并且是join的方式，那么就把要查的表加个别名，以免字段被覆盖
        return $fieldsStr;
    }
    /**
     * group处理
     * @access public
     * @param mixed $group
     * @return string
     */
    public function parseGroup($group) {
        return !empty($group)? ' GROUP BY '.$group:'';
    }
    /**
     * having处理
     * @access public
     * @param string $having
     * @return string
     */
    public function parseHaving($having) {
        return  !empty($having)?   ' HAVING '.$having:'';
    }
    /**
     * join分析
     * @access public
     * @param mixed $join
     * @return string
     */
    public function parseJoin($join) {
        $joinStr = '';
        if(!empty($join)) {
            if(is_array($join)) {
                foreach ($join as $key=>$_join){
                    if(false !== stripos($_join,'JOIN'))
                        $joinStr .= ' '.$_join;
                    else
                        $joinStr .= ' LEFT JOIN ' .$_join;
                }
            }else{
                $joinStr .= ' LEFT JOIN ' .$join;
            }
        }
        return $joinStr;
    }
    /**
     * union处理
     * @access public
     * @param mixed $union
     * @return string
     */
    public function parseUnion($union) {
        if(empty($union)) return '';
        if(isset($union['_all'])) {
            $str  =   'UNION ALL ';
            unset($union['_all']);
        }else{
            $str  =   'UNION ';
        }
        foreach ($union as $u){
            $sql[] = $str.(is_array($u)?$this->buildSelectSql($u):$u);
        }
        return implode(' ',$sql);
    }
    /**
     * comment处理
     * @access protected
     * @param string $comment
     * @return string
     */
    protected function parseComment($comment) {
        return  !empty($comment)?   ' /* '.$comment.' */':'';
    }
    /**
     * set分析
     * @access public
     * @param array $data
     * @return string
     */
    public function parseSet($data) {
        foreach ($data as $key=>$val){
            $set[]  =   $key .'='.$this->parseValue($val);
        }
        return ' SET '.implode(',',$set);
    }
    /**
     * value处理
     * @access public
     * @param mixed $value
     * @return string
     */
    public function parseValue($value) {
        if(is_string($value)) {
            $value =  '\''.$this->filterValue($value).'\'';
        }elseif(isset($value[0]) && is_string($value[0]) && strtolower($value[0]) == 'exp'){
            $value =  $this->filterValue($value[1]);
        }elseif(is_array($value)) {
            $value =  array_map(array($this, '_parseValue'),$value);
        }elseif(is_bool($value)){
            $value =  $value ? '1' : '0';
        }elseif(is_null($value)){
            $value =  'null';
        }
        return $value;
    }

    /**
     * 替换SQL语句中表达式
     * @access public
     * @param array $options 表达式
     * @return string
     */
    public function parseSql($sql,$options=array()){
        $sql   = str_replace(
            array('%TABLE%','%DISTINCT%','%FIELD%','%JOIN%','%WHERE%','%GROUP%','%HAVING%','%ORDER%','%LIMIT%','%UNION%'),
            array(
                $this->parseTable($options['table']),
                $this->parseDistinct(isset($options['distinct'])?$options['distinct']:false),
                $this->parseField(!empty($options['field'])?$options['field']:'*'),
                $this->parseJoin(!empty($options['join'])?$options['join']:''),
                $this->parseWhere(!empty($options['where'])?$options['where']:''),
                $this->parseGroup(!empty($options['group'])?$options['group']:''),
                $this->parseHaving(!empty($options['having'])?$options['having']:''),
                $this->parseOrder(!empty($options['order'])?$options['order']:''),
                $this->parseLimit(!empty($options['limit'])?$options['limit']:''),
                $this->parseUnion(!empty($options['union'])?$options['union']:'')
            ),$sql);
        return $sql;
    }
    /**
     * 关闭连接
     */
    public function close(){}
    /**
     * 析构方法
     */
    public function __destruct(){
        if($this->psth){
            $this->free();
        }
    }
} 