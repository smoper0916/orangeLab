<?php
class DBManager
{
	private $s;
	private $db_place = "localhost";
	private $db_id = "smoper0916";
	private $db_pw = "hyeok3096";
	private $db_name = "smoper0916";
		
	function __construct(){
		$this->s = mysql_connect($this->db_place, $this->db_id, $this->db_pw) or die("Failed");
		mysql_select_db($this->db_name, $this->s);
	}
	function __destruct(){
		mysql_close($this->s);
	}	
	//-------------------------------------------------------------------
	// 테이블 생성
	//-------------------------------------------------------------------
	function db_create($Tname,$Attribute){
	  $sql="create table ".$Tname."(".$Attribute.");";
	  $result = mysql_query($sql);
	  if(!$result) echo "테이블생성 실패";
	}
	//-------------------------------------------------------------------
	// 데이터 삽입
	//-------------------------------------------------------------------
	function db_insert($Tname, $data){
	  $sql="insert into ".$Tname." VALUES (".$data.");";
	  $result = mysql_query($sql);
	  if(!$result) echo "데이터입력 실패";
	}

	//-------------------------------------------------------------------
	// 데이터 삭제
	//-------------------------------------------------------------------
	function db_delete($Tname, $where){
	  $sql="delete from ".$Tname." WHERE ".$where.";";
	  $result = mysql_query($sql);
	  if(!$result) echo "데이터삭제 실패";
	}

	//-------------------------------------------------------------------
	// 데이터 수정
	//-------------------------------------------------------------------
	function db_modify($Tname, $set, $where){
	  $sql="update ".$Tname." SET ".$set." WHERE ".$where;
	  $result = mysql_query($sql);
	  if(!$result) echo "데이터수정 실패";
	}


	//-------------------------------------------------------------------
	// 데이터 출력
	//-------------------------------------------------------------------
	function db_print($Tname){
	  $sql="select * from ".$Tname.";";
	  $result=mysql_query($sql);

	  if($result){
	   while($row = mysql_fetch_array($result)) $total[]=$row;
	   return $total;
	  }else echo "데이터출력 실패";
	}
	function db_select($Tname, $Catt){
	  $sql="select * from ".$Tname." where ".$Catt.";";
	  //$sql="select * from  se_current_weather_info where x=86 and y=96;";
	  $result=mysql_query($sql);
	  
	  if($result){
	   while($row = mysql_fetch_array($result)) $total[] = $row;
	   return $total;
	  }else echo "데이터출력 실패";
	}

	//-------------------------------------------------------------------
	// 쿼리 입력
	//-------------------------------------------------------------------
	function db_query($sql){
	  $result = mysql_query($sql) or die(__FILE__." : Line ".__LINE__."<p>Query : $sql<br><br><br>".mysql_error());
	  if($result){
	   $row = mysql_fetch_array($result);
	   return $row;  
	  }else{
	   echo "쿼리 실패";
	  }
	}
}
?>