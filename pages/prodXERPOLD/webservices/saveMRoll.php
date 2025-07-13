<?php
include __DIR__.'/../../log_activity/log.php';
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
$color = $_POST['color'];
$item = $_POST['item'];

// print_r($item);die();
$sql = "SELECT 
	mr.fabric_color AS color, 
	mr.id_item AS item
	FROM prod_m_roll AS mr
	WHERE mr.fabric_color = '{$color}' 
	AND mr.id_item = '$item'
";
// echo $sql;die();
$stmt = mysql_query($sql);
$row = mysql_fetch_array($stmt);


// print_r($row['color']);die();

if($row['color'] == $color && $row['item'] == $item){
	// echo "Update";
	$format = '2';
}
else{
	// echo "Insert";
	$format = '1';
}

// print_r($user);die();

if($code == '1'){
	$Proses = new Proses();
		if($format == '1'){
			// $key = $Proses->insert($data,$_SESSION['username']);
			$key = $Proses->insert($detail,$item,$_SESSION['username']);
			$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
			echo $result;
		}else if($format == '2'){
			// $key = $Proses->update($data,$_SESSION['username']);
			$key = $Proses->update_detail($detail,$_SESSION['username']);
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
		}
		else{
			insert_log_nw($sql,$_SESSION['username']);
			return 1;
		}
	}


	public function eksekusi_query_insert_update($sql,$function){
		$connect = $this->connect();
		$result = $connect->query($sql);
		$check_query = $this->check_query($sql,$connect,$result,$function);
		return '1';
	}


	public function insert($detail,$item,$username)
	{
		// print_r($detail);die();
		$connect = $this->connect();
		$dt = date("Y-m-d h:i:s");

		for($i=0;$i<count($detail);$i++){
			// echo('123');die();
			$detail[$i] = (array)$detail[$i];

			$sql  = "INSERT INTO prod_m_roll (
						id_cost,
						id_item,
						id_number,
						fabric_code,
						fabric_name,
						fabric_color,
						lot,
						roll_no,
						qty_sticker,
						#fabric_used,
						qty_cutting,
						cons_ws,
						cons_m,
						#cons_act,
						#cons_balance,
						#percentage,
						binding,
						actual_balance,
						#actual_total,
						#short_roll,
						spread_sheet,
						#ratio,
						#qty_pcs,
						#fabric_total_act,
						#majun,
						#majun_kg,
						ampar,
						#total,
						#used_total,
						l_fabric,
						username_create,
						dateinput_create
					) 
					VALUES (
						'{$detail[$i]['id_cost']}',
						'{$item}',
						'{$detail[$i]['id_number']}',
						'{$detail[$i]['fabric_code']}',
						'{$detail[$i]['fabric_name']}',
						'{$detail[$i]['fabric_color']}',
						'{$detail[$i]['lot']}',
						'{$detail[$i]['roll']}',
						'{$detail[$i]['qty_sticker_val']}',
						#'{$detail[$i]['fabric_use_val']}',
						'{$detail[$i]['qty_cut_val']}',
						'{$detail[$i]['cons_ws_val']}',
						'{$detail[$i]['cons_m_val']}',
						#'{$detail[$i]['cons_act_val']}',
						#'{$detail[$i]['cons_bal_val']}',
						#'{$detail[$i]['percent_val']}',
						'{$detail[$i]['bind_val']}',
						'{$detail[$i]['act_bal_val']}',
						#'{$detail[$i]['act_tot_val']}',
						#'{$detail[$i]['short_roll_val']}',
						'{$detail[$i]['spread_sheet_val']}',
						#'{$detail[$i]['ratio_val']}',
						#'{$detail[$i]['qty_pcs_val']}',
						#'{$detail[$i]['fabric_tot_act_val']}',
						#'{$detail[$i]['majun_val']}',
						#'{$detail[$i]['majun_kg_val']}',
						'{$detail[$i]['ampar_val']}',
						#'{$detail[$i]['total_val']}',
						#'{$detail[$i]['use_total_val']}',
						'{$detail[$i]['l_fabric_val']}',
						'{$username}',
						'{$dt}'
					)
			";

			$result = $connect->query($sql);
			// $this->eksekusi_query_insert_update($sql,'insert');
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
		print_r($result);	
		$connect->close();
	}
	
    
	public function update_detail($detail,$username)
	{
		// print_r($username);die();
		$connect = $this->connect();
		$dt = date("Y-m-d h:i:s");
		// print_r(count($detail));die();
		for($i=0;$i<count($detail);$i++){
			$detail[$i] = (array)$detail[$i];

			$sql="UPDATE prod_m_roll SET
					#fabric_used		= '{$detail[$i]['fabric_use_val']}',
					qty_cutting			= '{$detail[$i]['qty_cut_val']}',
					cons_ws				= '{$detail[$i]['cons_ws_val']}',
					cons_m				= '{$detail[$i]['cons_m_val']}',
					#cons_act			= '{$detail[$i]['cons_act_val']}',
					#cons_balance		= '{$detail[$i]['cons_bal_val']}',
					#percentage			= '{$detail[$i]['percent_val']}',
					binding				= '{$detail[$i]['bind_val']}',
					actual_balance		= '{$detail[$i]['act_bal_val']}',
					#actual_total		= '{$detail[$i]['act_tot_val']}',
					#short_roll			= '{$detail[$i]['short_roll_val']}',
					spread_sheet		= '{$detail[$i]['spread_sheet_val']}',
					#qty_pcs			= '{$detail[$i]['qty_pcs_val']}',
					#fabric_total_act	= '{$detail[$i]['fabric_tot_act_val']}',
					#majun				= '{$detail[$i]['majun_val']}',
					#majun_kg			= '{$detail[$i]['majun_kg_val']}',
					ampar				= '{$detail[$i]['ampar_val']}',
					#total				= '{$detail[$i]['total_val']}',
					#used_total			= '{$detail[$i]['use_total_val']}',
					l_fabric			= '{$detail[$i]['l_fabric_val']}',
					username_update		= '{$username}',
					dateinput_update	= '{$dt}'
				WHERE id_m_roll = '{$detail[$i]['id_m_roll']}'
			";
			
			// echo "$sql  <br/>";
			$connect->query($sql);
			$this->eksekusi_query_insert_update($sql,'update');
		}
		// die();
		return 1;
		
		// $result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
		print_r($result);
		// $connect->close();	
	}

}


?>