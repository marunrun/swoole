<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/19
 * Time: 13:24
 */

class server
{
    const PORT = 8811;

    public function port()
    {
        swoole_timer_tick(2000,function (){
            $shell = 'netstat -tunpl| grep '.self::PORT.' | grep LISTEN |wc -l';

            $res = shell_exec($shell);

            if($res != 1){
                echo date("Y-m-d H:i:s",time()).' - swoole_server is error'.PHP_EOL;
            }else{
                echo date("Y-m-d H:i:s",time()).' - swoole_server is success'.PHP_EOL;
            }
        });
    }

}

(new server())->port();