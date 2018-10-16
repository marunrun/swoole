<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/16
 * Time: 15:30
 */

namespace app\admin\controller;


use app\common\lib\redis\Predis;

class Live
{


    public function index()
    {
        return 'admin-live-index';
    }

    public function push()
    {
//        赛况基本数据入库  推送需要的数据给webSocket
        $ids = Predis::getInstance()->sMembers(config('redis.live_redis_key'));

        foreach ($ids as $id){

            $res = $_POST['http_server']->push($id,'hello');
            if($res === false){

            }
        }
    }
}