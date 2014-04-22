<?php
/**
 * 配置文件
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-3-25
 * Time: 下午2:14
 * Mail:<struggleLinux@gmail.com>
 */
$config['default']['hostname'] = 'localhost';
$config['default']['username'] = 'root';
$config['default']['password'] = '123456';
$config['default']['port'] = 3306;
$config['default']['database'] = 'test';
$config['default']['dbdriver'] = 'mysql';
$config['default']['dbprefix'] = '';
$config['default']['pconnect'] = TRUE;
$config['default']['db_debug'] = TRUE;
$config['default']['cache_on'] = FALSE;
$config['default']['cachedir'] = '';
$config['default']['char_set'] = 'utf8';
$config['default']['dbcollat'] = 'utf8_general_ci';
$config['default']['swap_pre'] = '';
$config['default']['autoinit'] = TRUE;
$config['default']['stricton'] = FALSE;
//运营中心数据库配置
$config['operatingcenter']['hostname'] = '192.168.1.10';
$config['operatingcenter']['username'] = 'app_admin';
$config['operatingcenter']['password'] = 'app_admin';
$config['operatingcenter']['port'] = 3317;
$config['operatingcenter']['database'] = 'operatingcenter';
$config['operatingcenter']['dbdriver'] = 'mysql';
$config['operatingcenter']['dbprefix'] = '';
$config['operatingcenter']['pconnect'] = FALSE;
$config['operatingcenter']['db_debug'] = TRUE;
$config['operatingcenter']['cache_on'] = FALSE;
$config['operatingcenter']['cachedir'] = '';
$config['operatingcenter']['char_set'] = 'utf8';
$config['operatingcenter']['dbcollat'] = 'utf8_general_ci';
$config['operatingcenter']['swap_pre'] = '';
$config['operatingcenter']['autoinit'] = TRUE;
$config['operatingcenter']['stricton'] = FALSE;
//订单中心数据库配置
$config['orderandgiftcenter']['hostname'] = '192.168.1.10';
$config['orderandgiftcenter']['username'] = 'app_admin';
$config['orderandgiftcenter']['password'] = 'app_admin';
$config['orderandgiftcenter']['port'] = 3317;
$config['orderandgiftcenter']['database'] = 'orderandgiftcenter';
$config['orderandgiftcenter']['dbdriver'] = 'mysql';
$config['orderandgiftcenter']['dbprefix'] = '';
$config['orderandgiftcenter']['pconnect'] = FALSE;
$config['orderandgiftcenter']['db_debug'] = TRUE;
$config['orderandgiftcenter']['cache_on'] = FALSE;
$config['orderandgiftcenter']['cachedir'] = '';
$config['orderandgiftcenter']['char_set'] = 'utf8';
$config['orderandgiftcenter']['dbcollat'] = 'utf8_general_ci';
$config['orderandgiftcenter']['swap_pre'] = '';
$config['orderandgiftcenter']['autoinit'] = TRUE;
$config['orderandgiftcenter']['stricton'] = FALSE;

$config['mamcache']['servers']=array('127.0.0.1:1121','127.0.0.1:1121','127.0.0.1:1121');
