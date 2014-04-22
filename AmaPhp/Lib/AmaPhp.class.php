<?php
/**
 * 引用类
 * Created by PhpStorm.
 * User: struggle
 * Date: 14-3-21
 * Time: 上午9:11
 */
class AmaPhp {
    private static  $_instance = array();
    /**
     * 初始化
     */
    public static function init(){

        set_exception_handler(array('AmaPhp','appException'));
        spl_autoload_register('AmaPhp::autoload');
        Route::start();

    }
    /**
     * 自动载入方法
     * @param $class
     */
   public  static function autoload($class){
       $len =strlen($class);
       $core_array = array('Model','Control','View','Route');
       if($len>7 && substr($class,-7) == CONTROL_EXT ){
            $file_dir = PROJECT_PATH.CONTROL_EXT.'/';
       }elseif($len>5 && substr($class,-5) == MODEL_EXT){
            $file_dir = PROJECT_PATH.MODEL_EXT.'/';
       }elseif(in_array($class,$core_array) ){
           $file_dir = CORE_PATH;
       }
       $file = str_replace('\\','/',$file_dir.$class.EXT);
       if(is_file($file)){
            require $file;
       }else{
           halt('错误:'.$class.'文件不存在');
       }
   }

    /**
     * 获取实例化对象
     * @param $class  类名称
     * @param string $method 对象方法
     * @return mixed
     */
    public static  function  instance( $class,$method=''){
        $_flag = $class.$method;
        if(!isset(self::$_instance[$_flag])){
            if(!empty($class)){
                if(class_exists($class)){
                    $obj = new $class();
                    if(!empty($method) && method_exists($obj,$method))
                        self::$_instance[$_flag] =  call_user_func_array(array(&$obj, $method),array());
                    else
                        self::$_instance[$_flag] =  $obj;
                }else
                    halt('错误:'.$class.'不存在');
            }
        }
        return self::$_instance[$_flag];
    }

    /**
     * 自定义异常处理
     * @access public
     * @param mixed $e 异常对象
     */
     public static function appException($e) {
        $error = array();
        $error['message']   = $e->getMessage();
        $trace  =   $e->getTrace();
        if('throw_exception'==$trace[0]['function']) {
            $error['file']  =   $trace[0]['file'];
            $error['line']  =   $trace[0]['line'];
        }else{
            $error['file']      = $e->getFile();
            $error['line']      = $e->getLine();
        }
        halt($error);
    }
}


