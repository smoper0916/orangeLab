<?

include "simple_html_dom.php";

if(!$_GET['page']) $page = 1;
else $page = $_GET['page'];


//	$text = file_get_contents("http://kumoh.ac.kr/");
//	echo $text;


//$html = file_get_html("http://kumoh.ac.kr/jsp/module/board/list01.do?conf_no=38&C_PAGE=".$page);
//    echo $html;    
$html = file_get_html('exam.html');
if($html)
{


    ?>


    <?
    //$article = $html->find('tbody[id=boardNormalList_ListBoardListVo]', 0);
   // $article = $html->find('td[class=notice]', 0);
    $article = $html->find('tr[title]');
//echo $article[0]->outertext;


foreach($article as $i){
	if(!isset($i->onclick)){
//echo $i->outertext;
echo ''.PHP_EOL;
$onclick = $i->children[1]->onclick;
$onclick = preg_replace('/btnView\(\'\);/','',$onclick);
echo $onclick;
echo $i->title;
}

}


       //$children = $article->firstChild();
       // echo $children;
//print_r($article);
    ?>

    <?
}
else
    echo "error document";

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
