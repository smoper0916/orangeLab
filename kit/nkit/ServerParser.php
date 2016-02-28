<?php


/**
 * Created by Sublime Text 3.
 * User: 재혁
 * Date: 2/28/2016
 * Time: 1:16 PM
 */





namespace orangelab;

header("Content-Type:text/html; charset=utf8");

include_once "simple_html_dom.php";
include_once "snoopy/Snoopy.class.php";
include_once "Post.php";


global $TEMP_HTML_PATH;
$TEMP_HTML_PATH = "./html/etc/html_003.html";


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



class ServerParser{
    var $url;
    var $html;
    var $postArray;
    var $mode;
    var $finished=false;
    var $done = false;
    var $module_no;
    var $page;

    function setMode($mode){$this->mode = $mode;}
    function setURL($url){$this->url = $url;}
    function autosetURL($module_no, $page){
        $this->url = "http://kumoh.ac.kr/jsp/module/board/list01.do?conf_no=";

        switch($module_no){
            case 0:
                $this->url .= "38";
                break;
            case 1:
                $this->url .= "130";
                break;
            case 2:
                $this->url .= "131";
                break;
            case 3:
                $this->url .= "566";
                break;
        }
        $this->url .= "&C_PAGE=" . $page;
        $this->page = $page;
        $this->module_no = $module_no;
        //echo "<p>".$this->url."</p>";
    }
    function snoopyFetch(){
        $spy = new \Snoopy();
        $spy->fetch($this->url);
        $txt=$spy->results;

        $html_file = fopen($GLOBALS["TEMP_HTML_PATH"], "w");
        fwrite($html_file, $txt."\r\n");
        fclose($html_file);

        $this->html = file_get_html($GLOBALS["TEMP_HTML_PATH"]);
    }
    function analyzeHtmlTags(){
        if($this->html) {
            //$article = $html->find('tbody[id=boardNormalList_ListBoardListVo]', 0);
            // $article = $html->find('td[class=notice]', 0);

            //echo $article[0]->outertext;


            $article = $this->html->find('tr[title]');

            foreach($article as $i){
                if(isset($i->onclick)){
                    /*
                        $onclick = $i->onclick;
                        echo "<script language='javascript'>$onclick</script>";
                        $onclick = "<script>document.write (n);</script>";
                        echo $onclick;
            */
                    //$board_no = $onclick;
                    if($this->page != 1)
                        continue;
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

                array_push($this->postArray, new Post($board_no, $this->module_no, $title, $date));
                //$db->db_query("INSERT INTO kit_board_list(board_no, module_no, title, date) VALUES('$board_no','$module_no','$title','$date');");


                //갓재혁을 찬양하라 Made By JaeHyeok
            }

            //array_push($this->postArray, $i);
            $this->done = true;

        }
        else {
            echo "error document";
            $this->done = false;
        }
    }
    function Run()
    {
        $this->postArray = array();

        for($m_no=0; $m_no<4;$m_no++) {
            for ($i = 1; $i <= 3; $i++) {
                $this->autosetURL($m_no, $i);
                $this->snoopyFetch();
                $this->analyzeHtmlTags();
            }
        }
        $this->finished = true;

        if($this->finished){
            return $this->postArray;
        }

    }
}
?>