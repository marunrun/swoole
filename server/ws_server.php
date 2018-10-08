<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-09-02
 * Time: 18:11
 */

//创建websocket服务器对象，监听0.0.0.0:9502端口
$ws = new swoole_websocket_server("0.0.0.0", 8811);

$ws->set([
    'enable_static_handler'     => true, //开启静态页面
    'document_root'             => "/home/marun/code/demo/www"  //设置静态页面的根目录
]);

//监听WebSocket连接打开事件
$ws->on('open', function ($ws, $request) {
    var_dump($request->fd, $request->get, $request->server);
    $ws->push($request->fd, "hello, welcome\n");
});

//监听WebSocket消息事件
$ws->on('message', function ($ws, $frame) {
    echo "Message: {$frame->data}\n";
    $ws->push($frame->fd, "server: {$frame->data}");
});

//监听WebSocket连接关闭事件
$ws->on('close', function ($ws, $fd) {
    echo "client {$fd} is closed\n";
});

$ws->start();