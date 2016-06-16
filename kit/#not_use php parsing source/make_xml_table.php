<?php
/**
 * Created by PhpStorm.
 * User: 재혁
 * Date: 2015-11-25
 * Time: 오후 11:06
*/

include "db_manager.php";
include 'xml_maker.php';

$db = new DBManager();
$table_name = $_GET["table"];
$file_name = $_GET["filename"];

//$total = $db->db_query2("select * from se_region_list");
$total = $db->db_query2("select * from ".$table_name);
$XmlConstruct = new XmlConstruct('table');

$count=0;
foreach($total as $row)
{
    $XmlConstruct->startElement('row');
    $XmlConstruct->startAttribute('id');
    $XmlConstruct->text($count);
    $XmlConstruct->endAttribute();

    //echo "row[0] : ".$row[0]."          row[1] :  ".$row[1]."    row[2] :  ";
    //print_r($row);
    for($i=0;$i<count($row)/2; $i++) {
        $XmlConstruct->startElement('att');
        if(!$row[$i])
            $XmlConstruct->text('NULL');
        else
            $XmlConstruct->text($row[$i]);
        $XmlConstruct->endElement();
    }
    $XmlConstruct->endElement();
    $count++;
}
$file_path = "./xml/";
if(!$_GET["filename"])
    $file_path.="table_result.xml";
else
    $file_path.=$file_name.".xml";
$XmlConstruct->output($file_path);
header("Location: ".$file_path);