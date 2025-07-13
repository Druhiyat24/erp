<?php
include __DIR__.'/../../log_activity/log.php';
session_start();
include __DIR__ .'/../../../include/conn.php';

/*
format =  1 : INSERT
format =  2 : UPDATE
format =  3 : DELETE
*/
$data = (object)$_POST['data'];
$det = $_POST['detail'];
$dedet = base64_decode($det);
$detail = json_decode($dedet);
// print_r($detail);die();

$code = $_POST['code'];
$format = $_POST['format'];
// print_r($format);die();

if($code == '1'){
	$Proses = new Proses();
	if($format == '1'){
		$no_cut_qc = $Proses->get_no_cut_qc($data->date);
		$key = $Proses->insert($data,$_SESSION['username'],$no_cut_qc);
		$key = $Proses->insert_detail($detail);
		$result = '{ "responds":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
		echo $result;
	}else if($format == '2'){
		// $key = $Proses->update($_SESSION['username']);
		$key = $Proses->update_detail($detail);
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


	public function check_query($sql,$connect,$my_result,$function){
		if(!$my_result){
			$message = "Error :".$connect->error;
			$respon  = "500";
			$result = '{ "respon":"'.$respon.'", "message":"'.$message.'","part" : "'.$function.'", "records":"0"}';
			print_r($result);			
			exit;		
		}
		else{
			insert_log_nw($sql,$_SESSION['username']);
			return 1;
		}
	}


	public function result($res){
        $result = array();
		if($res->num_rows > 0){
			while($row = $res->fetch_array()){
				$result[] = $row;
			}
		}
        return $result;
	}


	public function eksekusi_query($sql,$function){
		$connect = $this->connect();
		$result = $connect->query($sql);
		$check_query = $this->check_query($sql,$connect,$result,$function);
		$tmp_array = $this->result($result);
		return $tmp_array;
	}


	public function eksekusi_query_insert_update($sql,$function){
		$connect = $this->connect();
		$result = $connect->query($sql);
		$check_query = $this->check_query($sql,$connect,$result,$function);
		return '1';
	}


	public function check_exist_pool($_category){
		$_arr = array();
		$_years = date("Y");
		$sql = "SELECT category,seq,years FROM prod_pool WHERE category = '{$_category}' AND years='{$_years}' LIMIT 1";

		$tmp_array = $this->eksekusi_query($sql,"check_exist_pool");
		if(count($tmp_array) > 0 ){
			$_arr = array(
				"category" 	=> $_category,
				"seq"	   	=> $tmp_array[0]["seq"],
				"years"		=> $tmp_array[0]["years"]
			);
		}else{
			$_arr = array(
				"category" 	=> $_category,
				"seq"	   	=> '0',
				"years"		=> date("Y")
			);	
		}
/* 		array_push($tmp_array,$_arr);
		print_r($_arr);
		die(); */
		return $_arr;
	}


	public function update_pool($_category,$_years){
		$_arr = array();
		$update = " UPDATE prod_pool SET seq=seq+1  WHERE category = '{$_category}' AND years = '{$_years}'
		";
		$this->eksekusi_query_insert_update($update,'update_pool');
		return 1;
	}		

	public function insert_pool($_category){
		$years =date("Y");
		$seq = 1;
		$update = " INSERT INTO prod_pool(category,years,seq)VALUES('{$_category}','{$years}','{$seq}')";
		$this->eksekusi_query_insert_update($update,'insert_pool');
		return 1;
	}


	public function get_no_cut_qc($_date){
		$category ="CUT/QC";
		$__d_out =  date("Y-m-d", strtotime($_date));
		$_pecah  = explode("-",$__d_out);
		
		
		$_years = $_pecah[0];
		$_month = $_pecah[1];
		$populasi_pool = $this->check_exist_pool($category);

		if((intval($populasi_pool['seq']) > 0)){
			if(intval($populasi_pool['years']) == intval($_years)){
				$years =$populasi_pool["years"];
				$seq   =sprintf('%06d', (intval($populasi_pool["seq"])+ 1));
				$this->update_pool($category,$years);
			}
			else{
				$years =$_years;
				$this->insert_pool($category);
				$seq   =sprintf('%06d', (1));
				
			}
		}
		else{
			$years = $populasi_pool["years"];
			$seq = sprintf('%06d', (intval($populasi_pool["seq"])+ 1));
			$this->insert_pool($category);
		}
			
		//$seq   =sprintf('%06d', (intval($populasi_pool["seq"])+ 1));	
		$seq_years = substr($years,2,2);
		$_no_sew_in = $category."/".$_month.$seq_years."/".$seq;
		return $_no_sew_in;
	}


	public function insert($data,$username,$no_cut_qc){
		$population_id = explode("_",$data->ws);
		$id_costing = $population_id[0];
		$color = $population_id[1];
		// print_r($id_costing);die();

		$connect = $this->connect();	
		$dt = date("Y-m-d h:i:s");

		$sql = "INSERT INTO prod_cut_qc (id_ws_cut_qc,id_cost,color,no_cut_qc,username_create,dateinput_create) 
				VALUES (
					'{$data->ws}',
					'{$id_costing}',
					'{$color}',
					'{$no_cut_qc}',
					'{$username}',
					'{$dt}'
				)
		";
		// echo $sql;die();

		// $this->eksekusi_query_insert_update($sql,'insert');
		$result = $connect->query($sql);
		if(!$result){
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


	public function insert_detail($detail){
		// print_r($detail);die();
		$connect = $this->connect();

		$sqli = "SELECT max(id_cut_number) AS id FROM prod_cut_number";
		$result = $connect->query($sqli);

		$row = mysqli_fetch_array($result);
		$last_id = $row['id'];

		for($i=0;$i<count($detail);$i++){
			// echo('123');die();
			$detail[$i] = (array)$detail[$i];

			$qtyQC = $detail[$i]['qty_input_qc_val'] - $detail[$i]['reject_qc_qty_val'];

			$sql  = "INSERT INTO prod_cut_qc_detail (
						id_cut_qc,
						id_cut_number_detail,
						id_cut_out_detail,
						id_cost,
						id_so_det,
						id_item,
						color,
						qty_input_qc,
						qty_reject_qc,
						qty_cut_qc,
						remarks,
						approve
					) 
					VALUES (
						'{$last_id}',
						'{$detail[$i]['id_cut_number_detail']}',
						'{$detail[$i]['id_cut_out_detail']}',
						'{$detail[$i]['id_cost']}',
						'{$detail[$i]['id_so_det']}',
						'{$detail[$i]['id_item']}',
						'{$detail[$i]['color']}',
						'{$detail[$i]['qty_input_qc_val']}',
						'{$detail[$i]['reject_qc_qty_val']}',
						'{$qtyQC}',
						'{$detail[$i]['remarks_val']}',
						'{$detail[$i]['check_val']}'
					)
			";

			$result = $connect->query($sql);
			// $this->eksekusi_query_insert_update($sql,'insert_detail');

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
	
    
	public function update_detail($detail,$username){
		// print_r($detail);die();
		$connect = $this->connect();
		$dt = date("Y-m-d h:i:s");
		// print_r(count($detail));die();
		for($i=0;$i<count($detail);$i++){
			$detail[$i] = (array)$detail[$i];

			$qtyQC = $detail[$i]['qty_input_qc_val'] - $detail[$i]['reject_qc_qty_val'];

			$sql="UPDATE prod_cut_qc_detail SET
					qty_input_qc		= '{$detail[$i]['qty_input_qc_val']}',
					qty_reject_qc		= '{$detail[$i]['reject_qc_qty_val']}',
					qty_cut_qc			= '{$qtyQC}',
					remarks				= '{$detail[$i]['remarks_val']}',
					approve				= '{$detail[$i]['check_val']}'
				WHERE id_cut_qc_detail 	= '{$detail[$i]['id_cut_qc_detail']}'
			";
			
			// echo "$sql  <br/>";
			$connect->query($sql);
			$this->eksekusi_query_insert_update($sql,'update_detail');
		}
		return 1;
		
		// $result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
		// print_r($result);
		$connect->close();	
	}


}


?>