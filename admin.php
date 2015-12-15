<?php
/**
 * Created by PhpStorm.
 * User: geeth
 * Date: 2015/11/7 0007
 * Time: 12:07
 */
// 检测PHP环境
if (version_compare(PHP_VERSION, '5.3.0', '<')) die('require PHP > 5.3.0 !');


define('BIND_MODULE','Admin');
// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', true);
// 定义应用目录
define('PUB_PATH', './Public/');
define('UPL_PATH', './Uploads/');
define('APP_PATH', './Application/');
//define('BIND_MODULE', 'Admin');
require './ThinkPHP/ThinkPHP.php';