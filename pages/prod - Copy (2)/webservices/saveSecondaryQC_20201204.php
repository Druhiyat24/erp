<?php
session_start();
include __DIR__ .'/../../../include/conn.php';

/*
format =  1 : INSERT
format =  2 : UPDATE
format =  3 : DELETE
*/
// $data =(object)$_POST['data'];
$det=$_POST['detail'];
$dedet = base64_decode($det);
$detail = json_decode($dedet);
// print_r($detail);die();

$code = $_POST['code'];
$id_url = $_POST['id'];

$sql = "SELECT qc.id_sec_out FROM prod_sec_qc AS qc WHERE qc.id_sec_out = '{$id_url}'";
// echo $sql;die();
$stmt = mysql_query($sql);
$row = mysql_fetch_array($stmt);


// print_r($id_url);die();

if($row['id_sec_out'] == $id_url){
	// echo "Update";
	$format = '2';
}
else{
	// echo "Insert";
	$format = '1';
}
// print_r($format);die();

if($code == '1'){
	$Proses = new Proses();
	if($format == '1'){
		// $key = $Proses->insert($data,$_SESSION['username']);
		$key = $Proses->insert_detail($detail,$_SESSION['username']);
		$result = '{ "responds":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
		echo $result;
	}else if($format == '2'){
		// $key = $Proses->update($data,$_SESSION['username']);
		$key = $Proses->update_detail($detail,$_SESSION['username']);
		$result = '{ "responds":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
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


	public function insert_detail($detail,$username)
	{
		// print_r($detail);die();
		$connect = $this->connect();
		$dt = date("Y-m-d h:i:s");

		for($i=0;$i<count($detail);$i++){
			// echo('123');die();
			$detail[$i] = (array)$detail[$i];

			$qtyQC = $detail[$i]['qty_sec_out_val'] - $detail[$i]['qty_reject_qc_val'];

			$sql  = "INSERT INTO prod_sec_qc (
						id_sec_out_detail,
						id_sec_out,
						id_sec_in_detail,
						id_cut_out_detail,
						id_cut_number,
						id_cut_qc,
						qty_reject_sec_qc,
						qty_sec_qc,
						approve_sec,
						username_approve_sec,
						dateinput_approve_sec
					) 
					VALUES (
						'{$detail[$i]['id_sec_out_detail']}',
						'{$detail[$i]['id_sec_out']}',
						'{$detail[$i]['id_sec_in_detail']}',
						'{$detail[$i]['id_cut_out_detail']}',
						'{$detail[$i]['id_cut_number']}',
						'{$detail[$i]['id_cut_qc']}',
						'{$detail[$i]['qty_reject_qc_val']}',
						'{$qtyQC}',
						'{$detail[$i]['check_val']}',
						'{$username}',
						'{$dt}'
					)
			";
			$result = $connect->query($sql);

				//print_r($sql);die();
				// echo $sql;die();
			
			// print_r($detail[$i]);die();
			// $return = $detail[$i]['qty_received'] - $detail[$i]['need_val'];
			
		}
		// echo $sql;die();
		return 1;
		// if(!$result)
		// {
		// 	$message = "Error :".$connect->error;
		// 	$respon  = "500";
		// 	$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
		// }					

		// $message = "SUKSES!";
		// $respon  = "200";			
		
		// $result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
		// print_r($result);	
		$connect->close();
	}
	
    
	public function update_detail($detail,$username)
	{
		// print_r($detail);die();
		$connect = $this->connect();
		$dt = date("Y-m-d h:i:s");
		// print_r(count($detail));die();
		for($i=0;$i<count($detail);$i++){
			$detail[$i] = (array)$detail[$i];

			$qtyQC = $detail[$i]['qty_sec_out_val'] - $detail[$i]['qty_reject_qc_val'];

			$sql="UPDATE prod_sec_qc SET
					qty_reject_sec_qc	= '{$detail[$i]['qty_reject_qc_val']}',
					qty_sec_qc			= '{$qtyQC}',
					approve_sec			= '{$detail[$i]['check_val']}'
				WHERE id_sec_out_detail = '{$detail[$i]['id_sec_out_detail']}'
			";
			
			// echo "$sql  <br/>";
			
			
			$connect->query($sql);
		}
		// die();
		return 1;
		
		// $result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
		// print_r($result);
		$connect->close();	
	}


}


?>