<?php
/**
 * 常用工具函数封装
 */


/**
 * 获取客户端ip
 */
function getIp():string{
  $realip = '';
  if (isset($_SERVER)) {
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
      /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
      foreach ($arr as $ip) {
        $ip = trim($ip);
        if ($ip != 'unknown') {
          $realip = $ip;
          break;
        }
      }
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
      $realip = $_SERVER['HTTP_CLIENT_IP'];
    } else {
      if (isset($_SERVER['REMOTE_ADDR'])) {
        $realip = $_SERVER['REMOTE_ADDR'];
      } else {
        $realip = '0.0.0.0';
      }
    }
  }
  preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
  $ip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
  if ($ip == "0.0.0.0") code("无法解析您的IP地址，请更换浏览器后再试");
  return $ip;
}

/**
 * 生成16位MD5字符串
 */
function md5_16(string $str):string{
  return substr(md5($str), 8, 16);
}