<?
$datapath="att2000.mdb"; 
$conn = new com("ADODB.Connection");  
$connstr = "DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=". realpath($datapath);
$conn -> Open($connstr);  
$rs = new com("ADODB.RecordSet");  
$rs->Open("select * from CHECKINOUT ",$conn,1,1); 

?>