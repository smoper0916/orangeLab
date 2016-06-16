<?php

header("Content-Type:text/html; charset=utf8");
include "simple_html_dom.php";
include "snoopy/Snoopy.class.php";

global $TEMP_HTML_PATH, $RESULT_HTML_PATH;
$TEMP_HTML_PATH = "./html/etc/html_009.html";
$RESULT_HTML_PATH = "./xml/result_009.xml";
$url = "http://kumoh.info/se.py";


$spy = new Snoopy();
//echo "bbb";
$spy->fetch($url);
//echo "aaa";
$txt=$spy->results;

$html_file = fopen($GLOBALS["TEMP_HTML_PATH"], "w");
fwrite($html_file, $txt."\r\n");
fclose($html_file);

$html = file_get_html($GLOBALS["TEMP_HTML_PATH"]);
//$html = file_get_html($url);

if($html) {
    //$article = $html->find('tbody[id=boardNormalList_ListBoardListVo]', 0);
    // $article = $html->find('td[class=notice]', 0);

    //echo $article[0]->outertext;

    $postArray = array();
    $article = $html->find('tr');

    foreach($article as $i){
        if($i->class == "notice"){
            echo "<p>".$i->plaintext."</p>";
        }
        else if(!$i->find('th'))
        {
            echo "<p>".$i->plaintext;
        }
        //echo $i->parent;

    }

    //array_push($this->postArray, $i);

}
else {
    echo "error document";
}
?>