<?php
//连接 swoole tcp服务端
$client = new swoole_client(SWOOLE_SOCK_TCP);


//连接到服务器
if(!$client->connect('127.0.0.1',9501)){
    echo '连接失败';
    exit;
}

//php cli模式下
fwrite(STDOUT,"请输入消息: ");
$msg = trim(fgets(STDIN));


//向服务器发送数据
if (!$client->send($msg))
{
    echo '发送失败';exit;
}

//从服务器接收数据
$data = $client->recv();

if (!$data)
{
    echo '接收失败';exit;
}

echo $data."\n";

//关闭连接
$client->close();