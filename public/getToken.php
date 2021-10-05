<?php
$needSession = false; //声明无需session不效验token
/**
 * 获取token
 */
include("../app.php");
checkHttpSign();
randToken();
