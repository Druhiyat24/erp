<?php
$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){
$data 			= $_POST['data'];
$code 			= $_POST['code'];

$id_cost 		= $data['id_cost'];
$id_mark_entry 	= $data['id_mark_entry'];
$color 			= $data['color'];
$id_panel		= $data['id_panel'];
$id_item		= $data['id_item'];

$id_group	 	= $data['id_group'];
$id_group_det 	= $data['id_group_det'];
$balance 		= 0;
// print_r($id_item);die();
if($code == '1'){
	$Proses = new Proses();
			//echo '123';
			$populasi_mark= array();
			$populasi_mark['headers']				= $Proses->get_header($id_cost);
			$populasi_mark['qty_so']  				= $Proses->get_qty_so($id_cost);
			$populasi_mark['rasio']  			   	= $Proses->get_rasio($id_cost,$color,$id_mark_entry,$id_panel,$id_item);

/* 			print_r($populasi_mark);
			die(); */
			// $key = $Proses->update_detail($data,$detail,$_SESSION['username']);
			$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":['.json_encode($populasi_mark).']}';
			echo $result;

}
else{
	exit;
}
print_r($List);
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
	
	
	public function get_header($_id_cost){
		$sql = "CALL spGetSizeByIdCosting('{$_id_cost}')";
		$tmp_array = $this->eksekusi_query($sql,"get_header");
		$_pop_size = array();
		//print_r($tmp_array);
		return $tmp_array;
		
		
		
	}
	
	public function get_qty_so($_id_cost){
		$sql = "CALL spKolomQtySo('{$_id_cost}')";
		$tmp_array = $this->eksekusi_query($sql,"get_header");
		//print_r($sql);
		$_pop_size = array();
		return $tmp_array;
		
	}

	public function get_rasio($_id_cost,$_color,$_id_mark,$_id_panel,$_id_item){
		$sql = "CALL spGetPopulasiRasioByIdGroupDet('{$_id_cost}','{$_color}','{$_id_mark}','{$_id_panel}','{$_id_item}')";
		$tmp_array = $this->eksekusi_query($sql,"get_header");
   		// print_r($sql);die();  
		$_pop_size = array();
		return $tmp_array;
	}	
}

?>