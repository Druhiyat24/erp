<?php
session_start();
     
/*
format =  1 : INSERT
format =  2 : UPDATE
format =  3 : DELETE
*/
$data = (object)$_POST['data'];
$detail = $_POST['detail'];
$code = $_POST['code'];
//$format = $_POST['format'];
// print_r($detail);die();

$format = 1;
if($code == '1'){
	$Proses = new Proses();
		if($format == '1'){
			// $key = $Proses->insert($data,$_SESSION['username']);
			$key = $Proses->insert_detail($detail,$_SESSION['username']);
			$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
			echo $result;
		}
		// else if($format == '2'){
		// 	$key = $Proses->update($data);
		// }
		else{
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
		$sql  ="INSERT INTO prod_cut_out_category (fabric_code,color,fabric_desc,id_grouping,id_cost,id_item,username,dateinput) 
					VALUES (
						'{$detail['code']}',
						'{$detail['color']}',
						'{$detail['description']}',
						'{$detail['grouping']}',
						-- '{$detail['panel']}',
						-- '{$detail['lot']}',
						'{$detail['id_cost']}',
						'{$detail['id_item']}',
						'{$username}',
						'{$dt}'
					)";
					// print_r($sql);die();
		$result = $connect->query($sql);

		return $result;		
		
		$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
		print_r($result);	
		$connect->close();
	}

}

?>