<?php

$http  = new swoole_http_server('0.0.0.0',8811);

$http->set([
    'enable_static_handler' =>  true,
    'document_root' => '/home/marun/code/demo/www'
]);

$http->on('request',function ($request,$response){

    $content =  [
        'date: '    => date("Y-m-d H:i:s",time()),
        "get: "     => $request->get,
        "post: "    => $request->post,
        "header: "  => $request->header
    ];


    swoole_async_writefile(__DIR__ . "/" .date("Y-m-d",time()).".log",json_encode($content).PHP_EOL,function ($filename){
        echo "write ok".PHP_EOL;
    },FILE_APPEND);


    $response->end(json_encode($request->get));

});

$http->start();