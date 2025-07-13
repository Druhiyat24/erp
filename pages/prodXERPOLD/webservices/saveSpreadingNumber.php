<?php
include __DIR__.'/../../log_activity/log.php';
session_start();

/*
format =  1 : INSERT
format =  2 : UPDATE
format =  3 : DELETE
*/
$data =(object)$_POST['data'];
// $detail = $_POST['detail'];
// $det=$_POST['detail'];
// $dedet = base64_decode($det);
// $detail = json_decode($dedet);

// print_r($data);die();
$code = $_POST['code'];
$format = $_POST['format'];
//print_r($_POST);die();
// $data->dtpicker	  	= date("Y-m-d", strtotime($data->dtpicker));
// print_r($format);die();
if($code == '1'){
	$Proses = new Proses();
		if($format == '1'){
			// echo('123');die();
			$id_number_group = $Proses->generate_id_number_group($data);
			$key = $Proses->insert($data,$id_number_group);
			// $key = $Proses->insert_detail($data);
			$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
			print_r($result);
		}else if($format == '2'){
			$key = $Proses->update($data);
			// $key = $Proses->update_detail($data,$detail,$_SESSION['username']);
			$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
			print_r($result);
		}else{
			exit;
		}
}
else{
	exit;
}

class Proses {

	public function connect()
	{
		include __DIR__ .'/../../../include/conn.php';
		return $conn_li;
	}

	public function check_error($result,$connect)
	{
		if(!$result)
		{
			$message = "Error :".$connect->error;
			$respon  = "500";
			$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
			print_r($result);			
			exit;	
		}
		else
		{
			$check = "ok";
			return $check;
		}
	}

	public function check_query($sql,$connect,$my_result,$function){
		if(!$my_result){
			$message = "Error :".$connect->error;
			$respon  = "500";
			$result = '{ "respon":"'.$respon.'", "message":"'.$message.'","part" : "'.$function.'", "records":"0"}';
			print_r($result);			
			exit;		
		}
		else{
			insert_log_nw($sql,$_SESSION['username']);
			return 1;
		}
	}


	public function eksekusi_query_insert_update($sql,$function){
		$connect = $this->connect();
		$result = $connect->query($sql);
		$check_query = $this->check_query($sql,$connect,$result,$function);
		return '1';
	}


	public function generate_id_number_group($_data)
	{
		// print_r($data);die();
		$connect = $this->connect();	
		// $dt = date("Y-m-d h:i:s");
		$sql = "SELECT COUNT(*)jumlah FROM prod_spread_report_number WHERE id_cost ='{$_data->id_costing}' AND  id_so='{$_data->id_so}'  ";		
		// echo $sql;
		// die();				
		$result = $connect->query($sql);
		if(!$result)
		{
			$message = "Error :".$connect->error;
			$respon  = "500";
			$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
			print_r($result);
			die();
		}					
				$rec__ = mysqli_fetch_assoc($result); 
				$_number = $rec__['jumlah'];
				$_number = $_number + 1;
				
				$_number = sprintf('%05d', $_number);
				return $_number;
					
		
		$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
		print_r($result);	
		$connect->close();
	}

	
	public function insert($_data,$_id_number_group){
 		// print_r($_id_number_group);die(); 
		$connect = $this->connect();

		$sql2  ="INSERT INTO prod_spread_report_number (id_mark_entry,id_cost,id_number_group,id_internal,username,id_so,color) 
				VALUES (
					'{$_data->id_mark_entry}',
					'{$_data->id_costing}',
					'{$_id_number_group}',
					'{$_data->nomor_internal}',
					'{$_SESSION['username']}',
					'{$_data->id_so}',
					'".rawurldecode($_data->color)."'
		)";	
/* echo $sql2;
die();		 */
		$connect->query($sql2);
		// $this->eksekusi_query_insert_update($sql2,'insert');

		if(!$result)
		{
			$message = "Error :".$connect->error;
			$respon  = "500";
			$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
		}					

		$message = "SUKSES!";
		$respon  = "200";			
		
		$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
		// print_r($result);	
		$connect->close();
		return $result;
	}


	public function update($_data){
		// print_r($data);die();
		$connect = $this->connect();	
		// $dt = date("Y-m-d h:i:s");
		$sql = "UPDATE prod_spread_report_number SET
					id_internal			= '{$_data->nomor_internal}'
				WHERE id_number = '{$_data->id_number}'";		
		 //echo $sql;
		 //die();				
		$result = $connect->query($sql);
		$this->eksekusi_query_insert_update($sql,'update');

		if(!$result)
		{
			$message = "Error :".$connect->error;
			$respon  = "500";
			$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
		}					

		$message = "SUKSES!";
		$respon  = "200";			
		
		$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
		//print_r($result);	
		$connect->close();
	}

}


?>