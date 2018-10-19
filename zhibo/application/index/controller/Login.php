<?php

namespace app\index\controller;

use app\common\lib\redis\Predis;
use app\common\lib\Util;
use app\common\lib\Redis;



class Login
{
    public function index()
    {

        //获取手机号和验证码
        $phoneNum = intval($_GET['phone_num']);
        $code     = intval($_GET['code']);

        if(empty($phoneNum) ||empty($code)){
            return Util::show(config('code.error') , '手机号或密码不能为空!');
        }

        $redis = Predis::getInstance();
        $res = $redis->get(Redis::msmKey($phoneNum));

        if($res == $code){
            $data  = [
                'user'      => $phoneNum,
                'srckey'    => md5(Redis::userKey($phoneNum)),
                'time'      => time(),
                'isLogin'   => true
            ];

            $redis->set(Redis::userKey($phoneNum),$data);
            //一个验证码只能使用一次!! 验证成功后就从redis中删除
//            $redis->del(Redis::userKey($phoneNum));

            return Util::show(config('code.success'),'登录成功' , $data );
        }

        return  Util::show(config('code.error'),'验证码错误或失效,请重新获取');
    }
}