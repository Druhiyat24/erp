<?php 
$data = $_POST;
$code = $_POST['code'];
$id_cost = $_GET['id_cost'];
// print_r($id_url);die();
if($code == '1'){
	$Proses = new Proses();
	$_outp = $Proses->get_populasi_nya($id_cost);
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
	public function get_populasi_nya($id_cost){
		$sql = "SELECT A.id_panel id
			,A.id_cost
			,B.nama_panel isi
			FROM prod_cut_out_detail A
			INNER JOIN masterpanel AS B ON B.id = A.id_panel
			WHERE A.id_cost  = '{$id_cost}' GROUP BY A.id_panel,A.id_cost";
		$row = $this->eksekusi_query($sql,"get_populasi_nya");
		$_pop_size = array();
		$outp = '';
		for($i=0;$i<count($row);$i++){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row[$i]['id']).'",';		
			$outp .= '"isi":"'. rawurlencode($row[$i]["isi"]).'"}';	
		}
		return $outp;
	}
}
?>