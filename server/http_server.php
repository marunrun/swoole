<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-09-02
 * Time: 17:08
 */

//将host设置为0.0.0.0之后 可以通过外网加端口号来访问这个页面
$http = new swoole_http_server('0.0.0.0',8811);
/**
 *设置document_root并设置enable_static_handler为true后，底层收到Http请求会先判断document_root路径下是否存在此文件，如果存在会直接发送文件内容给客户端，不再触发onRequest回调。
 */
$http->set([
    'enable_static_handler'     => true, //开启静态页面
    'document_root'             => "/home/marun/code/demo/www"  //设置静态页面的根目录
]);

$http->on('request',function ($request , $response){
    //$request对象里可以有get post 等等
    //使用$response向浏览器发送响应数据,只能返回字符串格式
    $response->end(json_encode($request->get));
    //$response->end("<h1>Hello World</h1>");
});

$http->start();