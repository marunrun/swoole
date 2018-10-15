<?php

namespace  app\common\lib\redis;

class Predis
{
    /**
     * 定义单例模式的变量
     * @var null
     */
    private static $instance = null ;

    public $redis = null ;


    public static function getInstance()
    {
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->redis = new \Redis();
        $res = $this->redis->connect(config('redis.host'),config('redis.port'),config('redis.timeOut'));
        if($res  === false){
            throw  new  \Exception('redis connect error');
        }

        $res = $this->redis->auth(config('redis.password'));
        if($res  === false){
            throw  new  \Exception('redis password  error');
        }
    }


    public function set($key , $value ,$time = 0)
    {
        if(!$key){
            return '';
        }

        if(is_array($value)){
            $value = json_encode($value);
        }

        if(!$time){
            return $this->redis->set($key,$value);
        }

        return $this->redis->set($key , $value , $time);
    }


    public function get($key)
    {
        return $this->redis->get($key);
    }

}