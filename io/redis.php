<?php

$option = [
    'password'  => 'mr666..'
];

$redis = new swoole_redis($option);

$redis->on('message',function (){

});


$redis->on('close',function (){

});



$redis->connect('127.0.0.1','6379',function ($redis,$result){

    if($result === false){
        echo "error_code = ".$redis->errCode.PHP_EOL;
        echo "error_message = ".$redis->errMsg.PHP_EOL;
        return false;
    }


    $redis->set('marun',time(),function ($redis,$result){
            var_dump($result);
    });
    $redis->keys("*",function ($redis,$result){
        var_dump($result);
    });

    $redis->close();
});

