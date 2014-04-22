<?php
/**
 * AmaPhp 初始配置文件
 * Created by PhpStorm.
 * User: struggle
 * Date: 14-3-20
 * Time: 下午8:07
 */
session_start();
define('AMAPHP_INI','1');
define('AMAPHP_VERSION','0.0.1');
define('EXT','.class.php') ;
defined('ROOT_PATH') or define('ROOT_PATH',str_replace('\\','/',__DIR__).'/');//根目录路径
defined('CORE_PATH') or define('CORE_PATH',ROOT_PATH.'Core/'); //核心目录路径
defined('EXTEND_PATH') or define('EXTEND_PATH',ROOT_PATH.'Extend/');//扩展目录路径
defined('LIB_PATH') or define('LIB_PATH',ROOT_PATH.'Lib/'); //库文件目录
defined('CONTROL_EXT') or define('CONTROL_EXT','Control');//控制器后缀
defined('MODEL_EXT') or define('MODEL_EXT','Model');//模型后缀
