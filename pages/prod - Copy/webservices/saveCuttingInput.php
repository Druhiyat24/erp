<?php
session_start();

/*
format =  1 : INSERT
format =  2 : UPDATE
format =  3 : DELETE
*/
$data =(object)$_POST['data'];
$detail=$_POST['detail'];
/*    print_r($detail);
 die();  */ 
$code = $_POST['code'];
$format = $_POST['format'];
// $data->dtpicker	  	= date("Y-m-d", strtotime($data->dtpicker)); 
if($code == '1'){
	$Proses = new Proses();
		if($format == '1'){
			$key = $Proses->insert($data,$_SESSION['username']);
			$key = $Proses->insert_detail($detail,$_SESSION['username']);
			$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
			echo $result;
		}else if($format == '2'){
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
		

		$populasi_id = explode("_",$data->ws);
		$id_costing = $populasi_id[0];
		$id_jo = $populasi_id[1];

		$connect = $this->connect();	
		$dt = date("Y-m-d h:i:s");
		$sql  ="INSERT INTO cut_in (id_act_costing,username,dateinput,id_jo) 
				VALUES (
					'{$id_costing}',
					'{$username}',
					'{$dt}',
					'{$id_jo}'
				)";		
/* echo $sql;
die(); */				
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

	public function insert_detail($detail,$username)
	{
		// print_r($detail);die();
		$connect = $this->connect();
		$sqli = "SELECT max(id_cut_in) AS id FROM cut_in";
		$result = $connect->query($sqli);
		
		// print_r($detail);die();

		// print_r(mysqli_fetch_array($result));die();
		$row = mysqli_fetch_array($result);
		$last_id = $row['id'];

		// echo $last_id;die();
		// print_r($last_id);die();
		//  print_r(count($detail));die();
		for($i=0;$i<count($detail);$i++){
			$sql  ="INSERT INTO cut_in_detail (
					 id_cut_in
					,id_cost
					,id_so
					,id_so_det
					,id_item
					,id_bom_item
					,id_group
					,id_subgroup
					,id_type2
					,id_content
					,id_width
					,id_length
					,id_weight
					,id_color
					,id_desc
					,qty_yard
					,qty_roll
					,user_create
					) 
					VALUES (
						'{$last_id}',
						'{$detail[$i]['id_cost']}',
						'{$detail[$i]['id_so']}',
						'{$detail[$i]['id_so_det']}',
						'{$detail[$i]['id_item']}',
						'{$detail[$i]['id_bom_item']}',
						'{$detail[$i]['id_group']}',
						'{$detail[$i]['id_subgroup']}',
						'{$detail[$i]['id_type']}',
						'{$detail[$i]['id_contents']}',
						'{$detail[$i]['id_width']}',
						'{$detail[$i]['id_length']}',
						'{$detail[$i]['id_weight']}',
						'{$detail[$i]['id_color']}',
						'{$detail[$i]['id_desc']}',
						'{$detail[$i]['qty_yard']}',
						'{$detail[$i]['qty_roll']}',
						'{$username}'
					)";
					// print_r($sql);
					// echo "<br>";
			$result = $connect->query($sql);
		}
		// die();
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
	
    
	public function update_detail($data,$detail,$username)
	{
		$connect = $this->connect();
		$dt = date("Y-m-d h:i:s");
		// print_r(count($detail));die();
		for($i=0;$i<count($detail);$i++){
			$sql="UPDATE cut_in_detail SET 
				qty_yard		= '{$detail[$i]['qty_yard']}',
				qty_roll		= '{$detail[$i]['qty_roll']}',
				user_update		= '{$username}',
				d_update		= '{$dt}'
			WHERE id_cut_in			= '{$data->id}' 
		AND id_cut_in_detail 	= '{$detail[$i]['id_detail']}'";
	
/* echo "$sql  <br/>"; */
			 $result = $connect->query($sql);
		}
		return $result;
		die();
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