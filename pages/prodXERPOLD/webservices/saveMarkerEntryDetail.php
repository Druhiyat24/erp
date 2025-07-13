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
// print_r($detail);die();

$populasi_rasio = array();
$populasi_spread= array();
$populasi_sum_ratio= array();
$populasi_length= array();
for($i=0;$i<count($detail);$i++){
	$populasi_rasio[$i] 	= $detail[$i]['array_rasio'][0];
	$populasi_spread[$i] 	= $detail[$i]['array_spread'][0];
	$populasi_sum_ratio[$i] = $detail[$i]['array_sum_ratio'][0];
	$populasi_length[$i] 	= $detail[$i]['array_length'][0];
}

/* $populasi_rasio = $detail['array_rasio'][0];
$populasi_spread = $detail['array_spread'][0];
$populasi_sum_ratio = $detail['array_sum_ratio'][0];
$populasi_length = $detail['array_length'][0]; */

// $det=$_POST['detail'];
// $dedet = base64_decode($det);
// $detail = json_decode($dedet);

// print_r($panel);die();
$code = $_POST['code'];
$format = $_POST['format'];

$id_cost = $_POST['id_cost'];
$color = $_POST['clr'];
$url = $_POST['id'];
$panel = $_POST['tab'];
$item = $_POST['item'];
$jo = $_POST['jo'];
// print_r($panel);die();
// $data->dtpicker	  	= date("Y-m-d", strtotime($data->dtpicker));
// print_r($format);die();
if($code == '1'){
	$Proses = new Proses();
		if($format == '1'){
			// echo('123');die();
			$key = $Proses->insert($url,$id_cost,$color,$panel,$item,$jo,$_SESSION['username']);
			// $key = $Proses->insert_detail($data);
			//$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
			echo $key;
		}else if($format == '2'){
			// echo '123';
			$key = $Proses->update_spread($populasi_spread,$_SESSION['username']);
			$key = $Proses->update_rasio($populasi_rasio,$_SESSION['username']);
			$key = $Proses->update_sum($populasi_sum_ratio,$_SESSION['username']);
			$key = $Proses->update_length($populasi_length,$url,$_SESSION['username']);
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

	
	public function insert($url,$id_cost,$color,$panel,$item,$jo,$username)
	{
		// print_r($data);die();
		$connect = $this->connect();

		// $sqlw = "SELECT * FROM prod_mark_entry_detail WHERE id_cost = '$id_cost' AND color = '$color'";
		// $result = $connect->query($sqlw);
		// $cek = mysqli_fetch_array($result);
		// $cost = $cek['id_cost'];
		// $warna = $cek['color'];
		// if($cost == $id_cost AND $warna == $color ){
		// 	echo "Data Already Exsisting";
		// }
		// else{
			$dt = date("Y-m-d h:i:s");
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
			// $this->eksekusi_query_insert_update($sql2,'insert');

			$sqli = "SELECT max(id_group) AS id FROM prod_mark_entry_group";
			$result = $connect->query($sqli);

			// print_r(mysqli_fetch_array($result));die();
			$row = mysqli_fetch_array($result);
			$last_id = $row['id'];

			$q="SELECT 
					sd.id AS id_so_det,
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
				WHERE s.id_cost = '{$id_cost}' AND sd.color = '{$color}' AND sd.cancel='N'
				ORDER BY sd.id ASC";
			$q2 = mysqli_query($conn_li,$q);
			$result = $connect->query($q);

			// echo $q;die();
			// print_r($q2);die();
			$id_group_det = 1;
			while($row = mysqli_fetch_assoc($result)){
				// echo '123';
				$size = addslashes($row['size']);
				$qty = $row['qty'];

				$sql  ="INSERT INTO prod_mark_entry_detail (id_mark,id_cost,id_group,id_group_det,id_panel,id_item,id_jo,color,size,qty,username_create,dateinput_create) 
						VALUES (
							'{$url}',
							'{$id_cost}',
							'{$last_id}',
							'{$id_group_det}',
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
				// $this->eksekusi_query_insert_update($sql,'insert_detail');
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
			// $this->eksekusi_query_insert_update($sqlItem,'insert_sum');
		// }

		

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


	public function update_spread($populasi_spread,$username)
	{
		$connect = $this->connect();
		$dt = date("Y-m-d h:i:s");
		// print_r($populasi_spread);die();

		for($i = 0; $i < count($populasi_spread); $i++){

			foreach($populasi_spread[$i] as $val => $key){
	
				$sql = "UPDATE prod_mark_entry_group SET
						spread = '{$key['spread']}',
						username_update = '{$username}',
						dateinput_update = '{$dt}'
					WHERE id_group = '{$key['id']}'";
		
				// echo $sql;die();
				$result = $connect->query($sql);
				$this->eksekusi_query_insert_update($sql,'update_spread');
			}
		
		}

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
	

	public function update_rasio($populasi_rasio,$username)
	{
		$connect = $this->connect();
		$dt = date("Y-m-d h:i:s");
		// print_r($populasi_rasio);die();

		for($j = 0; $j < count($populasi_rasio); $j++){

			$last_kurang =0;
			$populasi_size = array();
			foreach($populasi_rasio[$j] as $val => $key){
						
				$q = "SELECT 
						meg.spread,
						med.id_mark_detail,
						med.size,
						meg.id_group,
						meg.color,
						#med.qty
						if(med.id_group_det = '1',med.qty,med.kurang)qty,
						med.id_group_det
					FROM prod_mark_entry_detail AS med
					INNER JOIN prod_mark_entry_group AS meg ON med.id_group = meg.id_group
					WHERE med.id_mark_detail ='{$key['id']}' ORDER BY med.id_mark_detail ASC
				";
						
				$result = $connect->query($q);
				$row = mysqli_fetch_array($result);
				$spread = $row['spread'];
				$qty = $row['qty'];	
				
				$spread_det = $spread * $key['ratio'];
				if($row['id_group_det'] == '1' ){
					$kurang = $spread_det - $qty;
					$last_kurang = $spread_det - $qty;
				}else{
					
					$kurang = $spread_det - $last_kurang;
					$last_kurang = $kurang;
					 // echo $kurang; die();
				}
	
				$sql = "UPDATE prod_mark_entry_detail SET
						ratio = '{$key['ratio']}',
						spread = '$spread_det',
						kurang = '$kurang',
						username_update = '{$username}',
						dateinput_update = '{$dt}'
					WHERE id_mark_detail = '{$key['id']}'";
				// echo $sql;die();
	
				$result = $connect->query($sql);
				$this->eksekusi_query_insert_update($sql,'update_detail');
			}

		}


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


	public function update_sum($populasi_sum_ratio,$username)
	{
		$connect = $this->connect();
		$dt = date("Y-m-d h:i:s");
		// print_r($populasi_sum_ratio);die();

		for($k = 0; $k < count($populasi_sum_ratio); $k++){

			foreach($populasi_sum_ratio[$k] as $val => $key){
				/* print_r($key); */
				$sql = "UPDATE prod_mark_entry_group SET
						unit_yds = '{$key['yds']}',
						unit_inch = '{$key['inch']}'
					WHERE id_group = '{$key['id']}'";
		
				// echo $sql;die();
				$result = $connect->query($sql);
				$this->eksekusi_query_insert_update($sql,'update_group');
			}

		}

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


	public function update_length($populasi_length,$username)
	{
		$connect = $this->connect();
		$dt = date("Y-m-d h:i:s");
		// print_r($populasi_length);die();

		for($l = 0; $l < count($populasi_length); $l++){

			foreach($populasi_length[$l] as $val => $key){
	 
				$sql = "UPDATE prod_mark_entry_sum SET
						gsm = '{$key['gsm']}',
						width = '{$key['width']}',
						allowance = '{$key['allow']}',
						b_cons_kg = '{$key['bcg']}',
						username_update = '{$username}',
						dateinput_update = '{$dt}'
					WHERE id_mark_entry = '{$key['id_mark_entry']}'
					AND id_panel = '{$key['id_panel']}'
					AND id_item = '{$key['id_item']}'
					AND color = '{$key['color']}'
				";
		
				// echo $sql;die();
				$result = $connect->query($sql);
				$this->eksekusi_query_insert_update($sql,'update_sum');
			}

		}

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

}


?>