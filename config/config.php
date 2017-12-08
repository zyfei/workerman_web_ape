<?php
return array(
        "port" => "8080",
        "home" => "http://127.0.0.1/",
        // session模式 file存文件 database存入数据库
        "session_type" => "database",
        "session_name" => "ape_session",
        // 进程名字
        "worker_name" => "worker_ape",
        // 开启多少个进程,windows下只能开启一个
        "worker_count" => 1,
        // 每个进程最多接待多少个访客
        "max_request" => 10000,
        // 默认controller
        "default_module" => "api",
        // 默认controller
        "default_controller" => "Test",
        // 默认方法
        "default_method" => "test1",
        // 系统日志文件位置,相对与根目录下的log目录
        "logFile" => "info.log",
        // 重定向标准输出，即将所有echo、var_dump等终端输出写到对应文件中
        // 注意 此参数只有在以守护进程方式运行时有效
        "stdoutFile" => "echo.log"
);
