<?php
/**
 * 旋转验证码生成
 */
include_once($_SERVER['DOCUMENT_ROOT']."/app.php");
include_once("./function.php");

$todayStart = strtotime(date("Y-m-d 00:00:00")); //今天凌晨的时间戳
$anHourAgo = time() - (60*60*1); //一小时之前的时间戳

$capCount = runSql("SELECT 
  (SELECT count(*) FROM captchaLog WHERE addIp=? AND addTime>=?) AS dayAll,
  (SELECT count(*) FROM captchaLog WHERE addIp=? AND addTime>=? AND yesTime=0) AS dayError,
  (SELECT count(*) FROM captchaLog WHERE addIp=? AND addTime>=?) AS hourAll,
  (SELECT count(*) FROM captchaLog WHERE addIp=? AND addTime>=? AND yesTime=0) AS hourError
",[
  $ip,$todayStart,
  $ip,$todayStart,
  $ip,$anHourAgo,
  $ip,$anHourAgo,
]);
if(!$capCount || !isset($capCount["dayAll"]))code("验证码生成失败！_code_2");

if($capCount["hourError"] > $captchaConfig['ipHourError'])code("频繁验证，请一小时后再试!");
if($capCount["hourAll"] > $captchaConfig['ipHourAll'])code("频繁验证，请一小时后再试!");
if($capCount["dayError"] > $captchaConfig['ipDayError'])code("频繁验证，请明天后再试!");
if($capCount["dayAll"] > $captchaConfig['ipDayAll'])code("频繁验证，请明天再试!");

$capPointNum = max($captchaConfig["randomPoint"],$captchaConfig["addPoint"]($capCount));  //取配置参数的最大值
$capLineNum = max($captchaConfig["randomLine"],$captchaConfig["addLine"]($capCount));     //取配置参数的最大值
$capBlockNum = max($captchaConfig["randomBlock"],$captchaConfig["addBlock"]($capCount));  //取配置参数的最大值


$captchaImage = easySqlSelect("*","captchaImage",[],[
  "useNum" => "ASC",
  "id" => "DESC",
]);
if(!$captchaImage)code("验证码生成失败!_code_3");

$capImgFile = $root."/".$captchaImage['file']; // 拼接验证码原图路径
$outImg = $root."/nodejs/captcha/temp/".md5(time()."|".rand(0,999)."|".rand(0,999).$capImgFile).".png"; //验证码生成后的临时路径
$rotationAngle = (int)rand(0,360); //验证码随机旋转角度

/**
 * 拼接命令行参数
 */
$cmd = "{$nodePath} {$root}/nodejs/captcha/index.js {$capImgFile} {$outImg} {$rotationAngle} {$captchaConfig['canvasSize']} {$capPointNum} {$capLineNum} {$capBlockNum}";
$cmdEnd = str_replace(PHP_EOL, '', shell_exec($cmd));
if($cmdEnd!="success")code("生成失败!_code_1");

$imgBase64 = base64_encode(file_get_contents($outImg)); //验证码图片转base64
unlink($outImg); //删除临时验证码

$captchaLogId = easySqlInsert("captchaLog",[
  "captchaId" => $captchaImage["id"],
  "rotationAngle" => $rotationAngle,
  "addIp" => $ip,
  "addTime" => time(),
]);
if(!$captchaLogId)code("验证码生成失败！_code_4");

if(!easySqlUpdate("captchaImage",[
  "id" => $captchaImage["id"]
],[
  "useNum=useNum+1" => []
]))code("意外错误！_code_1");

$session['captchaLogId'] = $captchaLogId; //验证码logID写入session

code(1,[
  "src" => $imgBase64, //输出验证码
]);