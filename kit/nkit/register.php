<?php
include_once "./db_manager.php";

use orangelab\DBManager;

/*
 * ERROR CODE
 * 101 - NO SERVICE ==> 지원하지 않는 서비스
 * 103 - FAILED ==> 실패
 * 777 - SUCCESS ==> 성공했다는 의미
 */

// 상수
$table_instanceid = "kit_subscriber";
$table_keyword = "kit_keywords";
$table_wished_keyword = "kit_wished_keywords";
$table_members = "kit_members";

$task = $_GET['task'];
$dbm = new DBManager();

if($task == "register"){ //기기등록(instance id를 등록하고 서버 상의 id를 부여받는 작업)
    $instance_id = urldecode($_GET['ins_id']);
    //$instance_id = $_GET['ins_id'];
    $dbm->db_insert($table_instanceid,"NULL, '".$instance_id."'"); // 가입완료
    $value_userid = $dbm->db_query2("SELECT user_id FROM ".$table_instanceid." WHERE device_id='".$instance_id."'");
    $user_id = $value_userid[0]['user_id'];
    print(json_encode(array("code"=>"777","user_id"=>$user_id)));
    //print("777<br>");
    //print($user_id);
}
else if($task == "keyword"){ // 키워드 등록 작업
    $keyword_name = urldecode($_GET['keyword']);

    //내가 등록하려던 키워드가 존재할 경우 그 참조 count만 증가시키고 회원별 키워드 목록에 추가함
    $rvalue_table_keyword = $dbm->db_query2("SELECT id, ref_cnt FROM ".$table_keyword." WHERE keyword='".$keyword_name."'");
    if($rvalue_table_keyword != null){
        $ref_cnt = $rvalue_table_keyword[0]['ref_cnt'];
        $id = $rvalue_table_keyword[0]['id'];
        
        $ref_cnt++;
        // 멤버id 가져오기 => 인스턴스를 이용해서
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
     * 110 - id 중복
     * 111 - nickname 중복
     */

    $user_id = urldecode($_GET['userid']);
    $user_pw = urldecode($_GET['password']);
    $user_nick = urldecode($_GET['nick']);

    //중복 체크
    $rvalue_table_members = $dbm->db_query2("SELECT user_id FROM ".$table_members." WHERE user_id='".$user_id."'");

    if($rvalue_table_members == null){
        //닉네임 중복 체크
        $rvalue_att_nick = $dbm->db_query2("SELECT count(nick) FROM ".$table_members." WHERE nick='".$user_nick."'");
        if($rvalue_att_nick[0][0] == 0){
            //가입 성공
            $dbm->db_insert($table_members,"'".$user_id."', '".$user_pw."', '".$user_nick."'");
            print(json_encode(array("code"=>"777")));
        }
        else{
            //닉네임 중복
            print(json_encode(array("code"=>"111")));
        }
    }
    else{
        //id 중복
        print(json_encode(array("code"=>"110")));
    }
    
}
else{
    print(json_encode(array("code"=>"101")));
}
?>