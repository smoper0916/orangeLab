<?php
include_once "./db_manager.php";

use orangelab\DBManager;

/*
 * ERROR CODE
 * 101 - NO SERVICE ==> �������� �ʴ� ����
 * 103 - FAILED ==> ����
 * 777 - SUCCESS ==> �����ߴٴ� �ǹ�
 */

// ���
$table_instanceid = "kit_subscriber";
$table_keyword = "kit_keywords";
$table_wished_keyword = "kit_wished_keywords";
$table_members = "kit_members";

$task = $_GET['task'];
$dbm = new DBManager();

if($task == "register"){ //�����(instance id�� ����ϰ� ���� ���� id�� �ο��޴� �۾�)
    $instance_id = urldecode($_GET['ins_id']);
    //$instance_id = $_GET['ins_id'];
    $dbm->db_insert($table_instanceid,"NULL, '".$instance_id."'"); // ���ԿϷ�
    $value_userid = $dbm->db_query2("SELECT user_id FROM ".$table_instanceid." WHERE device_id='".$instance_id."'");
    $user_id = $value_userid[0]['user_id'];
    print(json_encode(array("code"=>"777","user_id"=>$user_id)));
    //print("777<br>");
    //print($user_id);
}
else if($task == "keyword"){ // Ű���� ��� �۾�
    $keyword_name = urldecode($_GET['keyword']);

    //���� ����Ϸ��� Ű���尡 ������ ��� �� ���� count�� ������Ű�� ȸ���� Ű���� ��Ͽ� �߰���
    $rvalue_table_keyword = $dbm->db_query2("SELECT id, ref_cnt FROM ".$table_keyword." WHERE keyword='".$keyword_name."'");
    if($rvalue_table_keyword != null){
        $ref_cnt = $rvalue_table_keyword[0]['ref_cnt'];
        $id = $rvalue_table_keyword[0]['id'];
        
        $ref_cnt++;
        // ���id �������� => �ν��Ͻ��� �̿��ؼ�
        $dbm->db_modify($table_keyword, "ref_cnt=".$ref_cnt, "id=".$id);
        $dbm->db_insert($table_wished_keyword, ", ".$id.", '".$keyword_name."'");
    }
    else{
        $dbm->db_insert($table_keyword, "NULL, '".$keyword_name."', 1");
    }

    print(json_encode(array("code"=>"777")));
}
else if($task == "member_register"){
    /*
     * 110 - id �ߺ�
     * 111 - nickname �ߺ�
     */

    $user_id = urldecode($_GET['userid']);
    $user_pw = urldecode($_GET['password']);
    $user_nick = urldecode($_GET['nick']);

    //�ߺ� üũ
    $rvalue_table_members = $dbm->db_query2("SELECT user_id FROM ".$table_members." WHERE user_id='".$user_id."'");

    if($rvalue_table_members == null){
        //�г��� �ߺ� üũ
        $rvalue_att_nick = $dbm->db_query2("SELECT count(nick) FROM ".$table_members." WHERE nick='".$user_nick."'");
        if($rvalue_att_nick[0][0] == 0){
            //���� ����
            $dbm->db_insert($table_members,"'".$user_id."', '".$user_pw."', '".$user_nick."'");
            print(json_encode(array("code"=>"777")));
        }
        else{
            //�г��� �ߺ�
            print(json_encode(array("code"=>"111")));
        }
    }
    else{
        //id �ߺ�
        print(json_encode(array("code"=>"110")));
    }
    
}
else{
    print(json_encode(array("code"=>"101")));
}
?>