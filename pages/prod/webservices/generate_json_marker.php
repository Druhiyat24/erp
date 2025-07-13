<?php 
$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){
$id_mark_entry 	= $_GET['id_mark_entry'];
$color 			= $_GET['color'];
$code = 1;
/* print_r($_GET);die(); */
if($code == '1'){
	$Proses = new Proses();
			//echo '123';
			$populasi_marker 	= $Proses->get_key_panel($id_mark_entry,$color);
			//print_r($populasi_marker);
			$result 		= '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":['.json_encode($populasi_marker).']  }';
			echo $result;

}
else{
	exit;
}
print_r($List);
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
	
	public function get_array_per_item_per_panel($id_mark_entry,$color,$id_panel){
		$__x = array();
		$sql ="SELECT A.id_panel,A.id_item,A.color,B.nama_panel,A.id_mark,A.id_cost FROM prod_mark_entry_detail A
					INNER JOIN masterpanel B ON A.id_panel = B.id
		
				WHERE id_mark = '{$id_mark_entry}' AND color='{$color}' AND id_panel = '{$id_panel}' GROUP BY id_item";
		$tmp_array_ = $this->eksekusi_query($sql,"get_array_per_item_per_panel");
		$cnt = count($tmp_array_);
		
		for($ii=0;$ii<count($tmp_array_);$ii++){
			$_array = array(
				"id_panel" => $tmp_array_[$ii]['id_panel'],
				"id_item" => $tmp_array_[$ii]['id_item'],
				"nama_panel" => $tmp_array_[$ii]['nama_panel'],
				"id_mark_entry" => $tmp_array_[$ii]['id_mark'],
				"id_cost" => $tmp_array_[$ii]['id_cost'],
				"color" => $tmp_array_[$ii]['color']				
			);
			array_push($__x,$_array);
			
		}
			
		return $__x;

		
	}
	
	public function get_key_panel($id_mark_entry,$color){
		$_x = array();
		
		$sql ="SELECT id_panel,id_item,color FROM prod_mark_entry_detail WHERE id_mark = '{$id_mark_entry}' AND color='{$color}'
			GROUP BY id_panel
		";
/* 		echo $sql; */
		$tmp_array = $this->eksekusi_query($sql,"get_key_panel");
		$cnt = count($tmp_array);
		for($i=0;$i<count($tmp_array);$i++){
			$populasi_item = $this->get_array_per_item_per_panel($id_mark_entry,$color,$tmp_array[$i]['id_panel']);
			
			$__array = array(
				"panel" => $populasi_item
			);
			array_push($_x,$__array);
	//	print_r($_x);
		}
		return $_x;		
		
		
		
		
	}

	
	
	
}
?>