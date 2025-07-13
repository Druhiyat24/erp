<?php
include __DIR__.'/../../log_activity/log.php';
session_start();
$Proses = new Proses();
/*
format =  1 : INSERT
format =  2 : UPDATE
format =  3 : DELETE
*/
$data  = $_POST['header'];
$header= json_decode($data);
$det   = $_POST['detail'];
$detail= json_decode($det);
$code  = $_POST['code'];
$format= $_POST['format'];
/* print_r($detail);
die(); */
 if($_POST['detail'] == "[{}]"){

		$result = '{ "respon":"'.'205'.'", "message":"'.'No Data '.'", "records":"0"}';   
		echo $result;	
		die();
} 
	//validasi_header
	if($header->id_line == ''){
		$result = '{ "respon":"'.'204'.'", "message":"'.'Line Must Be Fill'.'", "records":"0"}';   
		echo $result;	
		die();
	}	
	if($header->id_cost == ''){
		$result = '{ "respon":"'.'204'.'", "message":"'.'WS Must Be Fill'.'", "records":"0"}';   
		echo $result;		
		die();
	}
	if($header->time == ''){
		$result = '{ "respon":"'.'204'.'", "message":"'.'Time Output Must Be Fill'.'", "records":"0"}';
		echo $result;		
		die();
	}if($header->date_output == ''){
		$result = '{ "respon":"'.'204'.'", "message":"'.'Date Output Must Be Fill'.'", "records":"0"}';
		echo $result;
		die();		
	}	



//validasi
	//check Format Inputan
	$ii=0;
for($i=0;$i<count($detail);$i++){
	$ii = $i+1;
	$_det = $detail[$i];
	$id_defect =$_det->is_id_defect;
	$check_list =$_det->is_checklist;
	//$balance = $_det->is_balance;
    if ($check_list == '1' ) {
		if($id_defect ==""){

		$result = '{ "respon":"'.'277'.'", "message":"'.'OK'.'", "records":"0","baris":"'.$ii.'"}';    
		echo $result;		
		die();			
		}else{
			$x= 'TRUE';
		}
        
    } else {
		$x="TRUE";
    }	
}



if($code == '1'){
		if($format == '1'){
			$no_reject_qot 				= $Proses->get_no($header->date_output);
			$key 						= $Proses->insert($header,$_SESSION['username'],$no_reject_qot);
			$id_reject_qot_header  		= $Proses->get_id($no_reject_qot);
			$key 						= $Proses->update_detail($header,$detail,$id_reject_qot_header,$_SESSION['username']);
			$result 					= '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0","id_reject_qot_header" : "'.$id_reject_qot_header.'"}';
/* 			echo $result;
			die(); */
		}else if($format == '2'){ 
			$id_reject_qot_header = $header->id_reject_qot_header;
			$key = $Proses->update($header,$_SESSION['username']);
			$key = $Proses->update_detail($header,$detail,$id_reject_qot_header,$_SESSION['username']);
			$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0","id_reject_qot_header" : "'.$id_reject_qot_header.'"}';
			echo $result;
			die();
		}else{
			exit;
		}
}
else{
	exit;
}

$result = '{ "respon":"'.'200'.'", "message":"'.'Data Berhasil disimpan'.'", "records":"0"}';
print_r($result);
//  echo $result;
// die(); 
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
	
	public function check_query($sql,$connect,$my_result,$function){
				if(!$my_result)
		{
			$message = "Error :".$connect->error;
			$respon  = "500";
			$result = '{ "respon":"'.$respon.'", "message":"'.$message.'","part" : "'.$function.'", "records":"0"}';
			print_r($result);			
			exit;		
		}else{
			insert_log_nw($sql, $_SESSION['username']);
			return 1;
		}
	}
	public function eksekusi_query($sql,$function){
		$connect = $this->connect();
		$result = $connect->query($sql);
		$check_query = $this->check_query($sql,$connect,$result,$function);
		$tmp_array = $this->result($result);
		return $tmp_array;
	}
	public function eksekusi_query_insert_update($sql,$function){
		$connect = $this->connect();
		$result = $connect->query($sql);
		$check_query = $this->check_query($sql,$connect,$result,$function);
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
	
	public function check_exist_pool($_category){
		$_arr = array();
		$_years = date("Y");
		$sql = "SELECT category,seq,years FROM prod_pool WHERE category = '{$_category}' AND years='{$_years}' LIMIT 1";

		$tmp_array = $this->eksekusi_query($sql,"check_exist_pool");
		if(count($tmp_array) > 0 ){
			$_arr = array(
				"category" 	=> $_category,
				"seq"	   	=> $tmp_array[0]["seq"],
				"years"		=> $tmp_array[0]["years"]
			);
		}else{
			$_arr = array(
				"category" 	=> $_category,
				"seq"	   	=> '0',
				"years"		=> date("Y")
			);

		
		}
/* 		array_push($tmp_array,$_arr);
		print_r($_arr);
		die(); */
		return $_arr;
	}		

	public function update_pool($_category,$_years){
		$_arr = array();
		$update = "UPDATE prod_pool SET seq=seq+1  WHERE category = '{$_category}' AND years = '{$_years}'
		";
		$this->eksekusi_query_insert_update($update,'update_pool');
		return 1;
	}		

	public function insert_pool($_category){
		$years =date("Y");
		$seq = 1;
		$update = "INSERT INTO prod_pool(category,years,seq)VALUES('{$_category}','{$years}','{$seq}')";
		$this->eksekusi_query_insert_update($update,'insert_pool');
		return 1;
	}
	
		
	
	public function get_no($_date_output){
		$category ="QOT/REJ";
		$__d_out =  date("Y-m-d", strtotime($_date_output));
		$_pecah  = explode("-",$__d_out);
		
		
		$_years = $_pecah[0];
		$_month = $_pecah[1];
		$populasi_pool = $this->check_exist_pool($category);

		if((intval($populasi_pool['seq']) > 0)){
			if(intval($populasi_pool['years']) == intval($_years)){
				$years =$populasi_pool["years"];
				$seq   =sprintf('%06d', (intval($populasi_pool["seq"])+ 1));
				$this->update_pool($category,$years);
			}else{
				$years =$_years;
				$this->insert_pool($category);
				$seq   =sprintf('%06d', (1));
				
			}
		}else{
				$years =$populasi_pool["years"];
				$seq   =sprintf('%06d', (intval($populasi_pool["seq"])+ 1));
				$this->insert_pool($category);
		}
			
			//$seq   =sprintf('%06d', (intval($populasi_pool["seq"])+ 1));
			$seq_years = substr($years,2,2);
			$_no_reject_qot = $category."/".$_month.$seq_years."/".$seq;
			return $_no_reject_qot;
	}	
	public function get_id($_no_reject_qot){
		$sql = "SELECT id_reject_qot_header FROM prod_reject_qot_header WHERE no_reject_qot = '{$_no_reject_qot}' LIMIT 1";

		$tmp_array = $this->eksekusi_query($sql,"get_id");

		return $tmp_array[0]['id_reject_qot_header'];
	}
	public function insert($data,$username,$no_reject_qot)
	{
		$d_out =  date("Y-m-d", strtotime($data->date_output));
		$dt = date("Y-m-d H:i:s");
		$__time=explode(" ",$dt);
		$_time = explode(":",$__time[1]);
		$time  = $_time[0].":".$_time[1];	
		$insert = "INSERT INTO prod_reject_qot_header (notes,id_cost,id_line,username,dateinput,date_output,no_reject_qot,time) 
				VALUES (
					'{$data->notes}',
					'{$data->id_cost}',
					'{$data->id_line}',
					'{$username}',
					'{$dt}',
					'{$d_out}',
					'{$no_reject_qot}',
					'{$time}' )
					";
/* 			echo $insert;
die();		 */	
		$this->eksekusi_query_insert_update($insert,'insert');
		return 1;	
	}
	public function update($data,$username)
	{
		$d_out =  date("Y-m-d", strtotime($data->date_output));
		$dt = date("Y-m-d H:i:s");
		$update = "UPDATE prod_reject_qot_header SET id_line = '{$data->id_line}',notes='{$data->notes}',userupdate='{$username}',dateupdate='{$dt}' WHERE id_reject_qot_header = '{$data->id_reject_qot_header}'";
		$this->eksekusi_query_insert_update($update,'update');
		return 1;	
	}	
	
	
	

	
	
	public function update_detail($header,$_detail,$id_reject_qot_header,$username)
	{
		$dt = date("Y-m-d H:i:s");
		for($i=0;$i<count($_detail);$i++){
			$_det = $_detail[$i];
			
			if($_det->is_id_det > 0){
				if($_det->is_checklist == 0){
					$delete = "UPDATE prod_reject_qot_detail SET id_reject_qot_header = NULL,dateupdate='{$dt}',userupdate='{$username}' , id_defect=NULL WHERE id_reject_qot_detail = '{$_det->is_id_det}'";
					$this->eksekusi_query_insert_update($delete,'update_detail');
				}else{
					$update = "UPDATE prod_reject_qot_detail SET id_reject_qot_header = '{$id_reject_qot_header}',dateupdate='{$dt}',userupdate='{$username}',id_defect={$_det->is_id_defect} WHERE id_reject_qot_detail = '{$_det->is_id_det}'";
					$this->eksekusi_query_insert_update($update,'update_detail');
					
				}
			}
		}
		return 1;		
	
		}
	}		
?>