<?php
include_once "./db_manager.php";

use orangelab\DBManager;

$msg = urldecode($_GET["msg"]);

if(!$_GET["msg"]){
	echo "NULL!!";
}
else{
	$dbm = new DBManager();
	$dbm->db_insert("kit_feedback", "NULL, "."'$msg'".", CURRENT_TIMESTAMP");
	echo "SUCCESS";
}
?>