<?php
include_once("../app.php"); //载入框架
checkHttpSign(); //请求签名效验


/**
 * 清空验证记录
 * 仅测试使用，生产环境勿使用
 */
easySqlDelete("captchaLog",[
  "addIp" => getIp(),
]);
$session["captchaCheckTime"] = 0;
code(1);
