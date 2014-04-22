<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-3-25
 * Time: 下午2:51
 * Mail:<struggleLinux@gmail.com>
 */
class UserInfoModel extends  Model {
     public function getList(){
         $result =  $this->insert(array('name'=>'admin','pwd'=>md5('123123123')));
         // $result = $this->field('username')->order(array('id'=>'desc','username'=>'ASC'))->limit(0,30)->select();
         //$result =  $this->where(array('id'=>11))->update(array('username'=>'121323123','password'=>'asdasdadsad'));
         var_dump($result,$this->getLastSql());
         return $result;
     }
}