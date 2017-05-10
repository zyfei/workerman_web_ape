# workerman_web_ape
使用workerman封装的一个完整的web框架，不依赖任何其他服务，比如nginx等，自己实现了静态服务，MVC封装，支持分布式部署，性能强悍，框架优化很多细节，使用非常简单。

## 不会编辑，对付看吧
## 目录结构

　_doc　　一些操作说明  
　 _lib　　workerman库文件，框架核心文件  
　_model　　数据库映射类放置地点，多模块复用，如果开启缓存，默认实现数据库find查询缓存。  
　static　　静态文件  
　z_admin　　一个后台模块例子  
　z_api　　一个api模块例子  
　config.php　　配置文件  
　filter.php　　全局路由配置  
　register.php　　workerman实现的 可插拔负载均衡服务器，简单实用  
　start_global_server.php　　缓存服务启动入口，如在配置文件开启缓存，请先启动此服务，基于GlobalData
　start.php　　项目启动入口  ， 修改代码请重启，如果是linux系统，参考workerman文档文件监控模块，实现热更新
　workerman_web_ape.sql　　此框架包含例子，请引入sql运行  
 
 
 
## 路由详解
路由:根据url找寻controller下面的类和方法  
	1-> 127.0.0.1　　找默认模块　默认控制器　里面的默认方法  
	2-> 127.0.0.1/user  找默认模块　默认控制器　里面的user方法  
	3-> 127.0.0.1/user/login  找默认模块　UserController　下面的User类里面的login方法  
	4-> 127.0.0.1/admin/admin_user/login  找admin模块(z_admin)　下面的AdminUserController　里面的login方法  

## 数据库操作详解
使用workerman提供的mysql操作类为基础，封装了find update delete count all page 等方法  
可配置 真/假删除 $softDelete=true/false  
数据库主键必须是id int类型  
如果出现修改id的情况 使用Model::update("修改后数组",原id)

## 视图
封装了简单的输出 循环 判断 include标签，具体看例子，复杂的调用请使用php语法

## 其他
因为workerman代码实例化一次并放在内存中运行  所以开发与传统php开发有些差别  
由于使用workerman为基础，请先阅读workerman手册，一些workerman注意事项本文未提出。  
参考:Navigation框架 https://github.com/xpader/Navigation  
参考:WebWorker框架 https://github.com/xtgxiso/WebWorker  
修改了ueediter部分代码，使其适应本框架

## QQ群:342016184   作者水平不高，本着学习/抛砖引玉的心态共享此框架，随着不断学习会不断优化



