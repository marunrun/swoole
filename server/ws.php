<?php

/**
 * WebSocket基础类
 * Class ws
 */
class ws
{
    const HOST = '0.0.0.0';
    const PORT = '8812';

    private static $instance = null ;

    private  $server  = null ;

    private function __construct()
    {
        $this->server = new swoole_websocket_server(self::HOST,self::PORT);
        $this->set($this->server);
        $this->server->on('open',[$this,'onOpen']);
        $this->server->on('message',[$this,'onMessage']);
        $this->server->on('close',[$this,'onClose']);
        $this->server->on('task',[$this,'onTask']);
        $this->server->on('finish',[$this,'onFinish']);
        $this->server->start();
    }

    public static function getInstance()
    {
        if(self::$instance == null){
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * 设置swoole的配置信息
     * @param $server
     */
    public function set($server)
    {
        $server->set([
            'worker_num'        => 2,
            'task_worker_num'   => 4    //在使用task任务的时候,必须设置
        ]);
    }

    /**
     * 监听接收数据事件
     * @param $server
     * @param $frame
     */
    public function onMessage($server, $frame)
    {
        $data = [
            'task'  =>1,
            'fd'    =>$frame->fd
        ];

        swoole_timer_after(5000,function () use($server , $frame){
                echo "5s after  \n";
                $server->push($frame->fd,"5s after".date("Y-m-d H:i:s",time()));
        });


        $server->task($data);

        $server->push($frame->fd,date("Y-m-d H:i:s",time()) . " fd : {$frame->fd}");
    }

    /**
     * 监听客户端连接事件
     * @param $server
     * @param $request     */
    public function onOpen($server , $request)
    {
        if($request->fd == 1){

            $timer_id = swoole_timer_tick(2000,function($timer_id) use($request){
                echo "fd {$request->fd }: 2s , timerId :{$timer_id} \n";
            });

            //延时器 10s 之后清除上面的定时器
            swoole_timer_after(10000,function () use($timer_id){
                echo "10s later , the timer: {$timer_id} is cleared \n";
                swoole_timer_clear($timer_id);
            });

        }

        if($request->fd == 2){
            $timerId = swoole_timer_tick(2000,function ($timer_id) use($request){
                echo "fd {$request->fd }: 2s , timerId :{$timer_id} \n";
            });

        }
        @var_dump($timer_id);
        @var_dump($timerId);

    }

    /**
     * 监听客户端连接关闭事件
     * @param $server
     * @param $fd
     * @param $reactorId
     */
    public function onClose($server , $fd , $reactorId)
    {
        foreach ($server->connections as $v){
            if($fd != $v) {
                $server->push($v, "fd {$fd} is closed");
            }
        }
    }


    public function onTask($server,$task_id,$src_worker_id,$data)
    {
        sleep(10);

        if($server->exist($data['fd'])){
            $server->push($data['fd'],date("Y-m-d H:i:s",time()));
            return "on task finished"; //告诉 worker 进程,当前任务完成
        }else{
            return "the fd : {$data['fd']} is closed";
        }

    }

    public function onFinish($server , $task_id , $data)
    {
        echo $data."\n";
    }


}

$obj = ws::getInstance();