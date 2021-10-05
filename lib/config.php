<?php
/**--------- 参数配置 ----------**/

/**
 * 数据库连接配置
 */
$DBconfig = [
  "host"    => "127.0.0.1", //数据库地址 
  "dbname"  => "", //库名称
  "user"    => "", //账号
  "pass"    => "",  //密码
  "charset" => "utf8mb4", //编码
];

$appKey = "rotateCaptcha_By_QQ_16159580639"; //项目key,请求签名时使用,用于请求md5效验,防中间人攻击篡改数据

$tokenStart = 'rotateCaptcha_'; //token存在redis的开头字符串
$tokenExpire = 86400 * 31; //token无操作后过期时间(秒)
$tokenOneIpNum = 100; //单个ip 在token有效期内允许生成多少个token
$tokenSaveDBIndex = 10; //token保存在redis哪个数据库

$nodePath = "node"; //node命令地址，默认node，权限不足时需要填写node具体安装路径


/**
 * 初始化变量
 */
$type = $_REQUEST['type']??null; //请求的type类型

$pdo = null; //初始化pdo对象
$redis = null; //redis初始化
$session = null; //初始化session

$date = date("Y-m-d H:i:s"); //当前日期

$root = $_SERVER['DOCUMENT_ROOT']; //网站运行目录 "/www/wwwroot/php"




/**
 * 旋转验证码数据配置
 */
$captchaConfig = [
  "canvasSize"    => 480,   //默认正方形验证码像素大小 
  "checkTimeOut"  => 600,   //验证码有效期
  
  "dragInterval"  => 200,   //鼠标轨迹间隔时间(ms)
  
  "dragTimeMin"   => 500,    //拖拽至少用时(ms)
  "dragTimeMax"   => 10*1000,//拖拽最多用时(ms)
  
  "errorAccuracy" => 10,     //左右误差度数允许值
  "oneCapErrNum"  => 3,     //每个验证码最多错误几次失效
  
  "ipDayAll"      => 300,   //单IP一天允许生成多少次验证码 
  "ipDayError"    => 100,   //单IP一天允许验证失败次数
  
  "ipHourAll"     => 100,   //单IP一小时内允许生成多少张验证码 
  "ipHourError"   => 30,    //单IP一小时内允许出错多少次 
  
  "randomPoint"   => 200,   //初始 随机干扰点数量 
  "randomLine"    => 50,    //初始 随机干扰线数量 
  "randomBlock"   => 3,     //初始 随机干扰矩块数量 
  
  /**
   * 动态干扰点线面 的生成规则，以IP统计
   * 请设置一个匿名函数并接收参数 $options
   * $options = [
   *  "dayAll"    => 0,   // 今天生成的验证码总数
   *  "dayError"  => 0,   // 今天验证失败总数
   *  "hourAll"   => 0,   // 一小时内验证码生成数量
   *  "hourError" => 0,   // 一小时内验证失败次数
   * ];
   * 函数返回要生成的点线面数量，该值若小于初始数量则选用初始数量
   */
  // 动态添加随机颜色的像素点
  "addPoint" => function(array $options):int{
    return 0;
  },
  // 动态添加随机颜色随机长度的线段
  "addLine"  => function(array $options):int{
    return 0;
  },
  // 动态添加随机颜色随机大小的矩形
  "addBlock" => function(array $options):int{
    return 0;
  },
  
  
  
  /**
   * 验证码效验完，允许使用的最大次数
   */
  "captchaUseMaxNum" => 3,
  /**
   * 验证码效验完，有效期(秒)
   */
  "captchaUseMaxTime" => 60*60,
  
];
