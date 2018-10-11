<?php

$server = new swoole_websocket_server("0.0.0.0", 8812);

    $server->set([
        'enable_static_handler' =>  true,
        'document_root' => '/home/marun/code/demo/www'
    ]);


$server->on('open', 'onOpen');
$server->on('message','onMessage');
$server->on('close', 'onClose');
/**
 * 监听连接打开事件
 * @param $server
 * @param $request
 */
function onOpen ($server , $request){
    var_dump($request->fd);
    if($request->fd == 1){
        swoole_timer_tick(2000,function($timer_id){
                echo "2s , timerId :{$timer_id}";
        });
    }
}

/**
 * 监听接收消息事件
 * @param $server
 * @param $frame
 */
function onMessage ($server, $frame){

    $server->push($frame->fd,date("Y-m-d H:i:s",time())." data: ".$frame->data." finish:".$frame->finish." fd: ".$frame->fd." opcode: ".$frame->opcode);
}

function onClose($server, $fd ,$reactorId){

    foreach ($server->connections as $v){
        if($fd != $v){
            $server->push($v,"fd {$fd} is closed");
        }

    }

}


$server->start();