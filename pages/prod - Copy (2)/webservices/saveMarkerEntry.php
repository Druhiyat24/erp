<?php
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

	// public function insert_detail($detail)
	// {
	// 	// print_r($detail);die();
	// 	$connect = $this->connect();
	// 	$sqli = "SELECT max(id_dc_in) AS id FROM prod_dc_in";
	// 	$result = $connect->query($sqli);

	// 	// print_r(mysqli_fetch_array($result));die();
	// 	$row = mysqli_fetch_array($result);
	// 	$last_id = $row['id'];

	// 	// echo $last_id;die();
	// 	// print_r($last_id);die();
	// 	//  print_r(count($detail));die();
	// 	for($i=0;$i<count($detail);$i++){
	// 		// echo('123');die();
	// 		$detail[$i] = (array)$detail[$i];
	// 		// print_r($detail[$i]);die();			
	// 		$sql  = "INSERT INTO prod_dc_in_detail (
	// 					id_dc_in
	// 				   ,id_panel
	// 				   ,id_cut_out_detail
	// 				   ,color
	// 				   ,size
	// 				   ,qty_input
	// 				   ) 
	// 				   VALUES (
	// 					   '{$last_id}',
	// 					   '{$detail[$i]['id_panel']}',
	// 					   '{$detail[$i]['id_cut_out_detail']}',
	// 					   '{$detail[$i]['color']}',
	// 					   '{$detail[$i]['size']}',
	// 					   '{$detail[$i]['qty_val']}'
	// 			)";	
	// 			//print_r($sql);die();
	// 			// echo $sql;die();
	// 			$result = $connect->query($sql);
			
	// 		// print_r($detail[$i]);die();
	// 		// $return = $detail[$i]['qty_received'] - $detail[$i]['need_val'];
			
	// 	}
	// 	// print_r($sql);die();
	// 	return $result;
	// 	// if(!$result)
	// 	// {
	// 	// 	$message = "Error :".$connect->error;
	// 	// 	$respon  = "500";
	// 	// 	$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
	// 	// }					

	// 	// $message = "SUKSES!";
	// 	// $respon  = "200";			
		
	// 	$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
	// 	print_r($result);	
	// 	$connect->close();
	// }

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
	
    
	// public function update_detail($data,$detail,$username)
	// {
	// 	// print_r($detail);die();
	// 	$connect = $this->connect();
	// 	$dt = date("Y-m-d h:i:s");
	// 	// print_r(count($detail));die();
	// 	for($i=0;$i<count($detail);$i++){
	// 		$detail[$i] = (array)$detail[$i];
	// 		// $return = $detail[$i]['qty_received'] - $detail[$i]['need_val'];
			
	// 		$sql="UPDATE prod_dc_in_detail SET 
	// 			qty_input	= '{$detail[$i]['qty_val']}'
	// 		WHERE id_cut_out_detail = '{$detail[$i]['id_cut_out_detail']}'";
			
	// 		/* echo "$sql  <br/>"; */
	// 		 $result = $connect->query($sql);
			
	// 	}
	// 	return $result;
	// 	// die();
	// 	$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
	// 	print_r($result);		
	// }

	public function deletes($data)
	{
		$connect = $this->connect();
		$id		  = $data->id;
		$sql="UPDATE cut_in SET idDelete = '0' WHERE id_cut_in = '$id'";
		
		$result = $connect->query($sql);
		if(!$connect->query($sql))
		{
			$message = "Error :".$connect->error;
			$respon  = "500";
			//$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
			//return result;			
			
		}
		else
		{
			$message = "SUKSES!";
			$respon  = "200";
			//$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
			//return result;
		};
		$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
		print_r($result);
	}

}


?>