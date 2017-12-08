<?php

namespace ape;

/**
 * 路由器逻辑
 */
class Router
{
    public static function router(&$data, &$map, &$access_log, &$module_name, &$controller_path, &$controller_name, &$method_name)
    {
        $config = APE['config'];
        $url = $data ["server"] ["REQUEST_URL"];
        if ($url == "/") {
            $url_arr = array();
        } else {
            $url_arr = explode("/", $url);
            $url = "/" . $url;
        }

        // 全部使用默认
        if (count($url_arr) == 0) {
            // 当前模块的路径
            ApeWeb::$MODULE_URL = HOME . $config ["default_module"] . "/";
            ApeWeb::$MODULE_NAME = "z_" . $config ["default_module"];

            $module_name = "z_" . $config ["default_module"] . DS;
            $controller_path = "controller" . DS;
            $controller_name = $config ["default_controller"];
            $method_name = $config ["default_method"];
        }
		// 如果URL是一级的话，采用默认module,默认的controller,动态method
        elseif (count($url_arr) == 1) {
            // 当前模块的路径
			ApeWeb::$MODULE_URL = HOME . $config ["default_module"] . "/";
            ApeWeb::$MODULE_NAME = "z_" . $config ["default_module"];

            $module_name = "z_" . $config ["default_module"] . DS;
            $controller_path = "controller" . DS;
            $controller_name = $config ["default_controller"];
            $method_name = $url_arr [0];
        } // 如果url为二级的话 , 动态的controller，动态的method
        elseif (count($url_arr) == 2) {
            // 当前模块的路径
            ApeWeb::$MODULE_URL = HOME . $config ["default_module"] . "/";
            ApeWeb::$MODULE_NAME = "z_" . $config ["default_module"];

            $module_name = "z_" . $config ["default_module"] . DS;
            $controller_path = "controller" . DS;
            $controller_name = ucfirst($url_arr [0]);
            // 将$controller_name里面的'_'后面的第一个字符换成大写的
            while (true) {
                if ($controller_name_index = strpos($controller_name, "_")) {
                    $controller_name = substr_replace($controller_name, strtoupper($controller_name {$controller_name_index + 1}), $controller_name_index, 2);
                } else {
                    break;
                }
            }
            $method_name = $url_arr [1];
        } // 如果url为三级,三级以上的话，不能使用默认module
        elseif (count($url_arr) > 2) {
            // 当前模块的路径
            ApeWeb::$MODULE_URL = HOME . $url_arr [0] . "/";
            ApeWeb::$MODULE_NAME = "z_" . $url_arr [0];

            $module_name = "z_" . $url_arr [0] . DS;
            $controller_path = "controller" . DS;

            for ($i = 1; $i < (count($url_arr) - 2); $i ++) {
                $controller_path = $controller_path . $url_arr [$i] . DS;
            }

            $controller_name = ucfirst($url_arr [count($url_arr) - 2]);
            // 将$controller_name里面的'_'后面的第一个字符换成大写的
            while (true) {
                if ($controller_name_index = strpos($controller_name, "_")) {
                    $controller_name = substr_replace($controller_name, strtoupper($controller_name {$controller_name_index + 1}), $controller_name_index, 2);
                } else {
                    break;
                }
            }
            $method_name = $url_arr [count($url_arr) - 1];
        }

        // 检查拦截器
        foreach ($map as $route) {
            if (stripos($url, $route [0]) === 0) {
                $filter [] = $route [1];
            }
        }

        // 循环中间件
        if (isset($filter)) {
            try {
                foreach ($filter as $cl) {
                    $cuf_bool = call_user_func($cl, $data);
                    // 如果拦截器终止了，那么退出整次解析
                    if ($cuf_bool == false) {
                        return false;
                    }
                }
            } catch (\Exception $e) {
                // Jump_exit?
                if ($e->getMessage() != 'jump_exit') {
                    $access_log [5] = $e;
                }
                $code = $e->getCode() ? $e->getCode() : 500;
                $access_log [6] = 500;
            }
        }
        return true;
    }
}
