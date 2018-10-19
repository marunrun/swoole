<?php


namespace  app\common\lib\task;

use app\common\lib\Redis;
use app\common\lib\redis\Predis;
class Task
{

    /**
     * 异步发送验证码
     * @param $data
     * @param $server \swoole_websocket_server
     * @return \app\common\lib\ali\stdClass|bool
     */
    public function sendMsg($data,$server)
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

    /**
     * 异步推送赛况消息
     * @param $data
     * @param $server
     */
    public function pushMsg($data,$server)
    {
        //        赛况基本数据入库  推送需要的数据给webSocket
        $ids = Predis::getInstance()->sMembers(config('redis.live_redis_key'));

        foreach ($ids as $id){
            $server->push($id,json_encode($data));
        }
    }


    public function pushChat($data,$server)
    {
        $ids = Predis::getInstance()->sMembers(config('redis.live_redis_key'));

        foreach ($ids as $id){
            $server->push($id,json_encode($data));
        }
    }

}