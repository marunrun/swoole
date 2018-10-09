<?php

$http  = new swoole_http_server('0.0.0.0',8811);

$http->set([
    'enable_static_handler' =>  true,
    'document_root' => '/home/marun/code/demo/www'
]);

$http->on('request',function ($request,$response){

    print_r($request->get);
    $response->cookie('run','666666666',60);
    $response->end(json_encode($request->get));
});

$http->start();