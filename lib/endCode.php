<?php
/**
 * 出口函数，通过code()函数输出内容后最终会执行此函数
 */
function endCode(array $msg):void{
  global $session;
  global $tokenStart;
  global $token;
  setRedis($tokenStart . "_" . $token, $session, 0, 15); //保存session修改
  global $redis;
  $redis = null; //关闭redis链接
  die(setJson($msg)); //最终出口
}