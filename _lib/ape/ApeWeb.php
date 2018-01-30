<?php

namespace ape;

use Workerman\Worker;
use Workerman\Protocols\Http;
use ape\http\Sendfile;
use Workerman\Protocols\HttpCache;

class ApeWeb extends Worker
{
    //当前的链接
    private $conn = false;
    // 储存拦截器
    private $filter_map = array();
    private $access_log = array();
    public $on404 = "";
    public $onStart = null;
    // 每个进程的最大请求数量
    public $max_request = 0;

    // 发送静态文件方法
    private $_sendfile;
    public static $mysqls;
    public static $view;
    public static $HOME;
    public static $MODULE_URL;
    public static $MODULE_NAME;
    public static $SEND_BODY;
    public static $udp_log_client = null;

    public function __construct($socket_name, $context_option = array())
    {
        $this->_sendfile = new Sendfile();
        $this->_sendfile->useETag = true;
        $this->_sendfile->cacheControl = true;
        $this->_sendfile->use304status = true;
        // 默认session为null
        $_SESSION = null;
        parent::__construct($socket_name, $context_option);
    }

    /**
     * 添加拦截器
     */
    public function AddFilter($url, callable $callback)
    {
        if (is_callable($callback)) {
            if ($callback instanceof \Closure) {
                $callback = \Closure::bind($callback, $this, get_class());
            }
        } else {
            throw new \Exception('can not HandleFunc');
        }
        $this->map [] = array(
                $url,
                $callback
        );
    }

    /**
     * 发送404
     *
     * @param unknown $connection
     */
    private function send_404($connection)
    {
        if ($this->on404) {
            $callback = \Closure::bind($this->on404, $this, get_class());
            call_user_func($callback);
        } else {
            Http::header("HTTP/1.1 404 Not Found");
            $html = 'page not found';
            $connection->send($html);
        }
    }

    /**
     * 每次发送请求完，自动关闭连接
     */
    private function auto_close(&$conn, &$static = false)
    {
        /**
         * 如果不是访问静态
         * 如果启用session了，还没有关闭，在这里关闭
         */
        if (! $static) {
            if (HttpCache::$instance->sessionStarted) {
                HttpCache::$instance->sessionStarted = false;
                Http::sessionWriteClose();
            }
            //$conn->close ();
            if (strtolower($_SERVER ["SERVER_PROTOCOL"]) == "http/1.1") {
                if (isset($_SERVER ["HTTP_CONNECTION"])) {
                    if (strtolower($_SERVER ["HTTP_CONNECTION"]) == "close") {
                        $conn->close();
                    }
                }
            } else {
                if ($_SERVER ["HTTP_CONNECTION"] != "keep-alive") {
                    $conn->close();
                }
            }
        }
        // 在这里自定义一些统计
        $this->access_log [7] = time() - $this->access_log [7];
        // 向UDP日志服务发送日志数据
        dd_log(implode(" - ", $this->access_log), "request");
    }

    /**
     * 当有静态文件访问时
     */
    public function onStaticFile($connection, $data)
    {
        $uri = $data ["server"] ["REQUEST_URI"];
        $pos = stripos($uri, "?");
        if ($pos != false) {
            $uri = substr($uri, 0, $pos);
        }

        $file = RUN_DIR . 'public/' . $uri;
        $path = realpath($file);
        // 如果没有这个文件
        if (! $path) {
            Http::header('HTTP/1.1 400 Bad Request');
            $connection->send('<h1>400 Bad Request</h1>');
            return;
        }
        // 只允许访问public文件夹下面
        if (strpos($path, RUN_DIR . 'public/') != 0) {
            Http::header('HTTP/1.1 400 Bad Request');
            $connection->send('<h1>400 Bad Request</h1>');
            return;
        }
        Sendfile::sendFile($connection, $path);
    }

    /**
     * 获取客户端消息
     */
    public function onMessage($connection, $data)
    {
        ApeWeb::$HOME = "http://".$_SERVER['HTTP_HOST']."/";
        // 初始化SEND_BODY
        ApeWeb::$SEND_BODY = "";
        $this->access_log [0] = $_SERVER ["REMOTE_ADDR"];
        $this->access_log [1] = T(time());
        $this->access_log [2] = $_SERVER ['REQUEST_METHOD'];
        $this->access_log [3] = $_SERVER ['REQUEST_URI'];
        $this->access_log [4] = $_SERVER ['SERVER_PROTOCOL'];
        $this->access_log [5] = "NULL";
        $this->access_log [6] = 200;
        $this->access_log [7] = time();
        //这块的作用是，如果访问的是/，默认增加模块
        // if ($data ["server"] ["REQUEST_URI"]=="/") {
        //     $data ["server"] ["REQUEST_URI"] = $data ["server"] ["REQUEST_URI"] . APE['config'] ["default_module"];
        //     $_SERVER ['REQUEST_URI'] = $_SERVER ['REQUEST_URI'] . APE['config'] ["default_module"];
        // }
        // 网站根目录
        $url = $data ["server"] ["REQUEST_URI"];
        $pos = stripos($url, "?");
        if ($pos != false) {
            $url = substr($url, 0, $pos);
        }
        if ($url != "/") {
            $url = strtolower(trim($url, "/"));
        }
        $data ["server"] ["REQUEST_URL"] = $url;
        $this->conn = $connection;

        if ($url == "/") {
            $url_arr = array();
        } else {
            $url_arr = explode("/", $url);
        }
        $success = false;
        $static = false;
        // 如果是访问静态文件
        if (count($url_arr) > 0 && strpos($url_arr [(count($url_arr) - 1)], '.') != false) {
            $this->onStaticFile($connection, $data);
            $success = true;
            $static = true;
        } else {
            // 如果是访问controller,通过路由匹配控制器和方法
            $r_call = Router::router($data, $this->map, $this->access_log, $module_name, $controller_path, $controller_name, $method_name);
            // 如果通过拦截器检查
            if ($r_call) {
                $c_u_f_path = $module_name . $controller_path . $controller_name. "Controller";
                $c_u_f_path = str_replace(DS, '\\', $c_u_f_path);
                
                //将-符号改为_ , 因为PHP方法名字不支持-
                $c_u_f_path = str_replace("-", '_', $c_u_f_path);
                $method_name = str_replace("-", '_', $method_name);

                // 寻找controller,并且验证是否类中包含指定方法,找到就执行方法
                if (is_file(RUN_DIR.$module_name . $controller_path . $controller_name . "Controller.php")
                    &&method_exists('\\'.$c_u_f_path, $method_name)) {
                    $success = true;
                    //将方法名字拼接上
                    $c_u_f_path = $c_u_f_path . "::" . $method_name;
                    $f_call = null;
                    try {
                        $f_call = call_user_func($c_u_f_path, $this, $data);
                    } catch (\Exception $e) {
                        var_dump($e->getMessage());
                    }

                    // 直接发送他的返回值
                    if ($f_call !== null || ApeWeb::$SEND_BODY != "") {
                        if (is_bool($f_call)) {
                            $f_call = "";
                        }
                        if (ApeWeb::$SEND_BODY != "") {
                            $f_call = ApeWeb::$SEND_BODY . $f_call;
                        }
                        $this->send($f_call);
                    }
                }
            } else {
                // 拦截器检查没通过，不报404，拦截器自己处理
                $success = true;
                $this->send(ApeWeb::$SEND_BODY);
            }
        }

        if (! $success) {
            // 没找到路由，直接404错误
            $this->send_404($connection);
        }
        // 每次发送完消息，自动关闭连接
        $this->auto_close($connection, $static);

        // 已经处理请求数
        static $request_count = 0;
        // 如果请求数达到max_request
        if (++ $request_count >= APE['config']["max_request"] && APE['config']["max_request"] > 0) {
            Worker::stopAll();
        }
    }

    /**
     * 发送文本
     *
     * @param unknown $data
     */
    public function send($data)
    {
        $this->conn->send($data);
    }

    /**
     * worker进程启动
     */
    public function run()
    {
        // 设置当前worker是否开启监听端口复用(socket的SO_REUSEPORT选项)，默认为false，不开启。
        $this->reusePort = false;
        $this->onWorkerStart = $this->onStart;

        // 一个已实例化的 object 的方法被作为 array 传递，
        // 下标 0 包含该 object，下标 1 包含方法名。 在同一个类里可以访问 protected 和 private 方法。
        $this->onMessage = array(
                $this,
                'onMessage'
        );
        parent::run();
    }
}
