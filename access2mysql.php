<?php
//连接mysql
$dbhost="localhost";//服务器  
$db="thinkcmf";//数据库  
$dbuser="root";//用户名  
$dbpass="";//密码  
$db_qianzhui="cmf_";//表前缀  
$link=mysql_connect($dbhost,$dbuser,$dbpass);  
mysql_query("SET NAMES 'UTF8'"); 
mysql_select_db("{$db}");
//连接access
//配置环境时需要修改//配置环境时需要修改//配置环境时需要修改//配置环境时需要修改//配置环境时需要修改
$datapath="./ZKTeco/att2000.mdb";
//配置环境时需要修改//配置环境时需要修改//配置环境时需要修改//配置环境时需要修改//配置环境时需要修改
$conn = new com("ADODB.Connection");  
$connstr = "DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=". realpath($datapath);
$conn -> Open($connstr);  
$rs = new com("ADODB.RecordSet");  
$rs->Open("select * from CHECKINOUT ",$conn,1,1); 
while(! $rs->eof) {
//逐行读取数据
$userid = $rs->Fields(0);
$info = new com("ADODB.RecordSet");
$info->Open("select * from USERINFO where USERID=".$userid,$conn,1,1);
$badge_number = $info->Fields(1);
$name = iconv ('gb2312','utf-8',$info->Fields(3));
$checktime = $rs->Fields(1)->value;
$unique=$userid.$checktime;
//测试输出
// echo($userid." ".$badge_number." ".$name." ".$checktime."<br>");
//开始写入
$sql="INSERT IGNORE INTO `cmf_checkinout` (`USERID`,`badge_number`,`NAME`,`CHECKTIME`,`UNIQUE`) VALUES('{$userid}','{$badge_number}','{$name}','{$checktime}','{$unique}')";
mysql_query($sql); //执行语句！！！ 
$rs->MoveNext();
}
echo('success!');
?>