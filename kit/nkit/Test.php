<?php
/**
 * Created by PhpStorm.
 * User: 재혁
 * Date: 2016-03-03
 * Time: 오후 8:07
 */
include_once "GcmMaker.php";

use orangelab\GcmMaker;



$gcm = new GcmMaker;
$gcm->SetMessage("this is json!!!");
$gcm->Excute();

?>