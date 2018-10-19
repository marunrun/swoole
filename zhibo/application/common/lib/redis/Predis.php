<?php

namespace app\common\lib\redis;

class Predis
{
    /**
     * 定义单例模式的变量
     * @var null
     */
    private static $instance = null;

    public $redis = null;


    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->redis = new \Redis();
        $res = $this->redis->connect(config('redis.host'), config('redis.port'), config('redis.timeOut'));
        if ($res === false) {
            throw  new  \Exception('redis connect error');
        }

        $res = $this->redis->auth(config('redis.password'));
        if ($res === false) {
            throw  new  \Exception('redis password  error');
        }
    }

    /**
     * 魔术方法
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        try {
            switch (count($arguments)) {
                case 1:
                    return $this->redis->$name($arguments[0]);
                    break;
                case 2:
                    return $this->redis->$name($arguments[0], $arguments[1]);
                    break;
                case 3:
                    return $this->redis->$name($arguments[0], $arguments[1], $arguments[2]);
                default :
                    return 'parameter error';
            }
        } catch (\Exception $e) {
            echo $e;
        }
//        switch (count($arguments))

//        $key = $arguments[0] ?: '';
//
//        if(!$key){
//            throw  new \Exception("key值必传");
//        }
//
//        $value = $arguments[1] ? : '';
//
//        if($value){
//            try {
//                return $this->redis->$name($key, $value);
//            }catch (\Exception $e){
//                return $e;
//            }
//        }else{
//            try {
//                return $this->redis->$name($key);
//            }catch (\Exception $e){
//                return $e;
//            }
//        }

    }


    /**
     * 删除
     * @param $key
     * @return int
     */
//    public function del($key)
//    {
//        return $this->redis->del($key);
//    }


    /**
     * 设置值
     * @param $key
     * @param $value
     * @param int $time
     * @return bool|string
     */
//    public function set($key, $value, $time = 0)
//    {
//        if (!$key) {
//            return '';
//        }
//
//        if (is_array($value)) {
//            $value = json_encode($value);
//        }
//
//        if (!$time) {
//            return $this->redis->set($key, $value);
//        }
//
//        return $this->redis->set($key, $value, $time);
//    }
//
    /**
     * 获取值
     * @param $key
     * @return bool|string
     */
//    public function get($key)
//    {
//        return $this->redis->get($key);
//    }

    /**设置哈希值
     * @param $key
     * @param $hKey
     * @param $value
     * @return int|string
     */
//    public function hSet($key, $hKey, $value)
//    {
//        if (!$key || !$hKey) {
//            return '';
//        }
//        if (is_array($value)) {
//            $value = json_encode($value);
//        }
//
//        return $this->redis->hSet($key, $hKey, $value);
//    }

    /**
     * 批量设置哈希值
     * @param $key
     * @param $value
     * @return bool|string
     */
//    public function hMset($key, $value)
//    {
//        if (!$key) {
//            return '';
//        }
//
//        return $this->redis->hMset($key, $value);
//    }

    /**
     * 设置过期时间
     * @param $key
     * @param $time
     * @return bool|string
     */
//    public function expire($key, $time)
//    {
//        if (!$key) {
//            return '';
//        }
//
//        return $this->redis->expire($key, $time);
//    }

    /**
     * 存到集合中
     * @param $key
     * @param $value
     * @return int
     */
//    public function sAdd($key, $value)
//    {
//        return $this->redis->sAdd($key, $value);
//    }

    /**
     * 从集合中删除
     * @param $key
     * @param $value
     * @return int
     */
//    public function sRem($key, $value)
//    {
//        return $this->redis->sRem($key, $value);
//    }
//
//    public function sMembers($key)
//    {
//        return $this->redis->sMembers($key);
//    }

}