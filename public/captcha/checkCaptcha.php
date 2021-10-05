<?php
/**
 * 旋转验证码效验
 */
include_once($_SERVER['DOCUMENT_ROOT']."/app.php");
include_once("./function.php");
checkHttpSign();

if(!isset($_REQUEST['rotationAngle']))code("验证失败!_code_1"); //缺少角度值，不进行数据效验
if(!isset($session['captchaLogId']))checkError("需要先获取验证码！");
if(!isset($_REQUEST['mouseTrackList']) || !$_REQUEST['mouseTrackList'])code("数据不完整_code_2");
if(!isset($_REQUEST['dragUseTime']))code("数据不完整_code_3");
if(!isset($_REQUEST['dragStartTime']))code("数据不完整_code_4");

if($_REQUEST['rotationAngle'] < 0 || $_REQUEST['rotationAngle'] > 360)code("旋转角度获取失败！请重试");
$rotationAngle = (int)round(360 - $_REQUEST['rotationAngle']); //旋转角度,四舍五入

$captchaLog = easySqlSelect("*","captchaLog",[
  "id" => $session['captchaLogId'],
]);
if(!$captchaLog)checkError("验证失败！_code_1");
if($captchaLog['tryNum'] >= $captchaConfig['oneCapErrNum'])checkError("验证次数超出限制！请刷新重试"); //验证次数超出限制
if($captchaLog['addTime']+$captchaConfig['checkTimeOut'] < time())checkError("效验超时！请刷新重试"); //验证码超时时间效验

$captchaCheckOutTime = $captchaLog['addTime'] + $captchaConfig['checkTimeOut']; //验证码超时时间

$dragUseTime = (int)$_REQUEST['dragUseTime']; // 拖拽用时
if($dragUseTime > $captchaConfig['dragTimeMax'] || $dragUseTime<$captchaConfig['dragTimeMin'])checkError("验证失败！_code_5");
$dragUseTime = $dragUseTime / 1000;

$dragStartTime = (int)$_REQUEST['dragStartTime'] / 1000;
if($dragStartTime < $captchaLog['addTime'] || $dragStartTime + $dragUseTime > $captchaCheckOutTime)checkError("效验超时!请重试");

/**
 * 鼠标轨迹解析
 */
$mouseTrackList = getJson((string)$_REQUEST['mouseTrackList']);
if(!$mouseTrackList || count($mouseTrackList) < 2)checkError("鼠标轨迹获取失败！请重试");
foreach($mouseTrackList as $index=>$item){
  if(!isset($item['r']) || !isset($item['t']))checkError("效验失败_v_1");
  $item['t'] = $item['t'] / 1000; //转为秒单位
  if($item['t'] < $dragStartTime)checkError("效验失败_v_2");
  if($item['t'] > ($dragUseTime + $dragStartTime))checkError("效验失败_v_2_2");
  $lastTime = $index==0? $dragStartTime : $mouseTrackList[$index-1]['t'] / 1000;
  if($item['t'] < $lastTime + ($captchaConfig['dragInterval'] / 1000))checkError("效验失败_v_3");
  $item['r'] = (int)round($item['r']);
  if($item['r']<0 || $item['r']>100)checkError("效验失败_v_4");
}



if(!in_array($rotationAngle,getSuccessRotationAngle($captchaLog['rotationAngle']))){ //角度效验失败
  
  if(!easySqlUpdate("captchaLog",[
    "id" => $captchaLog['id'],
  ],[
    "tryNum=tryNum+1" => []
  ]))code("意外错误!_code_2");
  if($captchaLog['tryNum']+1>=$captchaConfig['oneCapErrNum'])checkError("错误次数超过限制");
  code([
    "code" => 0,
    "message" => "角度错误!请重试"
  ]);
  
}else{ //角度效验成功
  
  if(!easySqlUpdate("captchaLog",[
    "id" => $captchaLog['id'],
  ],[
    "yesTime" => time(),
    "yesUseTime" => $dragUseTime,
  ]))code("意外错误！_code_3");
  unset($session['captchaLogId']); // 删除session里储存的验证码id
  
  $session['captchaUse'] = 0; //记录验证码使用次数
  $session['captchaCheckTime'] = time(); //记录效验时间
  
  code(1); //验证成功
}


/**
 * 结束代码并返回需要刷新验证码
 */
function checkError($msg="请先完成人机效验!"):void{
  code([
    "code" => 0,
    "getNewCaptcha" => true,
    "message" => $msg
  ]);
}