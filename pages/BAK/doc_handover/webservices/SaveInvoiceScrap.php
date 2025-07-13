<?php 
session_start(); //$username
$username =$_SESSION['username'];
if(!ISSET($username)){
	$result = '{ "respon":"'.$respon.'", "message":"SESSION HABIS SILAHKAN LOGIN LAGI!","part" : "Validasi", "records":"0"}';
	exit;
}
/* ini_set('max_execution_time', '6000'); //300 seconds = 5 minutes
include '../../forms/journal_interface.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Credentials: true" );
header( "Access-Control-Allow-Methods: POST"); */
//file_get_contents(); date("Y-m-d", strtotime($data['from']));


error_reporting(E_ALL);
		$data = $_POST;
		$detail = $data['detail'];
		$header = $data['data'];
if($data['format'] == '1' ){
	$getListData = new proses();
	$date_invoice = date("Y-m-d", strtotime($header['date_invoice']));
	$no_invoice = $getListData -> generate_invoice($date_invoice);
	$insert_header 	= $getListData->insert_header($header,$no_invoice,$username,$date_invoice);
	$last_id_inv	= $getListData->get_last_id_invoice($no_invoice);
	$insert_detail 	= $getListData->insert_detail($detail,$last_id_inv,$username);
	$result = '{ "respon":"200", "message":"Data Berhasil Di Save","part" : "Finish", "records":"0"}';
	print_r($result);
}else if($data['format'] == '2'){
	$getListData = new proses();
	$date_invoice = date("Y-m-d", strtotime($header['date_invoice']));
	$no_invoice = $header['invno'];
	$update_header 	= $getListData->update_header($header,$no_invoice,$username);
	$update_detail 	= $getListData->update_detail($detail,$header['id'],$username);
	$result = '{ "respon":"200", "message":"Data Berhasil Di Update","part" : "Finish", "records":"0"}';
	print_r($result);
	
}
else{
	exit;
}
//else{
//	exit;
//}
class proses{
	public function connect()
		{
			include __DIR__ .'/../../../include/conn.php';
			return $con_new;
		}
		
	public function json_array($res)
    {
		
        $rows = array();
		if($res->num_rows > 0)
		{
			while($row = $res->fetch_array()){
				$rows[] = $row;
			}
		}

        return $rows;
	}		
		


	
	public function result($res)
    {
        $result = array();
		if($res->num_rows > 0)
		{
			while($row = $res->fetch_array()){
				$result[] = $row;
			}
		}
        return $result;
	}		
	
	public function check_query($connect,$my_result,$function){
				if(!$my_result)
		{
			$message = "Error :".$connect->error;
			$respon  = "500";
			$result = '{ "respon":"'.$respon.'", "message":"'.$message.'","part" : "'.$function.'", "records":"0"}';
			print_r($result);			
			exit;		
		}else{
			return 1;
		}
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
	
	
	
	public function get_last_id_invoice($my_inv){
		$sql = "SELECT id FROM shp_invoice_scrap_header ORDER BY id DESC LIMIT 1";
		$tmp_array = $this->eksekusi_query($sql,"get_last_id_invoice");
		return $tmp_array[0]['id'];
		
	}
	
	public function generate_invoice($date_nya){
		$pecah_date = explode("-",$date_nya);
		$years = $pecah_date[0];
		$sql = "SELECT COUNT(years)jlh,ifnull(seq,0)seq FROM shp_inv_scrap_pool WHERE years = '{$date_nya}'  AND code ='SCRP'";
		$tmp_array = $this->eksekusi_query($sql,"generate_invoice");		
		if($tmp_array[0]['jlh'] == '0' && $tmp_array[0]['seq'] == '0'){
			$seq = '1';
			$insert = "INSERT INTO shp_inv_scrap_pool (code,code_company,years,seq)VALUES('SCRP','NAG'
			,'{$years}','1')";
			$this->eksekusi_query_insert_update($insert,'generate_invoice');
		}else{
			$seq = intval($tmp_array[0]['seq']) + 1;
			$insert = "UPDATE shp_inv_scrap_pool SET seq = '{$seq}' WHERE years = $years";
			$this->eksekusi_query_insert_update($insert,'generate_invoice');			
		}
		$years_ = SUBSTR($years,0,2);
		return sprintf('%03d', $seq)."/INV/SCRAP-NAG/".$years_.sprintf('%02d', $pecah_date[1]);
		
	}	
	public function insert_header($my_header,$no_invoice,$username,$date_invoice){
		$insert = "INSERT INTO shp_invoice_scrap_header(type_invoice,invno,date_invoice,user_insert,fg_ppn,id_pterms,id_buyer,id_coa) VALUES('100','{$no_invoice}','{$date_invoice}','{$username}','{$my_header['fg_ppn']}','{$my_header['id_terms']}','{$my_header['id_supplier']}','{$my_header['id_coa']}')";
		$this->eksekusi_query_insert_update($insert,'insert_header');	
		return 1;
	}
	public function update_header($my_header,$no_invoice,$username){
		$update = "UPDATE shp_invoice_scrap_header 
					SET  id_pterms		= '{$my_header['id_terms']}'
						,fg_ppn			= '{$my_header['fg_ppn']}'
						,user_update 	= '{$username}'
						,id_coa 	= '{$my_header['id_coa']}'
						,d_update		= '".date('Y-m-d H:m:s')."'						
						WHERE id = '{$my_header['id']}'
						";
		$this->eksekusi_query_insert_update($update,'update_header');				
						
	}
	public function insert_detail($my_detail,$my_last_id,$username){
		
		$cnt = count($my_detail);
		$trigger = $cnt -1;
		$insert = "INSERT INTO shp_invoice_scrap_detail (id_inv_sc,id_bppb,id_item,unit,qty,price,user_insert,curr,id_jo,discount) VALUES ";
		for($i=0;$i<$cnt;$i++){
			if($i == $trigger){
				$penghubung = '';
			}else{
				$penghubung = ',';
			}
			$insert .="('{$my_last_id}','{$my_detail[$i]['id_bkb']}','{$my_detail[$i]['id_item']}','{$my_detail[$i]['unit']}','{$my_detail[$i]['qty']}','{$my_detail[$i]['price']}','{$username}','{$my_detail[$i]['curr']}','{$my_detail[$i]['id_jo']}','{$my_detail[$i]['discount']}'){$penghubung} ";

		}
			return $this->eksekusi_query_insert_update($insert,'insert_detail');
	}
	
	public function update_detail($my_detail,$my_last_id,$username){
		
		$cnt = count($my_detail);
		$trigger = $cnt - 1;
		for($i=0;$i<$cnt;$i++){
			$update = "UPDATE shp_invoice_scrap_detail SET
						 unit 			= '{$my_detail[$i]['unit']}'
						,qty 			= '{$my_detail[$i]['qty']}'
						,price 			= '{$my_detail[$i]['price']}'
						,curr 			= '{$my_detail[$i]['curr']}'
						,discount 		= '{$my_detail[$i]['discount']}'
						,user_update 	= '{$username}'
						,d_update		= '".date('Y-m-d H:m:s')."'
						WHERE id = {$my_detail[$i]['id']}
			";
/* 			echo $update;
			die(); */
			$this->eksekusi_query_insert_update($update,'update_detail');
		}
		return '1';
	}	
}


?>




