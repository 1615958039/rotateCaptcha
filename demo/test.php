<?php
include_once("../app.php"); //载入框架
include_once("../public/captcha/function.php"); //载入验证码函数库
checkHttpSign(); //请求签名效验


/**
 * 模拟获取短信验证码场景
 */
if($type=="getSmsCode"){
  $phone = (string)($_REQUEST["phone"]??"");
  // TODO: 生产环境需要效验手机号是否正确
  
  isCheckCaptchaSuccess(true);  // 效验是否需要弹出验证码
  useCaptchaSuccessNum();  // 使用一次验证码
  
  // TODO: 这里发送验证码
  code(1,"验证码发送成功!");
}


/**
 * 简单使用
 */
else if($type=="easy"){
  isCheckCaptchaSuccess(true);  // 效验是否需要弹出验证码
  useCaptchaSuccessNum();  // 使用一次验证码
  code(1);
}