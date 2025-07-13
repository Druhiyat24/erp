<?php
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

$id_cost = $_POST['id_cost'];
$color = $_POST['clr'];
$url = $_POST['id'];
$id_panel = $_POST['panel'];
$id_item = $_POST['item'];
$id_jo = $_POST['jo'];
// print_r($url);die();
// $data->dtpicker	  	= date("Y-m-d", strtotime($data->dtpicker));
// print_r($format);die();
if($code == '1'){
	$Proses = new Proses();
		if($format == '1'){
			// echo('123');die();
			$key = $Proses->insert($url,$id_cost,$color,$id_panel,$id_item,$id_jo);
			// $key = $Proses->insert_detail($data);
			//$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
			echo $key;
		}else if($format == '2'){
			$key = $Proses->update($data,$id_cost,$color);
			// $key = $Proses->update_detail($data,$detail,$_SESSION['username']);
			$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
			echo $result;
		}else if($format == '3'){
			$key = $Proses->deletes($data);

			echo $key;
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
	
	public function insert($url,$id_cost,$color,$id_panel,$id_item,$id_jo)
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
		$result = $connect->query($q);

		

		// echo $q;die();
		// print_r($q2);die();
		$group_det = "SELECT MAX(id_group_det) AS id FROM prod_mark_entry_detail WHERE id_cost = '{$id_cost}' AND color = '{$color}'";
		$gd = $connect->query($group_det);

		// print_r(mysqli_fetch_array($result));die();
		$row = mysqli_fetch_array($gd);
		$det_group = $row['id'] + 1;
		while($row = mysqli_fetch_assoc($result)){
			// echo '123';
			$size = $row['size'];
			$qty = $row['qty'];
			
			$sql  ="INSERT INTO prod_mark_entry_detail (id_mark,id_cost,id_group,id_group_det,id_panel,id_item,id_jo,color,size,qty) 
					VALUES (
						'{$url}',
						'{$id_cost}',
						'{$last_id}',
						'{$det_group}',
						'{$id_panel}',
						'{$id_item}',
						'{$id_jo}',
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


	public function update($data,$id_cost,$color)
	{
		// print_r($data);die();
		$connect = $this->connect();	
		// $dt = date("Y-m-d h:i:s");
		$sql = "UPDATE prod_mark_entry_group SET
					gsm			= '{$data->gsm}',
					width		= '{$data->width}',
					b_cons_kg	= '{$data->bcg}'
				WHERE id_cost = '{$id_cost}' AND color = '{$color}'";		
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
	

	public function deletes($data)
	{

		$connect = $this->connect();
		$id = $data->id;
		$sqlG="DELETE FROM prod_mark_entry_group WHERE id_group = '$id'";
		$resultG = $connect->query($sqlG);


		$sql="DELETE FROM prod_mark_entry_detail WHERE id_group = '$id'";
		
/* 		
		echo $sql;
		die();  */
		
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