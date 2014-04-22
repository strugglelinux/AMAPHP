<?php
/**
 * 视图类
 * Created by PhpStorm.
 * User: struggle
 * Date: 14-3-21
 * Time: 上午10:34
 */
class View {
     private $_val =array() ;
    public function __construct(){
        var_dump('View');
    }
    /**
     * 设置页面输出变量
     * @param $name
     * @param string $value
     */
    public function set( $name,$value = ''){
         if(is_array($name)) {
             $this->_val   =  array_merge($this->_val,$name);
         }else {
             $this->_val[$name] = $value;
         }
     }

    /**
     * 获取页面输出变量
     * @param string $name
     * @return bool
     */
    public function get($name=''){
        if('' === $name) {
            return $this->_val;
        }
        return isset($this->_val[$name])?$this->_val[$name]:false;
    }
    /**
     * 显示模板
     * @param null $path
     */
    public function display($path=null){
        $this->templateDisplay($path);
    }

    /**
     * 页面操作
     * @param null $path
     */
    private function templateDisplay($path=null){
         if(is_null($path)){
             $file = PROJECT_PATH . 'View/'. __CONTROL__ . '/' . __ACTION__ . '.php';
         }else{
             $file = PROJECT_PATH . 'View/'.str_replace('\\','/',$path). '.php';
         }
        if(!file_exists( $file)) halt("错误：".basename($file).'文件不存在');

        extract ($this->_val);
        include $file;
    }
} 