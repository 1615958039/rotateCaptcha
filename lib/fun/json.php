<?php


/**
 * json输出数据
 * $arr = [
 *  0   => 请求失败 message 返回原因
 *  1   => 请求成功
 *  -1  => 无token需要先获取token
 *  -2  => 会话已失效，请重新登录
 */
function code(int|string|array $arr = 0, string|array $msg = ""):void{
  if (is_array($arr)) {
    if (!$arr['code']) $arr['code'] = 0;
    if (!$arr['message']) $arr['message'] = 'error';
    endCode($arr);
  } else if (!is_array($msg) && $msg != "") {
    endCode(['code' => $arr, "message" => $msg]);
  } else if (in_array($arr, [-2, -1, 0, 1]) && !is_string($arr)) {
    if (!is_array($msg)) $msg = [];
    if (isset($msg['message'])) {
      $msg['code'] = $arr;
      endCode($msg);
    } else {
      $msg['code'] = $arr;
      $msg['message'] = $arr == 0 ? 'error' : 'success';
      endCode($msg);
    }
  } else if ($msg == "") {
    endCode(["code" => 0, "message" => $arr]);
  }
}




/**
 * json_encode 二次封装
 */
function setJson(
  array $arr=[],
):string{
  if (!is_array($arr)) return "";
  return json_encode($arr, JSON_UNESCAPED_UNICODE);
}

/**
 * json_decode 二次封装
 */
function getJson(
  string|array $json,
){
  if(is_array($json))return $json;
  if (!$json) return false;
  $rt = false;
  try {
    $rt = json_decode($json, true);
  } catch (\Throwable $th) {
    var_dump($th);
  }
  if (!is_array($rt)) return false;
  return $rt;
}