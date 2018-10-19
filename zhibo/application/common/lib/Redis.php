<?php

namespace app\common\lib;


class Redis
{
    /**
     * 验证码前缀 redis key的前缀
     * @var string
     */
    public static $pre = 'sms_';

    /**
     * 用户信息的前缀
     * @var string
     */
    public static $userpre = 'user_';
    public  $redis = null;


    public function __construct()
    {
        $this->redis = new \Swoole\Coroutine\Redis();
        $this->redis->connect(config('redis.host'),config('redis.port'));
        $this->redis->auth(config('redis.password'));

        return $this->redis;
    }


    /**
     * 返回一个包含前缀的key
     * @param $phone
     * @return string
     */
    public static function msmKey($phone)
    {
        return self::$pre.$phone;
    }


    public static function userKey($phone)
    {
        return self::$userpre.$phone;
    }

}