<?php
/**
 *项目入口文件
 * Created by PhpStorm.
 * User: struggle
 * Date: 14-3-20
 * Time: 下午8:07
 */
include_once '../../AmaPhp.php';
define('PROJECT_PATH',str_replace('\\','/',__DIR__).'/');
include_once LIB_PATH."AmaPhp".EXT;
include_once ROOT_PATH.'Common/function.php';
define("APP_DEBUG",false);
define('URL_CTR','c'); //控制器参数
define('URL_ACT','f');//控制器方法参数
define("__PUBLIC__",'/Public/');//公共文件调用目录 js css img
//初始化
AmaPhp::init();
