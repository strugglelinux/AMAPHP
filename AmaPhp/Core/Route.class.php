<?php
/**
 * 路由类
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-4-3
 * Time: 下午2:01
 * Mail:<struggleLinux@gmail.com>
 */
class Route {
    public static function start(){
        $param = $_p = array();
        $control = $action = '';
        if(!isset($_SERVER['PATH_INFO'])){ //解析param1=value1&param2=value2
            $_p = self::_paramsKeyVal( $_SERVER['QUERY_STRING']);
        }else{
            if(!empty($_SERVER['QUERY_STRING'])){
                $_n = strpos('&',$_SERVER['QUERY_STRING']);
                if( !$_n ){
                    list($p,$v) = explode('=',$_SERVER['QUERY_STRING']);
                    $_p[$p] = $v;
                }else{
                    $_p = self::_paramsKeyVal( $_SERVER['QUERY_STRING']);
                }
            }
            if(isset($_SERVER['REQUEST_URI'])){
                $_a =  explode('?',$_SERVER['REQUEST_URI']);
                $str = str_replace('\\','/',substr($_a[0],1,strlen($_a[0]))) ;
                list($control,$action) =  explode('/',$str);
                $param[URL_CTR] =ucfirst(strtolower($control));
                $param[URL_ACT] =$action;
            }
        }
        $_GET = array_merge($_GET,array_merge($param,$_p));
        $_class =self::getControl(URL_CTR).'Control';
        $_action =self::getAction(URL_ACT);
        if(class_exists($_class)){
            $class = new $_class();
            defined('__CONTROL__') or define('__CONTROL__',self::getControl(URL_CTR)); //控制器
            if(method_exists($_class,$_action) ){
                defined('__ACTION__') or define('__ACTION__',self::getAction(URL_ACT)); //控制器操作名称
                $class->$_action();
            }else{
                halt('错误:'.$_class.'对象中不存在'.$_action.'方法');
            }
        }else
            halt('错误:'.$_class.'不存在');
    }

    /**
     * 解析param=val&param=val&param=val 形式参数
     * @param $str
     * @return array
     */
    private static function _paramsKeyVal($param){
        //var_dump($param);
        $_param =array();
        $_p = array();
        if(empty($param))return $_param;
        if(is_string($param)){
            $_p = explode('&',$param);
        }
        //var_dump($_p);
        foreach ($_p as $key => $val){
            $_t = explode('=',trim($val));
            if(count($_t) < 2 ){
                continue;
            }
            $_param[trim($_t[0])] = trim($_t[1]);
        }
        return $_param;
    }

    /**
     * 解析 param1/val1/param2/val2/param3/val3
     * @param $str
     * @return array
     */
    private  static function _paramsKeyVal1($param){
        $_param = array();
        $_p = array();
        if(is_string($param)){
            $_p = explode('/',$param);
        }
        if(is_array($param)){
            $_p = array_merge($_p,$param);
        }
        for($i=0;$i <count($_p);$i++){
            $_param[trim($_p[$i])] =trim($_p[$i+1]);
            $i++;
        }
        return $_param;
    }
    /**
     * 获取去控制器对象名称
     * @param $val
     */
    public static function getControl($var){
        $control = !empty($_POST[$var]) ?$_POST[$var] :(!empty($_GET[$var])?$_GET[$var]:'Index');
        unset($_POST[$var],$_GET[$var]);
        return ucfirst(strip_tags($control)); //首字母大写
    }

    /**
     * 获取操作名称
     */
    public static function getAction($var){
        $action   = !empty($_POST[$var]) ?$_POST[$var] :(!empty($_GET[$var])?$_GET[$var]:'index');
        unset($_POST[$var],$_GET[$var]);
        return strtolower(strip_tags($action));
    }
} 