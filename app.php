<?php
/**
 * 入口文件，每个api接口文件都需要引入
 */


// error_reporting(0); //关闭报错
// ignore_user_abort(true);//客户端断开不影响php继续执行
// set_time_limit(3600);//脚本超时时间
date_default_timezone_set("Asia/Shanghai"); //定义当前时区为上海时间
header("Content-Type: text/html; charset=utf-8"); //设置输出编码
if(isset($_SERVER['HTTP_ORIGIN'])){ //判断是否需要处理跨域请求
  header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']); //允许任何域名跨域访问
  header('Access-Control-Allow-Methods: POST,GET,OPTIONS');
  header("Access-Control-Max-Age: ".(86400*31));
  header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with,Origin,Token,Sign');
}
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') exit; //跨域询问，直接退出执行

require_once(__DIR__."/lib/config.php"); //载入配置文件，初始化配置参数

require_once(__DIR__."/lib/redisCon.php"); //连接redis
require_once(__DIR__."/lib/endCode.php"); //出口函数,通过code()结束代码后执行的函数
require_once(__DIR__."/lib/fun/json.php");    //json相关函数封装

require_once(__DIR__."/lib/fun/other.php");   //其余工具函数库
require_once(__DIR__."/lib/fun/sign.php");    //http请求签名相关的效验函数



require_once(__DIR__."/lib/mysqlCon.php"); //连接mysql

require_once(__DIR__."/lib/session.php"); //session和token相关工具函数,session依赖redis所以要注意顺序



/**
 * 全局变量
 */

$ip = getIp(); //当前用户IP





