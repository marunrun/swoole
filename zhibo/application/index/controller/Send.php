<?php

namespace app\index\controller;


use app\common\lib\ali\Sms;
use app\common\lib\Redis;
use app\common\lib\Util;

class Send
{
    public function index()
    {
        $phoneNum = request()->get('phone_num', 0, 'intval');

        if (empty($phoneNum)) {
            return Util::show(config('code.error'), '请输入手机号');
        }

        $res = preg_match("/^(13[0-9]|14[0-9]|15[0-9]|166|17[0-9]|18[0-9]|19[8|9])\d{8}$/",$phoneNum);
        if(!$res){
            return Util::show(config('code.error'),'手机号格式错误');
        }

        //生成一个随机数,发送验证码 ，并存到redis
        $code = rand(1000, 9999);

        $taskData = [
            'method'    => 'sendMsg',
            'data'  =>[
                'phone' => $phoneNum,
                'code' => $code,
                ]
        ];

        $_POST['http_server']->task($taskData);



//        if($res->Code === 'OK'){
            $redis = new \Swoole\Coroutine\Redis();
//            $redis->connect(config('redis.host'),config('redis.port'));
//            $redis->auth(config('redis.password'));
//            $redis->set(Redis::msmKey($phoneNum),$code , config('redis.codeTime'));
//
            return Util::show(config('code.success'),'验证码发送成功',['phoneNum'=> $phoneNum]);
//        }else{

//            return Util::show(config('code.error'),'验证码发送失败,请重试',['phoneNum' => $phoneNum]);
//        }

    }


}