<?php 
session_start();
if(!ISSET($_SESSION['username'])){
	$result = '{ "respon": "200", "status": "no", "message": "Session Experide Please Logout And Login Again", "records": "0"    }';
	echo $result;
	die();	
}
$data = $_POST;
$code = $_POST['code'];
	$mod = $_GET['mod'];
	$type_crud = $_GET['type_crud'];
if(!ISSET($_GET['type_crud'])){
	$type_crud = 'page';
}
if(!ISSET($_GET['mod'])){
	$mod == 'XX';
}


//print_r($mod);die();
if($code == '1'){
	$Proses = new Proses();
	$_outp = $Proses->get_auth($mod,$type_crud,$_SESSION['username']);
	$result = '{ "respon": "200", "status": "ok", "message": "1", "records": ['.$_outp.']    }';
	echo $result;
	die();
}
else{
	exit;
}
//}
//else{
//	exit;
//}

class Proses {
	public function connect()
		{
			include __DIR__ .'/../../../include/conn.php';
			return $con_new;
		}
		
	public function json_array($res)
    {
		
        $rows = array();
		if($res->num_rows > 0)
		{
			while($row = $res->fetch_array()){
				$rows[] = $row;
			}
		}

        return $rows;
	}		
		


	
	public function result($res)
    {
        $result = array();
		if($res->num_rows > 0)
		{
			while($row = $res->fetch_array()){
				$result[] = $row;
			}
		}
        return $result;
	}		
	
	public function check_query($connect,$my_result,$function){
				if(!$my_result)
		{
			$message = "Error :".$connect->error;
			$respon  = "500";
			$result = '{ "respon":"'.$respon.'", "message":"'.$message.'","part" : "'.$function.'", "records":"0"}';
			print_r($result);			
			exit;		
		}else{
			return 1;
		}
	}
	public function eksekusi_query($sql,$function){
		$connect = $this->connect();
		$result = $connect->query($sql);
		$check_query = $this->check_query($connect,$result,$function);
		$tmp_array = $this->result($result);
		return $tmp_array;
	}
	public function eksekusi_query_insert_update($sql,$function){
		$connect = $this->connect();
		$result = $connect->query($sql);
		$check_query = $this->check_query($connect,$result,$function);
		return '1';
	}	
	
	

	
	public function flookup_new($fld,$tbl,$criteria){
		include __DIR__ .'/../../../include/conn.php';
	
		if ($fld!="" AND $tbl!="" AND $criteria!=""){	
			$quenya = "Select $fld as namafld from $tbl Where $criteria ";
			$strsql = mysql_query($quenya);
			if (!$strsql) { 
				die($quenya. mysql_error()); 
			}
			$rs = mysql_fetch_array($strsql);
			if (mysql_num_rows($strsql)=='0'){
				$hasil="";
			}
			else{
				$hasil=$rs['namafld'];
			}
			return $hasil;
		}
	}
	
	public function get_userpassword($usersetting,$username){
		$sql = "SELECT username,ifnull({$usersetting},0){$usersetting} FROM userpassword WHERE username =  '{$username}'";
	//	echo $sql;
		$row_username = $this->eksekusi_query($sql,"get_auth");
		return $row_username[0][$usersetting];
	}
	public function get_auth($mod,$type_crud,$username){
		$sql = "SELECT mods,type_crud,usersetting FROM ms_auth WHERE mods = '{$mod}' AND type_crud = '{$type_crud}' LIMIT 1";
		$row = $this->eksekusi_query($sql,"get_auth");
		//echo $sql;
		if(count($row) == 0 ){
			$akses = "1";
		}else{
			$akses = $this->get_userpassword($row[0]['usersetting'],$username);
		}
		$_pop_size = array();
		$outp = '';
		for($i=0;$i<count($row);$i++){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"akses":"'.rawurlencode($akses).'"}';	
		}
		return $outp;
		
	
	}
}
?>