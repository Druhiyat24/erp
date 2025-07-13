<?php
session_start();

/*
format =  1 : INSERT
format =  2 : UPDATE
format =  3 : DELETE
*/
$data =(object)$_POST['data'];
$det=$_POST['detail'];
$dedet = base64_decode($det);
$detail = json_decode($dedet);

// print_r($detail);die();
$code = $_POST['code'];
$format = $_POST['format'];
// $data->dtpicker	  	= date("Y-m-d", strtotime($data->dtpicker));
// print_r($format);die();
if($code == '1'){
	$Proses = new Proses();
		if($format == '1'){
			$key = $Proses->insert($data,$_SESSION['username']);
			$key = $Proses->insert_detail($detail);
			$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
			echo $result;
		}else if($format == '2'){
			$key = $Proses->update($data,$_SESSION['username']);
			$key = $Proses->update_detail($data,$detail,$_SESSION['username']);
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
		$sql  ="INSERT INTO prod_sec_in (notes,inhouse_subkon,dept_subkon,id_cost,username,dateinput) 
				VALUES (
					'{$data->notes}',
					'{$data->inhousesubcon}',
					'{$data->deptsubcon}',
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
	}

	public function insert_detail($detail)
	{
		// print_r($detail);die();
		$connect = $this->connect();
		$sqli = "SELECT max(id_sec_in) AS id FROM prod_sec_in";
		$result = $connect->query($sqli);

		$row = mysqli_fetch_array($result);
		$last_id = $row['id'];

		for($i=0;$i<count($detail);$i++){

			$detail[$i] = (array)$detail[$i];
			$qtySecIn = $detail[$i]['qty_qc_val'] - $detail[$i]['qty_reject_si_val'];

			$sql  = "INSERT INTO prod_sec_in_detail (
						id_sec_in
					   ,id_cut_out_detail
					   ,id_cut_qc
					   ,id_cut_number
					   ,id_panel
					   ,qty_reject_sec_in
					   ,qty_sec_in
					) 
					VALUES (
						'{$last_id}',
						'{$detail[$i]['id_cut_out_detail']}',
						'{$detail[$i]['id_cut_qc']}',
						'{$detail[$i]['id_cut_number']}',
						'{$detail[$i]['id_panel']}',
						'{$detail[$i]['qty_reject_si_val']}',
						'{$qtySecIn}'
					)
			";
			// echo $sql;die();
			$result = $connect->query($sql);

			$sql2 = "UPDATE prod_cut_qc SET secondary = 'Y' WHERE id_cut_qc = '{$detail[$i]['id_cut_qc']}'";
			$result2 = $connect->query($sql2);
			
		}
		// print_r($sql);die();
		return $result;
		// if(!$result)
		// {
		// 	$message = "Error :".$connect->error;
		// 	$respon  = "500";
		// 	$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
		// }					

		// $message = "SUKSES!";
		// $respon  = "200";			
		
		$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
		print_r($result);	
		$connect->close();
	}

	public function update($data,$username)
	{
		// print_r($data);die();
		$connect = $this->connect();	
		// $dt = date("Y-m-d h:i:s");
		$sql  ="UPDATE prod_sec_in SET
					inhouse_subkon = '{$data->inhousesubcon}',
					dept_subkon = '{$data->deptsubcon}',
					notes = '{$data->notes}'
				WHERE id_sec_in = '{$data->id}'";		
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
	}
	
    
	public function update_detail($data,$detail,$username)
	{

		$connect = $this->connect();

		for($i=0;$i<count($detail);$i++){
			$detail[$i] = (array)$detail[$i];
			$qtySecIn = $detail[$i]['qty_qc_val'] - $detail[$i]['qty_reject_si_val' ];
				
			$sql = "UPDATE prod_sec_in_detail SET 
						qty_reject_sec_in	= '{$detail[$i]['qty_reject_si_val']}',
						qty_sec_in			= '{$qtySecIn}'
					WHERE id_sec_in_detail 	= '{$detail[$i]['id_sec_in_detail']}'
			";

			$result = $connect->query($sql);
			
		}
		return $result;
		// die();
		$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
		print_r($result);		
	}

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