<?php

/**
 *  SWOOLE_SOCK_UDP 代表UDP模式
 */
$client = new swoole_client(SWOOLE_SOCK_UDP);

//连接  与服务端的host和poet一致
if(!$client->connect('127.0.0.1',9502)){
    echo '连接失败';
    die;
}

/**
 * PHP  CLi模式下的一个常量
 */
fwrite(STDOUT,'请输入消息: '); //输入消息
$msg = trim(fgets(STDIN)); //返回值就是输入的消息

//发送消息给 tcp 服务端
$client->send($msg);

//接收来自server服务端的返回消息
$res = $client->recv();
echo $res."\n";