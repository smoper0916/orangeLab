<?php

include_once "./db_manager.php";

use orangelab\DBManager;

$table_login = "kit_members";
$dbm = new DBManager();

/*
 * 120 - id 존재x
 * 121 - pw 불일치
 */

$user_id = urldecode($_GET['userid']);
$user_pw = urldecode($_GET['password']);

//id 존재 체크. 존재한다면 그 아이디의 pw와 비교를 한다.
$rvalue_table_login = $dbm->db_query2("SELECT user_id FROM ".$table_login." WHERE user_id='".$user_id."'");

if($rvalue_table_login != null){
    //pw 체크
    $rvalue_att_pw = $dbm->db_query2("SELECT user_id FROM ".$table_login." WHERE user_id='".$user_id."' and pw='".$user_pw."'");
    if($rvalue_att_pw != null){
        //로그인 성공
        print(json_encode(array("code"=>"777")));
    }
    else{
        //pw 틀려서 로그인 실패
        print(json_encode(array("code"=>"121")));
    }
}
else{
    //id 존재x
    print(json_encode(array("code"=>"120")));
}
?>