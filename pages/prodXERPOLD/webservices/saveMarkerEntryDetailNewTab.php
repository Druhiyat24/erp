<?php
include __DIR__.'/../../log_activity/log.php';
session_start();

/*
format =  1 : INSERT
format =  2 : UPDATE
format =  3 : DELETE
*/
$data =(object)$_POST['data'];
$detail = $_POST['d_api'];

// $populasi_rasio = $detail['array_rasio'][0];
// $populasi_spread = $detail['array_spread'][0];
// $populasi_sum_ratio = $detail['array_sum_ratio'][0];
// $populasi_length = $detail['array_length'][0];

// $det=$_POST['detail'];
// $dedet = base64_decode($det);
// $detail = json_decode($dedet);

// print_r($panel);die();
$code = $_POST['code'];
$format = $_POST['format'];

$id_cost = $_POST['id_cost'];
$color = $_POST['clr'];
$url = $_POST['id'];
$item = $_POST['id_item'];
$jo = $_POST['id_jo'];
$panel = $_POST['panel'];

// print_r($item);die();
// $data->dtpicker	  	= date("Y-m-d", strtotime($data->dtpicker));
// print_r($format);die();
if($code == '1'){
	$Proses = new Proses();
		if($format == '1'){
			// echo('123');die();
			$key = $Proses->insert($url,$id_cost,$color);
			// $key = $Proses->insert_detail($data);
			//$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
			echo $key;
		}else if($format == '2'){
			// echo '123';
			$key = $Proses->update($url,$id_cost,$color,$item,$jo,$panel,$_SESSION['username']);
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
	
	public function insert($url,$id_cost,$color)
	{
		// print_r($data);die();
		$connect = $this->connect();

		$sql2  ="INSERT INTO prod_mark_entry_group (id_mark_entry,id_cost,color) 
				VALUES (
					'{$url}',
					'{$id_cost}',
					'{$color}'
		)";		
			// echo $sql;
			// die();				
		$connect->query($sql2);

		$sqli = "SELECT max(id_group) AS id FROM prod_mark_entry_group";
		$result = $connect->query($sqli);

		// print_r(mysqli_fetch_array($result));die();
		$row = mysqli_fetch_array($result);
		$last_id = $row['id'];

		$q="SELECT 
				sd.color,
				sd.size,
				sd.qty,
				s.id_cost
			FROM so_det AS sd
			INNER JOIN (
				SELECT 
					s.id,
					s.id_cost 
				FROM so AS s
				WHERE s.cancel_h='N'
			) AS s ON s.id = sd.id_so
			WHERE s.id_cost = '{$id_cost}' AND sd.color = '{$color}'";
		$q2 = mysqli_query($conn_li,$q);
		$result2 = $connect->query($q);

		

		// echo $q;die();
		// print_r($q2);die();
		// $group_det = "SELECT MAX(id_group_det) AS id FROM prod_mark_entry_detail WHERE id_cost = '{$id_cost}' AND color = '{$color}'";
		// $gd = $connect->query($group_det);

		// print_r(mysqli_fetch_array($result));die();
		// $row = mysqli_fetch_array($gd);
		$det_group = 1;
		while($row = mysqli_fetch_assoc($result2)){
			// echo '123';
			$size = $row['size'];
			$qty = $row['qty'];
			
			$sql  ="INSERT INTO prod_mark_entry_detail (id_mark,id_cost,id_group,id_group_det,color,size,qty) 
					VALUES (
						'{$url}',
						'{$id_cost}',
						'{$last_id}',
						'{$det_group}',
						'{$color}',
						'{$size}',
						'{$qty}'
					)";		
			// echo $sql;
			// die();				
			$connect->query($sql);
		}
		

		// $q="";
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


	public function update($url,$id_cost,$color,$item,$jo,$panel,$username)
	{
		$connect = $this->connect();
		$dt = date("Y-m-d h:i:s");

		$sqlItem = "SELECT med.id_cost,med.color,med.id_item,med.id_jo FROM prod_mark_entry_detail AS med WHERE med.id_cost = '{$id_cost}' AND med.color = '{$color}'";
		$resultsqlItem = $connect->query($sqlItem);

		$rowSqlItem = mysqli_fetch_array($resultsqlItem);
		$cekItem = $rowSqlItem['id_item'];
		$cekJo = $rowSqlItem['id_jo'];
		// print_r($cekItem);die();

		if($cekItem == '0' && $cekJo == '0'){

			$sqll = "UPDATE prod_mark_entry_detail SET
						id_item		= '{$item}',
						id_jo		= '{$jo}',
						username_update = '{$username}',
						dateinput_update = '{$dt}'
					WHERE id_cost = '{$id_cost}'
					AND color = '{$color}'
					AND id_mark = '{$url}'";
		
				// echo $sql;die();
			$result = $connect->query($sqll);
		
		}
		else{

			$sql2  ="INSERT INTO prod_mark_entry_group (id_mark_entry,id_cost,color,username_create,dateinput_create) 
					VALUES (
						'{$url}',
						'{$id_cost}',
						'{$color}',
						'{$username}',
						'{$dt}'
			)";		
				// echo $sql;
				// die();				
			$connect->query($sql2);

			$sqli = "SELECT max(id_group) AS id FROM prod_mark_entry_group";
			$result = $connect->query($sqli);

			// print_r(mysqli_fetch_array($result));die();
			$row = mysqli_fetch_array($result);
			$last_id = $row['id'];

			$q="SELECT 
					sd.color,
					sd.size,
					sd.qty,
					s.id_cost
				FROM so_det AS sd
				INNER JOIN (
					SELECT 
						s.id,
						s.id_cost 
					FROM so AS s
					WHERE s.cancel_h='N'
				) AS s ON s.id = sd.id_so
				WHERE s.id_cost = '{$id_cost}' AND sd.color = '{$color}'";
			$q2 = mysqli_query($conn_li,$q);
			$result2 = $connect->query($q);

			

			// echo $q;die();
			// print_r($q2);die();
			// $group_det = "SELECT MAX(id_group_det) AS id FROM prod_mark_entry_detail WHERE id_cost = '{$id_cost}' AND color = '{$color}'";
			// $gd = $connect->query($group_det);

			// print_r(mysqli_fetch_array($result));die();
			// $row = mysqli_fetch_array($gd);
			$det_group = 1;
			while($row = mysqli_fetch_assoc($result2)){
				// echo '123';
				$size = $row['size'];
				$qty = $row['qty'];
				
				$sql  ="INSERT INTO prod_mark_entry_detail (id_mark,id_cost,id_group,id_group_det,id_panel,id_item,id_jo,color,size,qty,username_create,dateinput_create) 
						VALUES (
							'{$url}',
							'{$id_cost}',
							'{$last_id}',
							'{$det_group}',
							'{$panel}',
							'{$item}',
							'{$jo}',
							'{$color}',
							'{$size}',
							'{$qty}',
							'{$username}',
							'{$dt}'
						)";		
				// echo $sql;
				// die();				
				$connect->query($sql);
			}

			$sqlItem  ="INSERT INTO prod_mark_entry_sum (id_mark_entry,id_panel,id_item,id_jo,color,username_create,dateinput_create) 
					VALUES (
						'{$url}',
						'{$panel}',
						'{$item}',
						'{$jo}',
						'{$color}',
						'{$username}',
						'{$dt}'
					)";		
			// echo $sql;
			// die();				
			$connect->query($sqlItem);

		}



		if(!$result)
		{
			$message = "Error :".$connect->error;
			$respon  = "500";
			$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
		}					

		// $message = "SUKSES!";
		// $respon  = "200";			
		
		// $result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
		// print_r($result);	
		$connect->close();
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