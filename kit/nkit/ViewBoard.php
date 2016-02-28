<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-02-21
 * Time: ¿ÀÈÄ 9:52
 */
include "GeneralParser.php";

use orangelab\GeneralParser;

$page = $_GET['page'];

if(is_null($_GET['module_no'])){
    echo '<p>You should use this php file like this -> ViewBoard.php?module_no=1&C_PAGE=2</p>';
    echo '<p>Made by Jaehyeok</p>';
}
else {
    $module_no = $_GET['module_no'];
    $gp = new GeneralParser();
    $gp->autosetURL($module_no, $page);
    $gp->Run();
}
?>