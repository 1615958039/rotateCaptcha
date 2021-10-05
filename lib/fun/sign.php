<?php
/**
 * 效验请求签名是否正确
 */
function checkHttpSign():void{
  global $appKey;
  if(!isset($_SERVER['HTTP_SIGN']))code("请求错误!缺少sign参数");
  $sign = $_SERVER['HTTP_SIGN'];
  $param = array_merge($_REQUEST,[
    "appKey" => $appKey
  ]);
  ksort($param);
  $param = md5(objToUrlCode($param));
  if($sign!==$param)code("签名错误!");
}


/**
 * 对象转成url编码格式
 */
function objToUrlCode($arr) {
  $str = '';
  foreach($arr as $key=>$value)$str = $str."&".$key."=".$value;
  return $str;
}