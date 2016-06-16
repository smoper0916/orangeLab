<?php
/**
 * Created by Sublime Text 3.
 * User: 재혁
 * Date: 3/11/2016
 * Time: 9:59 PM
 */

include "SearchEngine.php";

use orangelab\SearchEngine;

$keyword = $_GET['keyword'];

if(is_null($_GET['keyword'])){
    echo '<p>You should use this php file like this -> ViewSearchResult.php?keyword=금오</p>';
    echo '<p>Made by Jaehyeok</p>';
}
else {
    $gp = new SearchEngine();
    $gp->LoadInitialData($keyword);
    $gp->Run($keyword);
}
?>