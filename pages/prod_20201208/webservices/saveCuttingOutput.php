<?php
session_start();

/*
format =  1 : INSERT
format =  2 : UPDATE
format =  3 : DELETE
*/
$data =(object)$_POST['data'];
$detail=$_POST['detail'];
$id_header = $_POST['id_header'];
//  print_r($detail['id']);//die();   
$code = $_POST['code'];
$format = $_POST['format'];

if($code == '1'){
	$Proses = new Proses();
		if($format == '1'){
			$key = $Proses->insert($data,$_SESSION['username']);
			$key = $Proses->insert_detail($detail);
			
			// $key = $proses->delete_tempo($detail);
			//$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
			echo $result;
			exit;
		}else if($format == '2'){
			$key = $Proses->update($detail,$id_header);
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
		$connect = $this->connect();	
		$dt = date("Y-m-d h:i:s");
		$sql  ="INSERT INTO cut_out (id_act_costing,username,dateinput) 
				VALUES (
					'{$data->ws}',
					-- '{$data->qty_header}',
					'{$username}',
					'$dt'
				)";			
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
		$connect = $this->connect();
		$sqli = "SELECT max(id_cut_out) AS id FROM cut_out";
		$result = $connect->query($sqli);

		$row = mysqli_fetch_array($result);
		$last_id = $row['id'];

		for($i=0;$i<count($detail);$i++){
			$sql  ="INSERT INTO cut_out_detail (id_cut_out,id_cat,fabric_code,fabric_desc,id_grouping,id_panel,number_from,number_to
					,embro,printing,heat_transfer,size,date_detail,cutting_output,reject,ok_cutt)
						VALUES (
							'$last_id',
							'{$detail[$i]['id']}',
							'{$detail[$i]['goods_code']}',
							'{$detail[$i]['itemdesc']}',
							'{$detail[$i]['idg']}',
							'{$detail[$i]['idp']}',
							'{$detail[$i]['numbering1']}',
							'{$detail[$i]['numbering2']}',
							'{$detail[$i]['embro']}',
							'{$detail[$i]['print']}',
							'{$detail[$i]['heat']}',
							'{$detail[$i]['size']}',
							'{$detail[$i]['dt']}',
							'{$detail[$i]['cutt']}',
							'{$detail[$i]['reject']}',
							'{$detail[$i]['okeCutt']}'
						)";
					 //print_r($sql);die();
			$result = $connect->query($sql);
			
			$sqlc = "UPDATE cut_out_category SET is_save = 'Y' WHERE id_cat = '{$detail[$i]['id']}'";
			$result = $connect->query($sqlc);
		}
		// print_r($sqlc);die();
		
		$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0","last_id": "'.$last_id.'"}';
		print_r($result);	
		$connect->close();
	}
	
    
	public function update($detail,$id_header)
	{
		
		// print_r($id_header);die(); 
		
		
		
		$connect = $this->connect();

		for($i=0;$i<count($detail);$i++){
			if($detail[$i]['id_det'] == '0'){

				$sql  ="INSERT INTO cut_out_detail (id_cut_out,id_cat,fabric_code,fabric_desc,id_grouping,id_panel,number_from,number_to
						,embro,printing,heat_transfer,size,date_detail,cutting_output,reject,ok_cutt)
							VALUES (
								'{$id_header}',
								'{$detail[$i]['id_cat']}',
								'{$detail[$i]['goods_code']}',
								'{$detail[$i]['itemdesc']}',
								'{$detail[$i]['idg']}',
								'{$detail[$i]['idp']}',
								'{$detail[$i]['numbering1']}',
								'{$detail[$i]['numbering2']}',
								'{$detail[$i]['embro']}',
								'{$detail[$i]['print']}',
								'{$detail[$i]['heat']}',
								'{$detail[$i]['size']}',
								'{$detail[$i]['dt']}',
								'{$detail[$i]['cutt']}',
								'{$detail[$i]['reject']}',
								'{$detail[$i]['okeCutt']}'
							)";
					
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
					

						$sqlc = "UPDATE cut_out_category SET is_save = 'Y' WHERE id_cat = '{$detail[$i]['id_cat']}'";
						// print_r($sqlc);
						$result = $connect->query($sqlc);					
					
					
					
						}
			
			else{
				$sql  ="UPDATE cut_out_detail SET
							 number_from = '{$detail[$i]['numbering1']}',
							 number_to = '{$detail[$i]['numbering2']}',
							 embro = '{$detail[$i]['embro']}',
							 printing = '{$detail[$i]['print']}',
							 heat_transfer = '{$detail[$i]['heat']}',
							 date_detail = '{$detail[$i]['dt']}',
							 cutting_output = '{$detail[$i]['cutt']}',
							 reject = '{$detail[$i]['reject']}',
							 ok_cutt = '{$detail[$i]['okeCutt']}'
						 WHERE id_cut_out_detail = '{$detail[$i]['id_det']}'";
				
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
		
			}
		}

		$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0", "last_id": "'.$last_id.'"}';
		print_r($result);		
	}

	// public function deletes($data)
	// {
	// 	$connect = $this->connect();
	// 	$id		  = $data->id;
	// 	$sql="UPDATE cut_in SET idDelete = '0' WHERE id_cut_in = '$id'";
		
	// 	$result = $connect->query($sql);
	// 	if(!$connect->query($sql))
	// 	{
	// 		$message = "Error :".$connect->error;
	// 		$respon  = "500";
	// 		//$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
	// 		//return result;			
			
	// 	}
	// 	else
	// 	{
	// 		$message = "SUKSES!";
	// 		$respon  = "200";
	// 		//$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
	// 		//return result;
	// 	};
	// 	$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
	// 	print_r($result);
	// }

}


?>