<?php


namespace  app\common\lib\task;

use app\common\lib\Redis;
use app\common\lib\redis\Predis;

class Task
{

    public function sendMsg($data)
    {
        try {
            $obj = new \app\common\lib\ali\Sms();
            $res = $obj::sendSms($data['phone'], $data['code']);
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }

        if($res->Code === 'OK'){
            Predis::getInstance()->set(Redis::msmKey($data['phone']),$data['code']);
            return $res;
        }else{
            return false;
        }


    }
}