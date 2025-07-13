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

// print_r($data);die();
$code = $_POST['code'];
$format = $_POST['format'];

// print_r($format);die();
if($code == '1'){
	$Proses = new Proses();
		if($format == '1'){
			$no_dc_join = $Proses->get_no_dc_join($data->date);
			$key = $Proses->insert($data,$_SESSION['username'],$no_dc_join);
			$key = $Proses->insert_detail($detail);
			$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
			echo $result;
		}else if($format == '2'){
			$key = $Proses->update($data,$_SESSION['username']);
			$key = $Proses->update_detail($detail);
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

	public function connect(){
		include __DIR__ .'/../../../include/conn.php';
		return $conn_li;
	}


	public function check_error($result,$connect){
		if(!$result){
			$message = "Error :".$connect->error;
			$respon  = "500";
			$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
			print_r($result);			
			exit;	
		}
		else{
			$check = "ok";
			return $check;
		}
	}
	

	public function check_query($connect,$my_result,$function){
		if(!$my_result){
			$message = "Error :".$connect->error;
			$respon  = "500";
			$result = '{ "respon":"'.$respon.'", "message":"'.$message.'","part" : "'.$function.'", "records":"0"}';
			print_r($result);			
			exit;		
		}
		else{
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
		$check_query = $this->check_query($connect,$result,$function);
		$tmp_array = $this->result($result);
		return $tmp_array;
	}


	public function eksekusi_query_insert_update($sql,$function){
		$connect = $this->connect();
		$result = $connect->query($sql);
		$check_query = $this->check_query($connect,$result,$function);
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


	public function get_no_dc_join($_date){
		$category ="DC/JOIN";
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
		$_no_dc_join = $category."/".$_month.$seq_years."/".$seq;
		return $_no_dc_join;
	}


	public function insert($data,$username,$no_dc_join){
		// print_r($data);die();
		$connect = $this->connect();	
		$dt = date("Y-m-d h:i:s");
		$sql  ="INSERT INTO prod_dc_join 
				(no_dc_join,notes,id_cost,username_create,dateinput_create) 
				VALUES (
					'{$no_dc_join}',
					'{$data->notes}',
					'{$data->ws}',
					'{$username}',
					'{$dt}'
				)
		";		
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

	public function insert_detail($detail){
		// print_r($detail);die();
		$connect = $this->connect();
		$sqli = "SELECT max(id_dc_join) AS id FROM prod_dc_join";
		$result = $connect->query($sqli);

		// print_r(mysqli_fetch_array($result));die();
		$row = mysqli_fetch_array($result);
		$last_id = $row['id'];

		for($i=0;$i<count($detail);$i++){
			// echo('123');die();
			$detail[$i] = (array)$detail[$i];
			
			$qtyDCJoin = $detail[$i]['qty_input_join_val'] - $detail[$i]['reject_dc_join_val'];
			
			$sql  = "INSERT INTO prod_dc_join_detail (
						id_dc_join,
						id_cut_out_detail,
						id_cut_number_detail,
						id_cut_qc_detail,
						id_so_det,
						id_item,
						qty_input_dc_join,
						qty_reject_dc_join,
						qty_dc_join
					) 
					VALUES (
						'{$last_id}',
						'{$detail[$i]['id_cut_out_detail']}',
					    '{$detail[$i]['id_cut_number_detail']}',
					    '{$detail[$i]['id_cut_qc_detail']}',
						'{$detail[$i]['id_so_det']}',
						'{$detail[$i]['id_item']}',
						'{$detail[$i]['qty_input_join_val']}',
						'{$detail[$i]['reject_dc_join_val']}',
						'{$qtyDCJoin}'
					)
			";	
				//print_r($sql);die();
				// echo $sql;die();
				$result = $connect->query($sql);
			
			// print_r($detail[$i]);die();
			// $return = $detail[$i]['qty_received'] - $detail[$i]['need_val'];
			
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


	public function update($data,$username){
		// print_r($data);die();
		$connect = $this->connect();	
		$dt = date("Y-m-d h:i:s");
		$sql  ="UPDATE prod_dc_join SET
					notes				= '{$data->notes}',
					username_update		= '{$username}',
					dateinput_update	= '{$dt}'
				WHERE id_dc_join 		= '{$data->id}'
		";		
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
	
    
	public function update_detail($detail){
		// print_r($detail);die();
		$connect = $this->connect();
		// print_r(count($detail));die();
		for($i=0;$i<count($detail);$i++){
			$detail[$i] = (array)$detail[$i];
			
			$qtyDCJoin = $detail[$i]['qty_input_join_val'] - $detail[$i]['reject_dc_join_val'];
			
			$sql = "UPDATE prod_dc_join_detail SET 
						qty_input_dc_join	= '{$detail[$i]['qty_input_join_val']}',
						qty_reject_dc_join	= '{$detail[$i]['reject_dc_join_val']}',
						qty_dc_join			= '{$qtyDCJoin}'
					WHERE id_dc_join_detail = '{$detail[$i]['id_dc_join_detail']}'
			";
			
			/* echo "$sql  <br/>"; */
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