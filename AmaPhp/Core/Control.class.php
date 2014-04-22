<?php
/**
 * 控制器类
 * Created by PhpStorm.
 * User: Struggle
 * Date: 14-3-21
 * Time: 上午10:29
 */
abstract class Control {

    private $view = null;

    private $name = '';

    public function __construct(){
        $this->view = AmaPhp::instance('View'); //实例化View 对象
    }

    /**
     * 显示视图
     * @param null $path  格式 ‘Control/Action’
     */
    protected  function display($path=null){
        $this->view->display($path);
    }

    /**
     * 设置页面输出变量的值
     * @param $name
     * @param $value
     */
    protected  function set($name,$value){
        $this->view->set($name,$value);
    }
    public function __set($name,$value){
        $this->set($name,$value) ;
    }
    /**
     * 获取页面输出变量
     * @param $name
     */
    protected  function get($name){
        return $this->view->get($name);
    }
    public function __get($name){
       return $this->get($name);
    }

    /**
     * 获取当前控制器名称
     */
    protected function getCotrolName(){
        if(empty($this->name)){
           $this->name = substr(get_class($this),0,-7);
        }
        return $this->name;
    }

    /**
     * 魔术方法 检测对象有不存在的操作
     * @param $method
     * @param $arg
     */
    public  function __call($method,$arg) {
        if(method_exists($method,this)){
            $this->$method($arg);
        }else
            halt('错误：'.$method.'该方法不存在');
    }
    /**
     * 是否AJAX请求
     * @access protected
     * @return bool
     */
    protected function isAjax() {
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
            if('xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']))
                return true;
        }
        return false;
    }

    /**
     * 页面跳转
     * @param null $url
     * @param int $time
     * @param string $msg
     */
    public function redirect($url = null,$time=0,$msg = ''){
        redirect($url,$time,$msg);
    }
} 