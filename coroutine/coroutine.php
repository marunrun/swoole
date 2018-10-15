<?php

$http = new swoole_http_server('0.0.0.0',8811);

$http->on('request',function ($request,$response){

        $redis = new swoole\coroutine\redis();

        $res = $redis->connect('127.0.0.1',6379);

        if($res === false){
            $response->write(json_encode(['error_code' => $redis->errCode,'error_message' => $redis->errMsg]));
            return false;
        }
        //通过密码连接
        $res  = $redis->auth('mr666...');

        if($res === false){
            $response->write(json_encode(['error_code' => $redis->errCode,'error_message' => $redis->errMsg]));
            return false;
        }

        $key  = $request->get['k'];
        $res = $redis->get($key);

        if(!$res){
            $response->write(json_encode(['error_code' => $redis->errCode,'error_message' => $redis->errMsg ?: "key:'{$key}' does not exist"]));
            return false;
        }else{
            $response->write($res);
        }

});

$http->start();