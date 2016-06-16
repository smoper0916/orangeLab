<?
header("Content-Type:text/html; charset=utf8");

include "simple_html_dom.php";
include "snoopy/Snoopy.class.php";
include "db_manager.php";



if(!$_GET['page']) $page = 1;
else $page = $_GET['page'];

if(!$_GET['module_no']) $module_no = 0;
else $module_no = $_GET['module_no'];


$url = "http://m.bis.gumi.go.kr/GMBIS/m/page/getBrtBusPosList.do?act=srchBrtBusPos&brtId=91&brtDirection=1&brtClass=0&menuCode=1_13";

$url = $url."&C_PAGE=".$page;


$snoopy = new Snoopy;

$snoopy->fetch($url);
$txt=$snoopy->results;

$html_file = fopen("./bus_info.html", "w");
fwrite($html_file, $txt."\r\n");
fclose($html_file);



?>
