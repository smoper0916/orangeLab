<?php


/**
 * Created by Sublime Text 3.
 * User: 재혁
 * Date: 2/28/2016
 * Time: 1:24 PM
 */





namespace orangelab;

header("Content-Type:text/html; charset=utf8");

include_once "db_manager.php";
include_once "Post.php";
include_once "xml_maker.php";
include_once "ServerParser.php";
include_once "GcmMaker.php";

// 1. DB에 접속하여 키워드 개수를 체크합니다. 키워드가 0일 경우 종료합니다.
// 2. Server Parser에 파싱하라고 시켜서 파싱한 Data를 받아옵니다.
// 3. DB에 있는 Data와 현재 파싱한 Data를 비교합니다.
// 4. 이 중에 새로운 Data가 있으면 그것들을 따로 모아놓습니다.
// 5. foreach 문을 돌려서 한 키워드마다 체크를 합니다. 그 키워드를 해당 게시글이 포함하고 있는지 체크합니다. 
// 6. 만약 포함한다면, 그 키워드를 신청한 사용자들에게 푸시 메시지를 보냅니다.
// 7. 모든 작업이 끝나고 새롭게 추가된 게시글들을 모두 DB에 삽입합니다.
// 8. 새로 삽입된 게시글의 수 만큼 DB에서 가장 오래된 Data들을 삭제합니다. 만약 DB에 아무 Data가 없으면, 아무것도 삭제하지 않습니다.
// 9. 종료합니다.
class DetactingMachine
{
	var $postArray = array();
	var $dbManager;

	function __construct()
	{
		$this->dbManager = new DBManager();
	}

	function Run(){
		//1
		$keywords = $this->GetDB_Keywords();
		if(is_null($keywords)){
			echo 'SUCCESS';
		}
		else{
			//2
			$sParser = new ServerParser();
			$this->postArray = $sParser->Run();

			//3
			$new_post = array();
			$dbinside_post = $this->GetDB_Post();
			if(is_null($dbinside_post)){
				//db에 데이터가 없으면 현재 파싱한 데이터를 모두 삽입합니다.
				$new_post = $this->postArray;
			}
			else{
				//db 데이터와 비교해 새로운 데이터를 걸러냅니다.
				$count = count($dbinside_post);
				foreach($this->postArray as $row){
					$no_duple = true;
					for($i =0; $i<$count; $i++){
						if($row->board_no == $dbinside_post[$i]['post_no']){
							//echo "<br>".$row->board_no;
							//echo "<br>".$dbinside_post[$i]['post_no'];
							$no_duple = false;
						}
					}
					if($no_duple)
						array_push($new_post, $row);
				}
			}
			//print_r($new_post);

			//new Post가 구해졌다.
			//5
			if(count($new_post) > 0) {
				foreach ($keywords as $k) {
					foreach ($new_post as $p) {
						$title = $p->title;
						if (strpos($title, $k['keyword']) !== false) {
							//6
							//echo "<p>반갑습니다. " . $k['keyword'] . "</p>";
							//6-1 해당 키워드를 신청한 유저 목록을 받는다.
							$total = $this->dbManager->db_select_full("kit_wished_keywords", "m_id, date", "keyword=".$k['keyword']);

							//6-2 유저들의 Device Token을 받아온다.
							$regIDs = array();

							//$user_cnt = count($total);
							foreach($total as $item) {
								$device_id = $this->dbManager->db_select_full("kit_subscriber", "device_id", "user_id=" . $item['m_id'])[0]['device_id'];
								array_push($regIDs, $device_id);
							}

							//6-3 메시지를 전송한다.
							$gm = new GcmMaker();
							$gm->SetRegIDs($regIDs);
							$gm->SetMessage("[알림] ".$title);
							$gm->Excute();
						}
					}
				}

				//7
				foreach ($new_post as $p) {
					$m_no = $p->module_no;
					$b_no = $p->board_no;
					$title = $p->title;
					$date = $p->date;
					$this->dbManager->db_insert("kit_post", $m_no . ', ' . $b_no . ', "' . $title . '", ' . $date = str_replace('.', "", $date));
				}

				//8
				if (!is_null($dbinside_post)) {
					//date order by desc로 select한 다음 그 데이터 중 count($new_post)만큼 삭제함. (상위부터)
					//echo "<p> post select 실패 </p>";
					$result = $this->dbManager->db_query2("SELECT post_no FROM kit_post ORDER BY date ASC");
					//print_r($result);
					for ($i = 0; $i < count($new_post); $i++) {
						//echo "<p> post delete 실패 </p>";
						$this->dbManager->db_query2("DELETE FROM kit_post WHERE post_no=".$result[$i]['post_no']);
					}
				}

			}
			echo 'SUCCESS';
		}




	}

	// DB에 있는 Data를 가져온다.
	function GetDB_Post(){
		return $this->dbManager->db_select_full_no_option("kit_post", "module_no, post_no");
	}

	function GetDB_Keywords(){
		return $this->dbManager->db_select_no_option("kit_keywords");
	}

	// 사용자들에게 메시지를 보낸다.
	function SendMessage(){

	}
}
?>