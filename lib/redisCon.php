<?php
/**
 * 连接redis
 */


/**
 * 连接 redis
 */
try {
  $redis = new Redis();
  $redis->connect('127.0.0.1', 6379);
  if (!$redis->ping()) die("redis连接失败!_01");
  $redis->select(0); //默认选择 db0
} catch (Exception $e) {
  die("redis连接失败!_02");
}



/**
 * 获取 redis 
 * $dbIndex 选择数据库 0-15
 */
function getRedis(string $key,int $dbIndex = 0){
  global $redis;
  if(!$redis)code("未知错误!_redis_01_1");
  if ($dbIndex < 0 || $dbIndex > 15) return false;
  $redis->select($dbIndex);
  $data = $redis->get($key);
  $isJson = getJson($data);
  if ($isJson) return $isJson;
  return $data;
}


/**
 * 写入 redis 
 */
function setRedis(string $key,$value, int $expire = 0, int $dbIndex = 0):bool{
  global $redis;
  if ($dbIndex < 0 || $dbIndex > 15) return false;
  if (!$key || !isset($value)) return false;
  $redis->select($dbIndex);
  if (is_array($value)) $value = setJson($value);
  if ($expire == 0) {
    global $tokenExpire; //默认到期时间
    $redis->setex($key, $tokenExpire, $value);
  } else{
    $redis->setex($key, $expire, $value);
  }
  return true;
}



/**
 * 查询指定数据库的指定key数量
 */
function countRedis(string $key,int $dbIndex = 0):int{
  global $redis;
  $redis->select($dbIndex);
  $temp = [];
  $temp = $redis->scan($temp, $key, 99999999);
  return count($temp)??0;
}



/**
 * 统计数量
 */
function setCacheCount(
  string $keyName,
  string|int $addNum=1,//需要增加的数量
  string|int $timeOut=86400, //缓存超时时间
  string|int $initNum=1, //进入时的数量
):bool{
  $addNum = (int)$addNum;
  $timeOut = (int)$timeOut;
  $initNum = (int)$initNum;
  
  $countNum = getRedis($keyName,2);
  if(!$countNum){ //初次写入
    return setRedis($keyName,$initNum,$timeOut,2);
  }
  
  return setRedis($keyName,$countNum + $addNum,$timeOut,2);
}



/**
 * 获取统计数量
 */
function getCacheCount(string $keyName):int{
  return (int)getRedis($keyName,2);
}




