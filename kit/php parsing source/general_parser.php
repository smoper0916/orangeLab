<?php
/**
 * Created by PhpStorm.
 * User: 재혁
 * Date: 2/11/2016
 * Time: 1:49 PM
 */


header("Content-Type:text/html; charset=utf8");

include "simple_html_dom.php";
include "snoopy/Snoopy.class.php";
include "db_manager.php";
include "post.php";
include "xml_maker.php";



if(!$_GET['page']) $page = 1;
else $page = $_GET['page'];

if(!$_GET['module_no']) $module_no = 0;
else $module_no = $_GET['module_no'];

?>
<script type="text/javascript">
    var n;
    function btnView(seq){
        n = seq;
    }
</script>
<?

$url = "http://kumoh.ac.kr/jsp/module/board/list01.do?conf_no=";

switch($module_no){
    case 0:
        $url = $url."38";
        break;
    case 1:
        $url = $url."130";
        break;
    case 2:
        $url = $url."131";
        break;
    case 3:
        $url = $url."566";
        break;
}
$url = $url."&C_PAGE=".$page;


$snoopy = new Snoopy;

$snoopy->fetch($url);
$txt=$snoopy->results;

$html_file = fopen("./exam.html", "w");
fwrite($html_file, $txt."\r\n");
fclose($html_file);


$html = file_get_html("./exam.html");
//    echo $html;
//$html = file_get_html('exam.html');
if($html)
{
    //$article = $html->find('tbody[id=boardNormalList_ListBoardListVo]', 0);
    // $article = $html->find('td[class=notice]', 0);

    //echo $article[0]->outertext;

    $db = new DBManager;
    $postArray = array();
    $article = $html->find('tr[title]');

    foreach($article as $i){
        if(isset($i->onclick)){
            /*
                $onclick = $i->onclick;
                echo "<script language='javascript'>$onclick</script>";
                $onclick = "<script>document.write (n);</script>";
                echo $onclick;
    */
            //$board_no = $onclick;
            $board_no = preg_replace("/[^0-9]*/", "", $i->onclick);
            $title = $i->title;
            $date = $i->children[3]->plaintext;

        }
        else{
            //echo PHP_EOL;
            /*
            $onclick = $i->children[1]->onclick;
            echo "<script language='javascript'>$onclick</script>";
            $onclick = "<script>document.write (n);</script>";
            echo $onclick;
*/
            //$onclick = preg_replace('/btnView\(\'\);/','',$onclick);
            //echo $onclick;
            //echo $i->title;
            //$board_no = $onclick;
            $board_no = preg_replace("/[^0-9]*/", "", $i->children[1]->onclick);
            $title = $i->title;
            $date = $i->children[3]->plaintext;

        }
        $title = addslashes($title);

        array_push($postArray, new post($board_no, $module_no, $title, $date));
        //$db->db_query("INSERT INTO kit_board_list(board_no, module_no, title, date) VALUES('$board_no','$module_no','$title','$date');");


        //갓재혁을 찬양하라 Made By JaeHyeok
    }

    $XmlConstruct = new XmlConstruct('kit');

    $count = 0;
    foreach($postArray as $row)
    {
        echo ' module_no : ';
        echo $row->module_no;
        echo ' title : ';
        echo $row->title;
        echo 'date : ';
        echo $row->date;
        $XmlConstruct->startElement('post');

        $XmlConstruct->startAttribute('id');
        $XmlConstruct->text($count);
        $XmlConstruct->endAttribute();

        $XmlConstruct->startElement('board_no');
        $XmlConstruct->text($row->board_no);
        $XmlConstruct->endElement();

        $XmlConstruct->startElement('module_no');
        $XmlConstruct->text($row->module_no);
        $XmlConstruct->endElement();

        $XmlConstruct->startElement('title');
        $XmlConstruct->text($row->title);
        $XmlConstruct->endElement();

        $XmlConstruct->startElement('date');
        $XmlConstruct->text($row->date);
        $XmlConstruct->endElement();

        $XmlConstruct->endElement();
        $count++;
    }


    $XmlConstruct->output();
}
else
    echo "error document";


/*
 *
 * 모듈 자동이동은 잠시 중단
if($module_no != 3){
    $module_no = $module_no + 1;
    header("Location: ./parser.php?module_no=$module_no");
    die();
}
*/
/*
    include "snoopy/Snoopy.class.php";
   $snoopy = new Snoopy;

   if(!$_GET[page]) $page = 1;
   else $page = $_GET[page];

//	$snoopy->fetchform("http://kumoh.ac.kr/jsp/module/board/list01.do?conf_no=38&C_PAGE=".$page);
//	print $snoopy->results;
$snoopy->fetch("http://kumoh.ac.kr/jsp/module/board/list01.do?conf_no=38&C_PAGE=".$page);
$txt=$snoopy->results;
//print($txt);
$o = "";
$rex="/<tr onclick=\".*\"\>/";
preg_match_all($rex,$txt,$o);
print_r($o);
//     \<span id=\"moonseller\"\>[^>]\<\/span\>
*/
?>
