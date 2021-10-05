<?php
/**
 * 配置第三方库链接
 */

/**
 * 连接 mysql数据库
 */
try {
  $pdo = new PDO('mysql:host=' . $DBconfig["host"] . ';dbname=' . $DBconfig["dbname"] . ';charset=' . $DBconfig["charset"], $DBconfig["user"], $DBconfig['pass'],[
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    PDO::ATTR_PERSISTENT => true,
  ]);
  $pdo->exec('set names '.$DBconfig['charset']);
  $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
} catch (\Throwable $th) {
  code("数据库连接失败!");
}


/**
 * sql语句封装
 * 获取sql错误: $pdo->errorInfo()
 */
function runSql(
  string $sqly, 
  $arr = [], 
  $type = "", // type=list返回多列数据
){
  global $pdo;
  if(!$pdo){
    echo "数据库未连接";
    return false;
  }
  if ($arr) {
    if (!is_array($arr)) {
      echo "SQL预处理出错 -> \$arr格式非array -> var_dump(\$arr) ->";
      var_dump($arr);
      exit;
    }
  }
  $res = $pdo->prepare($sqly);
  if ($arr) $res->execute($arr);
  else $res->execute();
  if (strpos($sqly, "COUNT(*)") > 0) { //统计
    return $res->fetchColumn();
  } else if (strpos($sqly, "SELECT") === 0) { //SELECT 查询 
    if ($type == "list") { //查询多条数据
      $rt = $res->fetchAll(\PDO::FETCH_ASSOC);
    }else{ //查询一条数据
      $rt = $res->fetch(\PDO::FETCH_ASSOC);
    }
    if(!$rt)$rt = [];
    return $rt;
  } else if (strpos($sqly, "UPDATE") === 0) { //更新 修改数据
    return $res->rowCount(); //返回受影响的行数
  } else if (strpos($sqly, "INSERT") === 0) { //插入 增加数据
    return $pdo->lastinsertid(); //返回新插入的ID
  } else if (strpos($sqly, "DELETE") === 0) { //删除数据
    return $res->rowCount(); //返回受影响的行数
  } else {
    echo "无法解析预处理语句 => " . $sqly;
    exit;
  }
}



/**
 * 替换sql预处理语句 为 正常sql语句
 */
function getSqlCode(string $sql,array $arr):string{
  foreach($arr as $item){
    $qStart = strpos($sql,"?");
    $sqls = mb_substr($sql, 0, $qStart, 'utf-8');
    $sqle = mb_substr($sql,$qStart+1,mb_strlen($sql,"utf-8"),"utf-8");
    $sql = $sqls.'"'.$item.'"'.$sqle;
  }
  return $sql;
}


/**
 * 解析where参数
 * 支持写法:
 * 1. [ "字段名"=>("值"||[a,b,c...]) ]
 * 2. [ ["字段名","判断符",("值"||[a,b,c...])] ] 
 * 3. [ "带?的sql语句"=>[a,b,c...] ]
 */
function easySqlAnalysisWhere(array $where=[]){
  $sql = ""; //拼接后的sql语句
  $data = []; //需要预处理的参数值
  $i = 0;
  foreach($where as $key=>$value){
    if(strpos($key, "?") > 0){ // "sql语句带?"=>[传参的值数组] :  "id<>?"=>["1"]
      $sql = $sql . ($i==0?(" WHERE ".$key):(" AND ".$key));
      foreach($value as $item)$data[] = $item;
    }else if(is_numeric($key) && is_array($value)){ // ["字段名","判断条件","值"]
      $value[1] = strtoupper($value[1]);
      
      if(in_array($value[1],[
        "=","<>",">","<",">=","<=","LIKE"
      ])){ //正常符号
        
        $sql = $sql . ($i==0?" WHERE ":" AND ") . $value[0] . " " . $value[1] . " " . " ? ";
        $data[] = $value[2];
        
      }else if(in_array($value[1],["NOT IN","IN"])){ // ["字段名","in",[a,b,c...]]
        $sql = $sql . ($i==0?" WHERE ":" AND ") . " $value[0] $value[1] (";
        $_i = 0;
        foreach($value[2] as $item){
          $sql = $sql . ($_i==0?"?":",?");
          $data[] = $item;
          $_i++;
        }
        $sql = $sql . ") ";
      }else if($value[1]=="BETWEEN"){ // ["字段名","BETWEEN",[a,b]]
        $sql = $sql.($i==0?" WHERE ":" AND ")." $value[0] $value[1] ? AND ? ";
        $data[] = $value[2][0];
        $data[] = $value[2][1];
      }
      
      
    }else{ // "字段名"=>"值" || "字段名称" => [a,b,c]
      if(is_array($value)){
        
        $sql = $sql . ($i==0?" WHERE ":" AND ") . " $key IN (";
        $_i = 0;
        foreach($value as $item){
          $sql = $sql . ($_i==0?"?":",?");
          $data[] = $item;
          $_i++;
        }
        $sql = $sql . ") ";
        
      }else{
        
        $sql = $sql . (($i==0)?(" WHERE $key=? "):(" AND $key=? "));
        $data[] = $value;
        
      }
    }
    $i++;
  }
  return [$sql,$data];
}




/**
 * 简易查询语句封装
 * @param array $orderBy [["字段","DESC||ASC"]]
 */
function easySqlSelect(
  string|array $values, //搜索类型
  string $tableName, //表名
  string|array $where=[], // 查询条件 [["字段名","判断条件","值"],"字段名"=>"值","sql语句带?"=>[传参的值数组]]
  string|array $orderBy=[], // 排序条件 ["字段名称"=>"排序方式","id"=>"DESC"],
  string|array $limit=["one"] //查询条数,不填或传"[1]"则只查询一条，["页码","每页行数"]则查询多行,页码从0开始
):array{
  $data = [];
  if(is_array($values)){ //获取指定字段
    $temp = "";
    foreach($values as $item){
      $temp = $temp . ($temp==""?"":",") . $item;
    }
    $values = $temp;
  }
  $sql = "SELECT $values FROM $tableName ";
  if($where){
    $aw = easySqlAnalysisWhere($where);
    $sql = $sql . $aw[0];
    $data = array_merge($data,$aw[1]);
  }
  if($orderBy){
    if(is_array($orderBy)){
      $i = 0;
      foreach($orderBy as $key=>$value){
        $value = strtoupper($value=="DESC"?"DESC":"ASC");
        $sql = $sql . ($i==0?(" ORDER BY $key $value "):(" , $key $value "));
        $i++;
      }
    }else{
      $sql = $sql . " ORDER BY ".$orderBy." ";
    }
  }
  
  $isList = false;
  if($limit && isset($limit[1])){
    $sql = $sql . " LIMIT ?,?";
    $data[] = $limit[0] * $limit[1];
    $data[] = $limit[1];
    $isList = true;
  }else if(!$limit || $limit[0]=="one"){
    $sql = $sql . " LIMIT ?";
    $data[] = 1;
  }else if($limit[0]=="all"){ //返回全部
    $isList = true;
  }else if(isset($limit[0])){
    $sql = $sql . " LIMIT ?";
    $data[] = $limit[0];
    $isList = true;
  }else{
    $sql = $sql . " LIMIT 1";
  }
  // var_dump($sql);
  // var_dump($data);
  return runSql($sql,$data,$isList?"list":"");
}


/**
 * 快速插入数据
 * @return 插入后的自增id
 */
function easySqlInsert(
  string $tableName,
  array $values=[], // ["字段名"=>"值"]
  bool $debug=false
):int{
  $sql = "INSERT INTO `$tableName` ";
  $data = [];
  $keys = "(";
  $datas = "(";
  $i = 0;
  foreach($values as $key=>$value){
    $keys = $keys . ($i==0?"":",") . "`$key`";
    $datas = $datas . ($i==0?"":",") . "?";
    $data[] = $value;
    $i++;
  }
  $sql = "$sql $keys) VALUES $datas)";
  if($debug){
    var_dump($sql);
    var_dump($data);
    var_dump(getSqlCode($sql,$data));
  }
  return runSql($sql,$data);
}

/**
 * 快捷更新表数据
 * @return 返回影响的行数
 */
function easySqlUpdate(
  string $tableName, //表名
  array $where=[], //where参数
  /**
   * 需要更新的值，支持写法
   * 1. [字段名=>值] -> ["name"=>"我是ID1111"]
   * 2. ["SQL预处理语句"=>[参数1,参数2]] -> ["jf=jf+?",[1]]
   */
  array $setValues=[], //需要更新的值
  bool $debug=false
):int{
  $sql = "UPDATE  `$tableName` ";
  $data = [];
  $i = 0;
  foreach($setValues as $key=>$value){
    $sql = $sql . ($i==0?" SET ":" , ");
    
    if(is_array($value)){ // 传入sql语句 ["id=id+?"=>[1]]
      
      $sql = $sql . $key;
      $data = array_merge($data,$value);
      
    }else{ // 键值格式 ["字段名"=>"值"]
      
      $sql = $sql." $key=? ";
      $data[] = $value;
      
    }
    $i++;
  }
  if($where){
    $aw = easySqlAnalysisWhere($where);
    $sql = $sql . $aw[0];
    $data = array_merge($data,$aw[1]);
  }
  if($debug){
    var_dump($sql);
    var_dump($data);
  }
  return runSql($sql,$data);
}

/**
 * 快速删除指定数据
 * @return 返回影响的行数
 */
function easySqlDelete(
  string $tableName, //表名
  array $where=[]
):int{
  $aw = easySqlAnalysisWhere($where);
  $sql = "DELETE FROM $tableName " . $aw[0];
  $data = $aw[1];
  return runSql($sql,$data);
}


/**
 * 统计指定数量
 */
function easySqlCount(string $tableName,array $where=[]):int{
  return (int)(easySqlSelect("count(*)",$tableName,$where)["count(*)"]);
}



/**
 * 执行事务
 * @param function $function 事务内执行的sql相关函数
 */
function easySqlTransaction($function,bool $dumpError=false):bool{
  global $pdo;
  try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();
    if($function()===false)throw new Exception("function return false");
    $pdo->commit();
    return true;
  } catch (\Throwable $th) {
    $pdo->rollBack();
    if($dumpError)var_dump($th);
    return false;
  }
}

/**
 * 拼接数组成sql需要返回的字段
 * @param array $fieldList 字段设置列表
 */
function analysisField(array $fieldList):string{
  $param = [];
  foreach ($fieldList as $fieldName => $options){
    
    if(is_callable($options))continue;
    
    if(is_numeric($fieldName) && is_string($options)){ //仅字段名称，无设置参数
      
      $param[] = $options;
      
    }else if(is_array($options) && is_string($fieldName)){
      if(isset($options["from"]) && $options["from"]){
        
        $param[] = $options["from"]. " AS ".$fieldName;
        
      }else{
        
        $param[] = $fieldName;
        
      }
    }
  }
  $sql = "";
  foreach($param as $item){
    $sql .= ($sql==""?"":" , ") . " $item ";
  }
  return $sql;
}


/**
 * 解析可排序的字段
 * @param array $fieldList 字段设置列表
 * @param array $needReplace 需要替换为空的无用字符串数组
 */
function analysisCanSortField(array $fieldList,array $needReplace=[]):array{
  $canSort = [];
  foreach ($fieldList as $fieldName => $options){
    if(
      !is_array($options)
      || !is_string($fieldName)
      || !isset($options["sort"])
      || $options["sort"]!==true
      || is_callable($options)
    )continue;
    $cleanFieldName = str_replace($needReplace,"",$fieldName);
    $canSort[$cleanFieldName] = $fieldName;
  }
  return $canSort;
}



/**
 * 解析搜索规则
 * [
 *  "search" => "搜索类型", // ["string","number","dateTime"]
 *  "search" => [
 *    "type" => "搜索类型", // ["select","string"...]
 *    "select" => [
 *      ["label" => "下拉框展示的名称","value" => "选中的值"]
 *    ],
 *    "select" => [
 *      "list" => ["label" => "下拉框展示的名称","value" => "选中的值"],
 *      "multiple" => true, //是否允许多选
 *    ]
 *  ]
 * ]
 */
function getSearchOptions(
  array $fieldList,
  bool $checkHaving=false, //返回字段是否需要加入having语句中
):array{
  $canSearch = [];
  foreach ($fieldList as $fieldName => $options){
    
    if(
      !is_string($fieldName)
      || !is_array($options)
      || !isset($options["search"])
      || is_callable($options)
    )continue;
    
    $item = [
      "prop" => $fieldName,
      "label" => $options["label"]??$fieldName,
    ];
    
    if($checkHaving){
      $item["having"] = (isset($item["from"]) && $item["from"]!="");
    }
    
    if(is_string($options["search"])){
      $item["type"] = $options["search"];
    }else if(is_array($options["search"])){
      if(!isset($options["search"]["type"]))continue;
      switch ($options["search"]["type"]){
        case 'select':
          if(!isset($options["search"]["select"]))continue 2;
          $item["type"] = "select";
          if(
            is_array($options["search"]["select"])
            && !isset($options["search"]["select"]["list"])
          ){ //简写 "select" => [["label","value"]]
            $item["select"] = [
              "list" => $options["search"]["select"]
            ];
          }else{
            $item["select"] = $options["search"]["select"];
          }
          break;
        default:
          continue 2;
          break;
      }
    }
    $canSearch[] = $item;
  }
  return $canSearch;
}


/**
 * 获取字段类型对应的搜索规则列表
 * @param string $propType "string"||"number"||"select"||"dateTime"
 */
function getSearchRuleJson(string $propType){
  return [
    /**
     * 普通字符串
     */
    "string" => ["等于","不等于","包含","不包含"],
    /**
     * 数字
     */
    "number" => ["等于","不等于","大于","大于等于","小于","小于等于"],
    /**
     * 筛选框
     */
    "select" => ["等于","不等于"],
    /**
     * 日期选择器
     */
    "dateTime" => ["等于","不等于","大于","大于等于","小于","小于等于"],
  ][$propType];
}


/**
 * 解析和效验精确搜索参数
 * 表单规则:
 * [
 *  {
 *    prop: "字段名称",
 *    rule: "符号",
 *    value: "传入的值",
 *  }
 * ]
 * @param array $searchOptions 搜索规则
 * @param array $searchData 搜索提交的表单,注意：需要获取having
 * @return array [
 *  "where" => [["条件",[预处理参数]]]
 *  "having" => [["条件",[预处理参数]]]
 * ]
 */
function checkAndAnalysisSearchData(
  array $searchOptions, // ["prop" => "字段名称","having"=>"是否加入having语句中"]
  array $searchData,
):array{
  
  $where = [];
  
  $having = [];
  
  foreach ($searchData as $index => $item){
    if(
      !is_array($item)
      || !isset($item["prop"])
      || !isset($item["rule"])
      || !isset($item["value"])
      || $item["prop"]==""
      || $item["rule"]==""
      || $item["value"]==""
    )code("精确搜索的表单数据有误!(第".($index+1)."项)");
    $propIndex = findPropIndex($searchOptions,$item["prop"]);
    if($propIndex==-1)code("字段“".$item["prop"]."”不可搜索");
    if(!isset($searchOptions[$propIndex]["type"]))code("字段(index=>".$propIndex.")配置缺少类型参数!");
    if(!in_array($item["rule"],getSearchRuleJson($searchOptions[$propIndex]["type"])))code("第".($index+1)."项,搜索规则错误!");
    
    $sqlString = "";
    $sqlData = [];
    
    if(in_array($searchOptions[$propIndex]["type"],["string","number","dateTime"])){
      
      if(in_array($item["rule"],["包含","不包含"])){
        
        $sqlString = " ".$item["prop"].($item["rule"]=="包含"?" LIKE ":" NOT LIKE ")."?";
        $sqlData[] = "%".$item["value"]."%";
        
      }else{
        $symbolList = [
          "等于" => "=",
          "不等于" => "<>",
          "大于" => ">",
          "大于等于" => ">=",
          "小于" => "<",
          "小于等于" => "<=",
        ];
        $sqlString = " ".$item["prop"].$symbolList[$item["rule"]]."?";
        $sqlData[] = $item["value"];
      }
      
    }else if($searchOptions[$propIndex]["type"]=="select"){
      
      $item["value"] = getJson($item["value"]);
      if(!$item["value"] || !is_array($item["value"]))code("请选择具体选项,第".($index+1)."项");
      if(!isset($searchOptions[$propIndex]["select"]))code("字段(index=>".$propIndex.".select)配置缺少参数!");
      if(
        isset($searchOptions[$propIndex]["select"]["multiple"]) 
        && $searchOptions[$propIndex]["select"]["multiple"]==false
      ){
        // 仅限单选
        if(count($item["value"])>1)code("第 ".($index+1)." 项仅限单选");
      }
      $sqlString = " ".$item["prop"].($item["rule"]=="等于"?" IN ":" NOT IN ");
      $sqlString = $sqlString." (";
      foreach ($item["value"] as $_index => $_item){
        if(
          !is_string($_item)
          && !is_numeric($_item)
          && !in_array($_item,array_map(function($__item){
            return $__item["value"];
          },$searchOptions[$propIndex]["select"]["list"]))
        )code("第 ".($index+1)." 项的 第 $_index 选项类型有误!");
        $sqlString = $sqlString.($_index==0?"":",")."?";
        $sqlData[] = $_item;
      }
      $sqlString = $sqlString.") ";
    }
    
    if(
      isset($searchOptions[$propIndex]["having"])
      && $searchOptions[$propIndex]["having"]===true
    ){ //字段使用了 AS ，需要将语句拼接在having内
      
      $where[] = [$sqlString,$sqlData];
      
    }else{ //默认使用WHERE语句
      
      $having[] = [$sqlString,$sqlData];
      
    }
    
    
  }
  return [
    "where" => $where,
    "having" => $having
  ];
}

/**
 * 查找搜索数据的索引
 * @return int 没找到则返回“-1”
 */
function findPropIndex(array $searchOptions,string $fieldName):int{
  $rt = -1;
  foreach ($searchOptions as $index => $item){
    if($item["prop"]==$fieldName){
      $rt = $index;
    }
  }
  return $rt;
}



/**
 * 解析还未拼接的where数组
 * $where = [
 *  ["条件",[预处理参数]]
 * ]
 * @return array ["总的WHERE语句，不包含“WHERE字符串”",[预处理参数]]
 */
function analysisWhereCondition(array $where){
  $whereSql = "";
  $whereData = [];
  foreach ($where as $index => [$sqlString,$sqlData]){
    if(!$sqlString)continue;
    $whereSql = $whereSql.($whereSql==""?"":" AND ").$sqlString;
    if(!$sqlData || !is_array($sqlData))continue;
    $whereData = array_merge($whereData,$sqlData);
  }
  return [$whereSql,$whereData];
}


/**
 * 创建一个字段列表；传入的数据将原样返回，该函数仅做提示和效验使用
 * @param array $fieldList [
 *  "字段名称" => [
 *    "from" => "来自哪张表的哪个字段,不填则不设置AS语句",
 *    "sort" => "字段是否可排序",
 *    "search" => "精确搜索的字段类型",
 *    "label" => "字段中文名称",
 *    "filter" => function($item,$index){return "value"}, //过滤器，返回结果的新内容
 *  ],
 *  "字段名称" => function,//同上filter过滤器，字段等于过滤器函数时不参与sql语句拼接
 *  
 * ]
 */
function newFieldList(array $fieldList = [
  "sqlTableField" => [
    "from" => "",//来自哪张表的哪个字段,不填则不设置AS语句
    "sort" => false,//字段是否可排序
    "search" => ""||[],//[string,number,dateTime,select]精确搜索的字段类型 
    "label" => "",//字段中文名称
    "filter" => "function"// function($item,$index){return "value";}, //过滤器，返回结果的新内容
  ],
  "notSqlField" => "function", // function($item,$index){return "value";},
  
]):array{
  return $fieldList;
}