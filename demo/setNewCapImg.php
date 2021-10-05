<?php
/**
 * 载入 ./tempCaptchaImage 内的图片 到 验证码图片数据库
 */
$needSession = false;
include_once("../app.php");

// file/captchaImage/


$saveSuccess = 0; //保存成功的数量
$saveErrorImgs = []; //保存失败的图片路径数组


$saveImagePath = "../file/captchaImage/" . date("Ymd") . "/";
if(!is_dir($saveImagePath))if(!mkdir($saveImagePath,"0755",true))code("无目录权限！");

/**
 * 获取文件列表
 */
foreach (getDirList("./tempCaptchaImage")['file'] as $imagePath){
  
  $imgType = explode(".",$imagePath)[count(explode(".",$imagePath)) - 1];
  $imgMd5 = md5_file($imagePath);
  $imgName = md5($imgMd5."_".time()."_".rand(10,99999)."_".$imgType) . "." . $imgType;
  $imgSavePath = $saveImagePath . $imgName;
  $imgRelativePath = str_replace("../","",$imgSavePath);
  if(!easySqlSelect("*","captchaImage",["imgMd5" => $imgMd5])){
    
    easySqlInsert("captchaImage",[
      "file" => $imgRelativePath,
      "addTime" => time(),
      "imgMd5" => $imgMd5,
    ]);
    copy($imagePath,$imgSavePath);
    unlink($imagePath);
    $saveSuccess++;
    
  }else{
    
    $saveErrorImgs[] = "图片已存在数据库!md5=" . $imgMd5 . "图片路径:" . $imagePath;
    
  }
  
}



var_dump($saveErrorImgs);
die("脚本运行结束! _ 插入成功".$saveSuccess."张图片!");









/**
 * 获取自动目录的文件夹和文件列表
 */
function getDirList(string $path):array{
  $temp = scandir($path);
  $fileList = [];
  $dirList = [];
  foreach ($temp as $v) {
    $a = $path . "/" . $v;
    if (is_dir($a)) {
      if ($v == '.' || $v == '..')continue;
      $dirList[] = $a;
      $rt = getDirList($a);
      $fileList = array_merge($fileList,$rt['file']);
      $dirList = array_merge($dirList,$rt['dir']);
    } else {
      $fileList[] = $a;
    }
  }
  return [
    "file" => $fileList,
    "dir" => $dirList
  ];
}