<?php

include 'xml_maker.php';
include 'db_manager.php';

/*
$s = mysql_connect("localhost", "smoper0916", "hyeok3096") or die("Failed");
print "Success";
mysql_select_db("smoper0916", $s);
	  $sql="select * from  se_current_weather_info where x=86 and y=96;";
	  print " 1 ";
	  $result=mysql_query($sql);
	  print " 2 ";
	  if($result){
	   while($row = mysql_fetch_array($result)) $total[]=$row;
	   print_r($total);
	  }else echo "데이터출력 실패";
	  
	  mysql_close($s);
	  */
	  
$db = new DBManager;
$x = $_GET[x];
$y = $_GET[y];
//$total = $db->db_select('se_current_weather_info', 'x='.$x.' and y='.$y.';');
$row = $db->db_query('select * from  se_current_weather_info where x='.$x.' and y='.$y.';');
//echo "$row[x]";
//print_r($row);
//echo "$total[0][0][0]";

$contents = array(
    'x' => $row[x],
    'y' => $row[y],
    'hour' =>  $row[hour],
	'temp' =>  $row[temp],
	'tmx' =>  $row[tmx],
	'tmn' =>  $row[tmn],
	'sky' =>  $row[sky],
	'pty' =>  $row[pty],
	'pop' =>  $row[pop],
	'ws' =>  $row[ws],
	'wd' =>  $row[wd],
	'reh' =>  $row[reh]
);

 
$XmlConstruct = new XmlConstruct('current_info');
$XmlConstruct->fromArray($contents); 
$XmlConstruct->output();
header("Location: ./result.xml");
die();

?>
