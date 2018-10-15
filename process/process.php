<?php

$process = new swoole_process(function ($worker){

    $worker->exec('/home/marun/soft/php/bin/php',[__DIR__."/../server/http_server.php"]);

});

$pid = $process->start();

echo $pid.PHP_EOL;


swoole_process::wait();