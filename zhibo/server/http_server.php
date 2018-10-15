<?php

class httpServer
{

    private static $instance = null;

    private $http = null;

    private $config = [
        'worker_num' => 4,
        'enable_static_handler' => true,
        'document_root' => '/home/marun/code/demo/zhibo/public/static'
    ];


    //单例
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }



    private function __construct()
    {
        $this->http = new swoole_http_server('0.0.0.0', 8811);

        $this->http->set($this->config);
        $this->http->on('workerStart', [$this, 'onWorkerStart']);
        $this->http->on('request', [$this, 'onRequest']);
        $this->http->start();

    }

    /**
     *当开启服务的时候
     * @param $http
     * @param $worker_id
     */
    public function onWorkerStart($http, $worker_id)
    {
        // 定义应用目录
        define('APP_PATH', __DIR__ . '/../application/');
        // 加载基础文件
        require __DIR__ . '/../thinkphp/base.php';
    }


    /**
     * 当有连接进入的时候
     * @param $request
     * @param $response
     */
    public function onRequest($request, $response)
    {
        if (isset($request->server)) {
            $_SERVER= [];
            foreach ($request->server as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        if (isset($request->header)) {
            foreach ($request->header as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        if (isset($request->get)) {
            $_GET = [];
            foreach ($request->get as $k => $v) {
                $_GET[$k] = $v;
            }
        }

        if (isset($request->post)) {
            $_POST =[];
            foreach ($request->post as $k => $v) {
                $_POST[$k] = $v;
            }
        }
        ob_start();

        try {

            \think\Container::get('app', [defined('APP_PATH') ? APP_PATH : ''])
                ->run()
                ->send();

        } catch (\Exception $e) {
            echo $e;
        }

        $res = ob_get_clean();


        $response->end($res);

    }



}

$obj = httpServer::getInstance();