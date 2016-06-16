<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-02-21
 * Time: ¿ÀÈÄ 4:08
 */

namespace orangelab;

include "simple_html_dom.php";
include "snoopy/Snoopy.class.php";
include "Post.php";
include "xml_maker.php";

global $CONTENTS_HTML_PATH, $RESULT_HTML_PATH, $RESULT_XML_PATH;
$CONTENTS_HTML_PATH = "./html/etc/html_001.html";
$RESULT_HTML_PATH = "./html/contents/html_001.html";
$RESULT_XML_PATH = "./xml/files.xml";

class PostDetail{
    public $title, $date, $contents;
}
class ContentsParser
{
    var $url;
    var $html;
    var $postArray;
    var $files;

    function setURL($url){$this->url = $url;}
    function writeLine($str){
        echo $str.'<br>';
    }
    function autosetURL($module_no, $board_no){
        $this->url = "http://kumoh.ac.kr/jsp/module/board/view01.do?conf_no=";

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
        $this->url .= "&board_no=".$board_no;
        //echo $this->url;
    }
    function snoopyFetch(){
        $spy = new \Snoopy();
        $spy->fetch($this->url);
        $txt=$spy->results;

        $html_file = fopen($GLOBALS["CONTENTS_HTML_PATH"], "w");
        fwrite($html_file, $txt."\r\n");
        fclose($html_file);

        $this->html = file_get_html($GLOBALS["CONTENTS_HTML_PATH"]);
    }
    function analyzeHtmlTags(){
        if($this->html) {
            //$article = $html->find('tbody[id=boardNormalList_ListBoardListVo]', 0);
            // $article = $html->find('td[class=notice]', 0);

            //echo $article[0]->outertext;

            $this->postArray = array();
            $p = $this->html->find('p[class=file]');

            if(count($p) != 0){
                $this->files = array();
                $this->GetFiles();
            }
            else
                $this->files = null;
            //print_r($this->files);
                //$this->writeLine("no files");
            $article = $this->html->find('div[class=view_content]')[0];

                //array_push($this->postArray, $i);
            $contents = $article->innertext;
            $contents = str_replace('<?xml:namespace prefix = o ns = "urn:schemas-microsoft-com:office:office" /><o:p></o:p>',"",$contents);
            $contents = str_replace('<?xml:namespace prefix = "o" ns = "urn:schemas-microsoft-com:office:office" /><o:p></o:p>',"",$contents);
            $contents = str_replace('<?xml:namespace prefix = o ns = "urn:schemas-microsoft-com:office:office" /><o:p><u></u></o:p>', "", $contents);
            $contents = str_replace('<?XML:NAMESPACE PREFIX = "O" /><o:p></o:p>',"",$contents);
            $html_file = fopen($GLOBALS["RESULT_HTML_PATH"], "w");
            fwrite($html_file, $contents."\r\n");
            fclose($html_file);
            $this->MakeXML();
            $this->Redirect();
        }
        else
            echo "error document";
    }
    function GetFiles(){
        //$this->writeLine("I am a boy");
        $article = $this->html->find('p[class=file]');
        foreach ($article as $item) {
            $cnt = 0;
            foreach ($item->find('a') as $j) {
                $this->writeLine($j->innertext);
                $file_url = 'http://kumoh.ac.kr'.$j->href;
                $file_name = $j->plaintext;

                $this->files[$cnt]['name'] = $file_name;
                $this->files[$cnt]['url'] = $file_url;
                $cnt++;
            }
        }
    }
    function Run()
    {
        $this->snoopyFetch();
        $this->analyzeHtmlTags();
        

    }
    function Redirect(){
        header("Location: ./html/contents/html_001.html");
        die();
    }
    function MakeXML(){
        $XmlConstruct = new \XmlConstruct('kit');

        $count = 0;
        if(is_null($this->files)){
            $XmlConstruct->text("null");
        }
        else{
            foreach($this->files as $row)
            {
                $XmlConstruct->startElement('file');
                $XmlConstruct->startAttribute('id');
                $XmlConstruct->text($count);
                $XmlConstruct->endAttribute();

                $XmlConstruct->startElement('name');
                $XmlConstruct->text($row['name']);
                $XmlConstruct->endElement();

                $XmlConstruct->startElement('url');
                $XmlConstruct->text($row['url']);
                $XmlConstruct->endElement();

                $XmlConstruct->endElement();
                $count++;
            }
        }

        $XmlConstruct->output($GLOBALS["RESULT_XML_PATH"]);
    }
}