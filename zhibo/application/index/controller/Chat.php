<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/17
 * Time: 13:50
 */

namespace app\index\controller;


use app\common\lib\Util;

class Chat
{
    public function index()
    {

        if(empty($_POST['game_id'])){
            return Util::show(config('code.error'),'error');
        }

        $content = trim($_POST['content']);

        if($content == ' '){
            return Util::show(config('code.error'),'content is empty');
        }

        $data = [
            'user'  => '用户'.rand(1000,9999),
            'content'   => $content
        ];

        foreach ($_POST['http_server']->ports[1]->connections as $id){
            $_POST['http_server']->push($id,json_encode($data));
        }

        return Util::show(config('code.success'),'ok');
    }
}