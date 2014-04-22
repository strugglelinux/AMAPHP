<?php
/**
 * index 控制类
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-3-21
 * Time: 上午11:34
 * Mail:<struggleLinux@gmail.com>
 */
class IndexControl extends Control{
       public function index(){

           $name = $this->getCotrolName();
           $this->set('a',$name);
           $user = new UserInfoModel();
          // $result = $user->getList();
           var_dump($_POST,$_GET);
           $this->display();
       }
}