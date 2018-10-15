<?php


echo "start".date("Y-m-d H:i:s",time()).PHP_EOL;

$urls = [
    'https://baidu.com',
    'http://sina.com',
    'http://runrun520.cn',
    'http://qq.com',
    'http://baidu.com?search=marun',
];

$workers = [];

for ($i = 0; $i < 5; $i++){
    //子进程
    $process = new swoole_process(function ($worker) use ($i,$urls){
        //curl
        $res = curl_data($urls[$i]);
        $worker->write($res.PHP_EOL);
    },true);

    $pid = $process->start();

    $workers[$pid] = $process;

}

foreach ($workers as $process){

    echo $process->read();

}


/**
 * 模拟请求
 * @param $url
 * @return bool|string
 */
function curl_data($url){
//    $result = file_get_contents($url);
    sleep(1);
    return "get success".$url.PHP_EOL;
//    return $result;
}
echo "end".date("Y-m-d H:i:s",time()).PHP_EOL;
