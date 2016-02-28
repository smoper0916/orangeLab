<?php

namespace orangelab;

class DBManager
{
	private $s;
	private $db_place = "localhost";
	
	//private $db_id = "smoper0916";
	private $db_id = "root";
	private $db_pw = "autoset";
	private $db_name = "smoper0916";
    public $recent_row_cnt = 0;
		
	function __construct(){
		$this->s = mysqli_connect($this->db_place, $this->db_id, $this->db_pw, $this->db_name) or die("Failed");
		//$this->s = mysql_connect($this->db_place, $this->db_id, $this->db_pw) or die("Failed");
		//mysql_select_db($this->db_name, $this->s);
	}
	function __destruct(){
		mysqli_close($this->s);
	}	
	//-------------------------------------------------------------------
	// 테이블 생성
	//-------------------------------------------------------------------
	function db_create($Tname,$Attribute){
	  $sql="create table ".$Tname."(".$Attribute.");";
	  $result = mysqli_query($this->s, $sql);
	  if(!$result) echo "테이블생성 실패";
	}
	//-------------------------------------------------------------------
	// 데이터 삽입
	//-------------------------------------------------------------------
	function db_insert($Tname, $data){
	  $sql="insert into ".$Tname." VALUES (".$data.");";
        //echo $sql;
	  $result = mysqli_query($this->s, $sql);
	  if(!$result) echo "<p>데이터입력 실패 : ".$sql."</p>";
	}

	//-------------------------------------------------------------------
	// 데이터 삭제
	//-------------------------------------------------------------------
	function db_delete($Tname, $where){
	  $sql="delete from ".$Tname." WHERE ".$where.";";
	  $result = mysqli_query($this->s, $sql);
	  if(!$result) echo "데이터삭제 실패";
	}

	//-------------------------------------------------------------------
	// 데이터 수정
	//-------------------------------------------------------------------
	function db_modify($Tname, $set, $where){
	  $sql="update ".$Tname." SET ".$set." WHERE ".$where;
	  $result = mysqli_query($this->s, $sql);
	  if(!$result) echo "데이터수정 실패";
	}


	//-------------------------------------------------------------------
	// 데이터 출력
	//-------------------------------------------------------------------
	function db_print($Tname){
	  $sql="select * from ".$Tname.";";
	  $result=mysqli_query($this->s, $sql);

	  if($result){
	   while($row = mysqli_fetch_array($result)) $total[]=$row;
	   return $total;
	  }else echo "데이터출력 실패";
	}
	function db_select_no_option($Tname){
		$sql="select * from ".$Tname;
		$result=mysqli_query($this->s, $sql);
        $this->recent_row_cnt = mysqli_num_rows($result);
		if($result){
            $total = null;
			while($row = mysqli_fetch_array($result)) $total[] = $row;
			return $total;
		}else echo "데이터출력 실패";
	}
	function db_select_full_no_option($Tname, $Aname){
	    $sql="select ".$Aname." from ".$Tname;
	    $result=mysqli_query($this->s, $sql);

	    if($result){
            $total = null;
	        while($row = mysqli_fetch_array($result)) $total[] = $row;
	        return $total;
	    }else echo "데이터출력 실패";
	}
	function db_select_full($Tname, $Aname, $Catt){
	    $sql="select ".$Aname." from ".$Tname." where ".$Catt;
	    $result=mysqli_query($this->s, $sql);

	    if($result){
            $total = null;
	        while($row = mysqli_fetch_array($result)) $total[] = $row;
	        return $total;
	    }else echo "데이터출력 실패";
	}
	function db_select($Tname, $Catt){
	  $sql="select * from ".$Tname." where ".$Catt;
	  //$sql="select * from  se_current_weather_info where x=86 and y=96";
	  $result=mysqli_query($this->s, $sql);
	  
	  if($result){
          $total = null;
          while($row = mysqli_fetch_array($result)) $total[] = $row;
          return $total;
	  }else echo "데이터출력 실패";
	}

	//-------------------------------------------------------------------
	// 쿼리 입력
	//-------------------------------------------------------------------
	function db_query($sql){
	  $result = mysqli_query($this->s, $sql);
	  if($result){
	   $row = mysqli_fetch_array($result);
	   return $row;  
	  }else{
	   echo "쿼리 실패";
	  }
	}
	function db_query2($sql){
	    $result = mysqli_query($this->s, $sql);
	    if($result){
            if (strpos($sql, 'SELECT') !== false || strpos($sql, 'select') !== false) {
                $total = null;
                while($row = mysqli_fetch_array($result))
                {
                    $total[] = $row;
                }
                return $total;
            }
	    }else{
	        echo "쿼리 실패";
	    }
	}
}
?>