<?php
$token = getToken(); //初始化token字符串
/**
 * 初始化session，若页面不需要session则在引入app.php之前定义变量: $needSession = false;
 */
$session = (isset($needSession) && $needSession === false)?null:getSession($token); //初始化session


/**
 * 获取 redis db15 中储存的session信息
 */
function getSession(string $token):array{
  global $tokenStart;
  $notoken = ['code' => -1, 'message' => 'token失效~', "token" => $token];
  $key = $tokenStart . "_" . $token;
  if (!preg_match("/^[0-9a-f]{16}$/", $token)) code($notoken);
  $session = getRedis($key, 15);
  if (!$session) code($notoken);
  return $session;
}

/**
 * 向Session内写入数据
 */
function setSession(string $key, string $value):bool{
  global $token;
  global $session;
  global $tokenStart;
  $session[$key . ''] = $value;
  if (setRedis($tokenStart . "_" . $token, $session, 0, 15)) {
    return true;
  }
  return false;
}



/**
 * 获取token
 * 允许get post cookie或请求头内传递 token
 */
function getToken():string{
  $token = null;
  if(isset($_SERVER['HTTP_TOKEN'])){
    $token = $_SERVER['HTTP_TOKEN']; //请求头内的token优先级最高
  }else if(isset($_REQUEST['token'])){
    $token = $_REQUEST['token'];
  }
  if(!$token)$token = "";
  return $token;
}


/**
 * 随机生成 token
 */
function randToken():void{
  global $tokenExpire;
  global $tokenStart;
  global $tokenOneIpNum;
  $ip = getIp();
  
  
  $sumKey = "tokenNum_".$ip."_".date("d");
  $sumKeyDB = 10;
  
  $tokenCount = (int)getRedis($sumKey,$sumKeyDB);
  if($tokenCount > $tokenOneIpNum){
    code("访问频繁了呢!");
  }
  
  $ua = $_SERVER['HTTP_USER_AGENT'];
  $token = md5_16($ua . "_" . $ip . "_" . rand(0, 999999) . "_" . time() . "_" . rand(0, 999999) . "_" . rand(0, 999999));
  $key = $tokenStart . "_" . $token;
  $value = [
    "initTime" => time()
  ];
  if (
    setRedis($key, $value, $tokenExpire, 15)
    && setRedis($sumKey,$tokenCount+1,86400,$sumKeyDB)
  ) {
    code([
      "code" => 1,
      "message" => "success",
      "token" => $token,
      "num" => $tokenCount,
    ]);
  }
  code("生成token失败!请联系管理员处理_");
}










