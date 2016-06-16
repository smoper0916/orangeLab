<?php
/**
 * Created by PhpStorm.
 * User: 재혁
 * Date: 2016-03-03
 * Time: 오후 3:00
 */

namespace orangelab;


include_once "db_manager.php";

class GcmMaker
{
    var $header, $message, $registration_ids;

    function __construct()
    {
        $this->message = "";
        $this->registration_ids = array();
        $this->headers = array(
            'Content-Type:application/json',
            'Authorization:key=AIzaSyBObFgeoE0ja8OBSSkyqThpW_Kdzj0jGIc');
    }

    function SetMessage($message){
        $this->message = $message;
    }
    function SetRegIDs($regIDs){$this->registration_ids = $regIDs;}
    function Excute(){
        /*
        $arr   = array();
        $arr['data'] = array();
        $arr['data']['msg'] = "gcm으로 보낼 메시지를 쓰면 된다.";
        $arr['registration_ids'] = array();
        */


        //$this->registration_ids[0] = "eESUZq2Ysvs:APA91bEw1SvpBsNt1E9aUdJcqz9vtsQcw-msePPmfdUlrlpDYf82yE4lrM7o52GrNk_xZyvO1OcFrqPCN4On3oSmOoHvSaGL_lD5NRTxMNf_ycNlQfIHxszZVfSXv9uff4PzGmT1wxKD";

        $fields = array(
            'registration_ids' => $this->registration_ids,
            'data' => array( "message" => $this->message ),
        );
        //가입 시 device->server로 자신의 아이디를 보내줌
        //그걸 DB에서 저장함.
        //이 배열은 DB에서 그 값을 불러옴

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $response = curl_exec($ch);
        //echo $response;
        curl_close($ch);

    }
}
?>