<?php
include __DIR__.'/../../log_activity/log.php';
session_start();

/*
format =  1 : INSERT
format =  2 : UPDATE
format =  3 : DELETE
*/
$data =(object)$_POST['data'];
// $det=$_POST['detail'];
// $dedet = base64_decode($det);
// $detail = json_decode($dedet);

// print_r($data);die();
$code = $_POST['code'];
$format = $_POST['format'];
// $data->dtpicker	  	= date("Y-m-d", strtotime($data->dtpicker));
// print_r($format);die();
if($code == '1'){
	$Proses = new Proses();
		if($format == '1'){
			$key = $Proses->insert($data,$_SESSION['username']);
			// $key = $Proses->insert_detail($data);
			//$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
			echo $key;
		}else if($format == '2'){
			$key = $Proses->update($data,$_SESSION['username']);
			// $key = $Proses->update_detail($data,$detail,$_SESSION['username']);
			$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
			echo $result;
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
		}else{
			insert_log_nw($sql, $_SESSION['username']);
			return 1;
		}
	}

		
	public function eksekusi_query_insert_update($sql,$function){
		$connect = $this->connect();
		$result = $connect->query($sql);
		$check_query = $this->check_query($sql,$connect,$result,$function);
		return '1';
	}

	
	public function insert($data,$username)
	{
		// print_r($data);die();
		$connect = $this->connect();	
		$dt = date("Y-m-d h:i:s");
		$sql  ="INSERT INTO prod_mark_entry (id_cost,username,dateinput) 
				VALUES (
					'{$data->ws}',
					'{$username}',
					'{$dt}'
				)";		
		// echo $sql;
		// die();				
		$result = $connect->query($sql);
		// $this->eksekusi_query_insert_update($sql,'insert');
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


	public function update($data,$username)
	{
		// print_r($data);die();
		$connect = $this->connect();	
		// $dt = date("Y-m-d h:i:s");
		$sql  ="UPDATE prod_dc_in SET
					notes	= '{$data->notes}'
				WHERE id_dc_in = '{$data->id}'";		
		// echo $sql;
		// die();				
		$result = $connect->query($sql);
		$this->eksekusi_query_insert_update($sql,'insert');
		if(!$result)
		{
			$message = "Error :".$connect->error;
			$respon  = "500";
			$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
		}					

		$message = "SUKSES!";
		$respon  = "200";			
		
		$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
		print_r($result);	
		$connect->close();
	}

}


?>