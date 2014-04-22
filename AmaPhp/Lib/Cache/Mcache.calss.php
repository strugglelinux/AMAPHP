<?php
/**
 * Memcache 缓存
 * Created by PhpStorm.
 * User: struggle
 * Date: 14-4-2
 * Time: 上午11:29
 * Mail:<struggleLinux@gmail.com>
 */
class Mcache{
    //memcache 类型
    private $mam_type = '';
    //缓存服务器设置默认为本地
    private $server = '127.0.0.1';
    //默认端口
    private $port = 11211;
    //过期时间
    private  $expirat  = 60;
    //memcache 对象
    private $cache = null;
    /**
     * 构造函数
     */
    public function __construct($server,$port,$expiration ){
        if(!class_exists('Memcache')){
            throw new Exception('Error:Memcache类不存在');
        }
        $this->expirat = empty($expiration)?$this->expirat:$expiration;
        $this->server= empty($server)?$this->server:$server;
        $this->port = empty($port)?$this->server:$port;
        $this->cache = new Memcache();
        $this->cache->addserver($server, $port);
    }

    /**
     * 设置缓存值
     * @param $key
     * @param $value
     * @param int $expiration
     */
    public function set($key,$value,$expiration=60){
        if(empty($expiration)){
            $expiration = $this->expirat;
        }
        $this->cache->set(md5($key),$value,$expiration);
    }

    /**
     * 获取缓存值
     * @param $key
     * @return array|string
     */
    public function get($key){
        return $this->cache->get(md5($key));
    }

    /**
     * 删除缓存值
     * @param $key key
     * @param int $expiration 超时自动清空时间
     * @return bool
     */
    public function delete($key,$expiration=20){
        if(empty($expiration)){
            $expiration = $this->expirat;
        }
        return $this->cache->delete(md5($key),$expiration);
    }

    /**
     * 清空组缓存
     * @param $key 该值存在是清空指定key的缓存，不存在时全部清空
     */
    public function flushGroup($group =''){
        if(is_null($group)){
            return  $this->cache->flush();
        }
        $data = $this->getGroup($group);
        foreach($data as $key => $val){
            $this->delete($key);
        }
        return true;
    }

    /**
     * 设置关联组 的值
     * @param string $group
     * @param $key
     */
    public function setGroup($group ='', $key = ''){
        $data= array();
        $group_data = $this->getGroup($group);
        if($group_data){
            $data = array_merge($data,array($key));
        }else{
            $data[]  = $key;
        }
        $this->set($group,json_encode($data));
    }

    /**
     * 获取Group 的值
     * @param $group
     * @return array|bool|string
     */
    public function getGroup($group){
       $group_data =  $this->get($group);
       if($group_data){
           $group_data = object_to_array( json_decode($group_data));
           return $group_data;
       }
       return false;
    }
} 