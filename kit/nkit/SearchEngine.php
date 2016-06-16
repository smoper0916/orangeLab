<?php
/**
 * Created by Sublime Text 3.
 * User: 재혁
 * Date: 3/11/2016
 * Time: 6:21 PM
 */

namespace orangelab;

header("Content-Type:text/html; charset=utf8");

include "Post.php";
include "xml_maker.php";
include "db_manager.php";


global $RESULT_HTML_PATH;
$RESULT_HTML_PATH = "./xml/result_003.xml";

/*
 * Name : SearchEngine
 * Role : DB 내의 게시물의 검색을 담당합니다.
 *
*/
class SearchEngine{
    var $url;
    var $html;
    var $postArray;
    var $done = false;
    var $module_no;
    var $page;
    var $totalCnt;
    var $dbm;

    function __construct(){
    	$this->dbm = new DBManager();
    }
    function writeLine($str){
    	echo $str.'<br>';
    }
    function LoadInitialData($keyword){
    	$this->totalCnt = $this->dbm->db_query("SELECT count(*) FROM `kit_post` WHERE title like '%".$keyword."%'")[0];
    	$this->writeLine($this->totalCnt);
    }
    function MakeXML(){
        $XmlConstruct = new \XmlConstruct('kit');

        $count = 0;
        $XmlConstruct->startElement('total_count');
        $XmlConstruct->text($this->totalCnt);
		$XmlConstruct->endElement();

        foreach($this->postArray as $row)
        {
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


        $XmlConstruct->output($GLOBALS["RESULT_HTML_PATH"]);


    }
    function dbFetch($keyword){
    	$array = $this->dbm->db_query2("SELECT * FROM `kit_post` WHERE title like '%".$keyword."%'");
    	if(is_null($array)){
    		return;
    	}
    	foreach ($array as $item) {
    		array_push($this->postArray, new Post($item['post_no'], $item['module_no'], $item['title'], $item['date']));
    	}
    }
    function Run($keyword)
    {
        $this->postArray = array();
        $this->dbFetch($keyword);
        $this->MakeXML();
        $this->Redirect();

    }
    function Redirect(){
        header("Location: ".$GLOBALS["RESULT_HTML_PATH"]);
        die();
    }
}

?>