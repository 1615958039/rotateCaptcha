<?php

/**
 * 判断当前用户是否已完成验证码效验，且在验证码有效期内
 */
function isCheckCaptchaSuccess(
  $endCode=false,//直接结束代码，返回需要效验验证码
):bool{
  global $session;
  global $captchaConfig;
  if(
    !isset($session['captchaUse'])
    || !isset($session["captchaCheckTime"])
    || $session['captchaUse'] >= $captchaConfig["captchaUseMaxNum"]
    || $session["captchaCheckTime"] + $captchaConfig["captchaUseMaxTime"] < time()
  ){
    if($endCode)code(0,[
      "message" => "请先完成人机效验!",
      "needCaptcha" => true,
    ]);
    return false;
  }
  
  return true;
}


/**
 * 使用一次验证码有效数量
 */
function useCaptchaSuccessNum($userNum=1):bool{
  global $session;
  if(!isCheckCaptchaSuccess())return false;
  $session["captchaUse"] = $session["captchaUse"] + $userNum;
  return true;
}








/**
 * 获取可通过的角度列表
 */
function getSuccessRotationAngle(int $rotationAngle):array{
  global $captchaConfig;
  $yesArray = [$rotationAngle];
  for ($i=0; $i < $captchaConfig['errorAccuracy']; $i++) { 
    $yesArray[] = $rotationAngle - ($i+1);
    $yesArray[] = $rotationAngle + ($i+1);
  }
  foreach($yesArray as $index => $value){
    if($value<0){
      $yesArray[$index] = 360 + $value;
    }else if($value > 360){
      $yesArray[$index] = $value - 360;
    }
  }
  if(in_array(0,$yesArray) && !in_array(360,$yesArray)){
    $yesArray[] = 360;
  }
  if(!in_array(0,$yesArray) && in_array(360,$yesArray)){
    $yesArray[] = 0;
  }
  return $yesArray;
}