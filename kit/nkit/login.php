<?php

include_once "./db_manager.php";

use orangelab\DBManager;

$table_login = "kit_members";
$dbm = new DBManager();

/*
 * 120 - id ����x
 * 121 - pw ����ġ
 */

$user_id = urldecode($_GET['userid']);
$user_pw = urldecode($_GET['password']);

//id ���� üũ. �����Ѵٸ� �� ���̵��� pw�� �񱳸� �Ѵ�.
$rvalue_table_login = $dbm->db_query2("SELECT user_id FROM ".$table_login." WHERE user_id='".$user_id."'");

if($rvalue_table_login != null){
    //pw üũ
    $rvalue_att_pw = $dbm->db_query2("SELECT user_id FROM ".$table_login." WHERE user_id='".$user_id."' and pw='".$user_pw."'");
    if($rvalue_att_pw != null){
        //�α��� ����
        print(json_encode(array("code"=>"777")));
    }
    else{
        //pw Ʋ���� �α��� ����
        print(json_encode(array("code"=>"121")));
    }
}
else{
    //id ����x
    print(json_encode(array("code"=>"120")));
}
?>