# workerman_web_ape
使用workerman封装的一个完整的web框架，不依赖任何其他服务，比如nginx等，自己实现了静态服务，MVC封装，支持分布式部署，性能强悍，框架优化很多细节，使用非常简单。  
性能是传统php开发的10倍以上，传送门  http://www.workerman.net/bench  
由于所有代码都是php实现，所以可控性非常好

## Requires
PHP7 or 更高  
A POSIX compatible operating system (Linux, OSX, BSD)  
POSIX PCNTL extensions for PHP  

## 路由详解
路由:根据url找寻controller下面的类和方法  
规则 : http://路径/模块名字/类名/方法名  
```http://127.0.0.1/admin/user_mm/all 对应z_admin模块下UserMmController类all方法```  
```http://127.0.0.1/user_mm/all 对应默认模块下UserMmController类all方法```  
```http://127.0.0.1/all 对应默认模块下默认Controller类all方法```  
首先会将url中带_后面的首字母大写,然后查找对应的类和方法  


## 数据库操作详解
使用workerman提供的mysql操作类为基础，封装了find update delete count all page 等方法  
可配置 真/假删除 $softDelete=true/false  
数据库主键必须是id int类型  
如果出现修改id的情况 使用Model::update("修改后数组",原id)

## 视图
封装了简单的输出 循环 判断 include标签，具体看例子，复杂的调用请使用php语法,或者自己扩展
```
//循环输出
{foreach $n['list'] k2=>n2}
    //请使用单引号
    {$n2['name']}
    //判断
    {if $n2['id']==0}
        测试
    {/if}
{/foreach}

//包含文件
{include file="public.head"}

```

## 日志
请使用dd_log("相对于log目录的文件夹","日志内容");
```
框架会使用和http端口相同的端口创建一个udp服务，所有日志操作都是udp操作，无阻塞。
```
## 如何启动
```php start.php start  ```  
```php start.php start -d  ```  
```php start.php status  ```   
```php start.php connections```  
```php start.php stop  ```  
```php start.php restart  ```  
```php start.php reload  ```  

# Workerman性能测试
```
CPU:      Intel(R) Core(TM) i3-3220 CPU @ 3.30GHz and 4 processors totally
Memory:   8G
OS:       Ubuntu 14.04 LTS
Software: ab
PHP:      5.5.9
```

**Codes**
```php
<?php
use Workerman\Worker;
$worker = new Worker('tcp://0.0.0.0:1234');
$worker->count=3;
$worker->onMessage = function($connection, $data)
{
    $connection->send("HTTP/1.1 200 OK\r\nConnection: keep-alive\r\nServer: workerman\r\nContent-Length: 5\r\n\r\nhello");
};
Worker::runAll();
```
**Result**

```shell
ab -n1000000 -c100 -k http://127.0.0.1:1234/
This is ApacheBench, Version 2.3 <$Revision: 1528965 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 127.0.0.1 (be patient)
Completed 100000 requests
Completed 200000 requests
Completed 300000 requests
Completed 400000 requests
Completed 500000 requests
Completed 600000 requests
Completed 700000 requests
Completed 800000 requests
Completed 900000 requests
Completed 1000000 requests
Finished 1000000 requests


Server Software:        workerman/3.1.4
Server Hostname:        127.0.0.1
Server Port:            1234

Document Path:          /
Document Length:        5 bytes

Concurrency Level:      100
Time taken for tests:   7.240 seconds
Complete requests:      1000000
Failed requests:        0
Keep-Alive requests:    1000000
Total transferred:      73000000 bytes
HTML transferred:       5000000 bytes
Requests per second:    138124.14 [#/sec] (mean)
Time per request:       0.724 [ms] (mean)
Time per request:       0.007 [ms] (mean, across all concurrent requests)
Transfer rate:          9846.74 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.0      0       5
Processing:     0    1   0.2      1       9
Waiting:        0    1   0.2      1       9
Total:          0    1   0.2      1       9

Percentage of the requests served within a certain time (ms)
  50%      1
  66%      1
  75%      1
  80%      1
  90%      1
  95%      1
  98%      1
  99%      1
 100%      9 (longest request)

```
## Workerman的使用方法

中文主页:[http://www.workerman.net](http://www.workerman.net)

中文文档: [http://doc.workerman.net](http://doc.workerman.net)

Documentation:[https://github.com/walkor/workerman-manual](https://github.com/walkor/workerman-manual/blob/master/english/src/SUMMARY.md)



## 其他
因为workerman代码实例化一次并放在内存中运行  所以开发与传统php开发有些差别  
由于使用workerman为基础，请先阅读workerman手册，一些workerman注意事项本文未提出。  
参考:Navigation框架 https://github.com/xpader/Navigation  
参考:WebWorker框架 https://github.com/xtgxiso/WebWorker  
修改了ueediter部分代码，使其适应本框架


## 联系我

QQ群: 342016184   
任何人都可以通过QQ群联系到我。
