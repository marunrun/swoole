<?php

//创建Server对象，监听 127.0.0.1:9502端口，类型为SWOOLE_SOCK_UDP
$serv = new swoole_server("127.0.0.1", 9502, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);

//设置参数
$serv->set([
    'worker_number'  =>5,   //worker的进程数
    'max_request'    =>5000 //一个进程可以处理用户数
]);

//监听数据接收事件
$serv->on('packet',function ($serv,$data,$client_info){
        $serv->sendto($client_info['address'],$client_info['port'],$data,$client_info['server_socket']);
        print_r($client_info);
});

//启动服务器
$serv->start();