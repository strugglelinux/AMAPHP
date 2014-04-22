<?php
/**
 * 公共调用函数
 * Created by PhpStorm.
 * User: struggle
 * Date: 14-3-21
 * Time: 下午2:20
 * Mail:<struggleLinux@gmail.com>
 */
/**
 * 错误输出
 * @param mixed $error 错误
 * @return void
 */
function halt($error) {
    debug_backtrace();
    if(is_array($error)){
        foreach ($error as $key => $val){
            echo $key.':'.$val.'<br>';
        }
    }else{
        die($error);
    }
    exit;

}
//获取提交的数据
function  input($name){
    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            $value = $_GET;break;
        case 'POST':
            $value = $_POST;break;
        default:
            $value = $_GET;
    }
    return is_null($name)?$value:$value[$name];
}
function redirect($url = null,$time=0,$msg = ''){
    $url        = str_replace('\\','/',str_replace(array("\n", "\r"), '', $url));
    if (empty($msg))
        $msg    = "系统将在{$time}秒之后自动跳转到{$url}！";
    if (!headers_sent()) {
        // redirect
        if (0 === $time) {
            header('Location: ' . $url);
        } else {
            header("refresh:{$time};url={$url}");
            echo($msg);
        }
        exit();
    } else {
        $str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if ($time != 0)
            $str .= $msg;
        exit($str);
    }
}
/**
 * curl GET 操作方法
 * @param unknown $url url地址
 * @param unknown $type 获取类型 GET POST 默认 GET
 * @param unknown $param 传递参数数组
 */
function  ext_curl_get( $url , $param = array()){
    $param_str = '';
    $i=0;
    foreach ( $param as $key => $val){
        if($i == 0 ){
            $param_str .= $key.'='.$val;
        }else{
            $param_str .='&'.$key.'='.$val;
        }
        $i++;
    }
    if( !empty($param_str)&& !is_null($param_str )){
        $url .='?'.$param_str;
    }
    //echo $url;die;
    $curl = curl_init();
    // 设置你需要抓取的URL
    curl_setopt($curl, CURLOPT_URL, $url);
    // 设置header
    curl_setopt($curl, CURLOPT_HEADER, 0);
    // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    // 运行cURL，请求网页
    $data = curl_exec($curl);
    // 关闭URL请求
    curl_close($curl);
    return $data;
}
/**
 * curl POST 操作方法
 * @param unknown $url url地址
 * @param unknown $type 获取类型 GET POST 默认 GET
 * @param unknown $param 传递参数数组
 */
function  ext_curl_post( $url , $param = array()){
    $curl = curl_init();
    // 设置你需要抓取的URL
    curl_setopt($curl, CURLOPT_URL, $url);
    // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //设施post方式提交数据
    curl_setopt($curl, CURLOPT_POST, 1);
    //设置POST的数据
    curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
    //执行会话并获取内容
    $data = curl_exec($curl);
    // 关闭URL请求
    curl_close($curl);
    return $data;
}
/***
 * 将对象转换为数组
 */
function object_to_array($d) {
    if (is_object($d)) {
        $d = get_object_vars($d);
    }
    if (is_array($d)) {
        return array_map(__FUNCTION__, $d);
    }
    else {
        return $d;
    }
}
function loadClass($dir,$name){
    $file = $dir.$name.EXT;
    if(file_exists($file)){
        include_once $file;
    }else{
        halt('错误：'.$file.'不存在');
    }
}

/**
 * 获取配置文件
 * @param null $name 配置参数名称
 * @return mixed
 */
function loadConfig($name = ''){
    static $config = array();
    if(empty($config)){
        $file = PROJECT_PATH.'conf/conf.php';
        if(is_file($file)){
            include_once $file ;
        }
    }
    if(!is_null($name)){
        return $config[$name];
    }
    return $config;
}

/**
 * 去除bom头
 * @param $str
 * @return string
 */
function removeBom($str){
    $str = hex2bin(preg_replace('/^efbbbf/', '', bin2hex($str) ));
    if (0 === strpos(bin2hex($str), 'efbbbf')) {
        $str = substr($str, 3);
    }
    return $str;
}
/**
 * 使用正则验证数据
 * @access public
 * @param string $value  要验证的数据
 * @param string $rule 验证规则
 * @return boolean
 */
function regex($value,$rule) {
    $validate = array(
        //	验证名称必须是全部小写的字母或者与数字的组合
        'require'   =>  '/.+/',
        'email'     =>  '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
        'url'       =>  '/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/',
        'currency'  =>  '/^\d+(\.\d+)?$/',
        'number'    =>  '/^\d+$/',
        'plusnumber'=>	'/^\d+?$/',
        'zip'       =>  '/^\d{6}$/',
        'integer'   =>  '/^[-\+]?\d+$/',
        'double'    =>  '/^[-\+]?\d+(\.\d+)?$/',
        'english'   =>  '/^[A-Za-z]+$/',
        //	新增正则表达式
        'mixchars'	=>	'/^[\w_？！。.，,?!\x80-\xff]+$/', 	//中文字母数字下划线组合
        'ldchars'	=>	'/^\w+$/',							//字母数字的组合
        'tel'		=>	'/^(86)?1[3568]\d{9}$/',			//验证手机号码
        'twopoint'	=>	'/^[-\+]?\d+(\.\d{1,2})?$/',		//验证两位小数
        'twopluspoint'=>'/^\d+?(\.\d{1,2})?$/'				//验证两位正小数
    );
    // 检查是否有内置的正则表达式
    if(isset($validate[strtolower($rule)]))
        $rule       =   $validate[strtolower($rule)];
    return preg_match($rule,$value)===1;
}

/**
 * 载入DB
 * @param string $dbname
 * @return Db
 */
function loadDb($dbdriver){
    if(!class_exists('PDO')){
        throw new Exception("不存在PDO对象");
    }
    try{
        loadClass( LIB_PATH,'Db');
        $config = loadConfig($dbdriver);
        return new Db($config);
    }catch (Exception $e){
        throw new Exception($e->getMessage());
    }

}