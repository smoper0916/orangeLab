<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-02-21
 * Time: ¿ÀÈÄ 4:43
 */

include "ContentsParser.php";

$module_no = $_GET['module_no'];
$board_no = $_GET['board_no'];

use orangelab\ContentsParser;

if(!is_null($module_no) && !is_null($board_no)) {
    $contents_parser = new ContentsParser();
    $contents_parser->autosetURL($module_no, $board_no);
    $contents_parser->Run();
}
else{
    echo '<p>You should use this php file like this -> ViewContents.php?module_no=1&board_no=145974</p>';
    echo '<p>Made by Jaehyeok</p>';
}