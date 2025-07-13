<?php
include __DIR__.'/../../log_activity/log.php';
session_start();
$Proses = new Proses();
/*
format =  1 : INSERT
format =  2 : UPDATE
format =  3 : DELETE
*/
$data  = $_POST['header'];
$header= json_decode($data);
$det   = $_POST['detail'];
$detail= json_decode($det);
$code  = $_POST['code'];
/* print_r($detail);
die(); */
$format= $_POST['format'];
 if($_POST['detail'] == "[{}]"){

		$result = '{ "respon":"'.'205'.'", "message":"'.'No Data '.'", "records":"0"}';   
		echo $result;	
		die();
} 
	//validasi_header
	if($header->id_line == ''){
		$result = '{ "respon":"'.'204'.'", "message":"'.'Line Must Be Fill'.'", "records":"0"}';   
		echo $result;	
		die();
	}	
	if($header->id_cost == ''){
		$result = '{ "respon":"'.'204'.'", "message":"'.'WS Must Be Fill'.'", "records":"0"}';   
		echo $result;		
		die();
	}
	if($header->time == ''){
		$result = '{ "respon":"'.'204'.'", "message":"'.'Time Output Must Be Fill'.'", "records":"0"}';
		echo $result;		
		die();
	}if($header->date_output == ''){
		$result = '{ "respon":"'.'204'.'", "message":"'.'Date Output Must Be Fill'.'", "records":"0"}';
		echo $result;
		die();		
	}	



//validasi
	//check Format Inputan
	$ii=0;
for($i=0;$i<count($detail);$i++){
	$ii = $i+1;
	$_det = $detail[$i];
    $element = $_det->is_qty;
    if (is_numeric($element)) {
        $x= 'TRUE';
    } else {
		$x = 'FALSE';
		$result = '{ "respon":"'.'201'.'", "message":"'.'OK'.'", "records":"0","baris":"'.$ii.'"}';   
		echo $result;
		die();
    }	
}
	//check Format Inputan rpr
	$ii=0;
for($i=0;$i<count($detail);$i++){
	$ii = $i+1;
	$_det = $detail[$i];
    $element = $_det->is_rpr;
    if (is_numeric($element)) {
        $x= 'TRUE';
    } else {
		$x = 'FALSE';
		$result = '{ "respon":"'.'207'.'", "message":"'.'OK'.'", "records":"0","baris":"'.$ii.'"}';   
		echo $result;
		die();
    }	
}


$ii = 0;
	//check Balance
for($i=0;$i<count($detail);$i++){ 
	$ii = $i+1;
	$_det = $detail[$i];
	//$qty =$_det->is_qty;
	$qty =$_det->is_qty + $_det->is_rpr;	
	$balance = $_det->is_balance;
/* 	echo $balance." > ".$qty;
	die(); */
    if ($balance >= $qty) {
        $x= 'TRUE';
    } else {
		$result = '{ "respon":"'.'202'.'", "message":"'.'OK'.'", "records":"0","baris":"'.$ii.'"}';    
		echo $result;		
		die();
    }	
}

	//check qty must >= 0
$ii = 0;	
for($i=0;$i<count($detail);$i++){
	$ii = $i+1;
	$_det = $detail[$i];
	$qty =$_det->is_qty;
	$balance = $_det->is_balance;
/*  	echo $qty." > ".'0';
	die();  */
    if ($qty >= 0 ) {
        $x= 'TRUE';
    } else {
		$result = '{ "respon":"'.'203'.'", "message":"'.'OK'.'", "records":"0","baris":"'.$ii.'"}';    
		echo $result;		
		die();
    }	
}

	//check qty rpr >= 0
$ii = 0;	
for($i=0;$i<count($detail);$i++){
	$ii = $i+1;
	$_det = $detail[$i];
	$qty_rpr =$_det->is_rpr;
	$balance = $_det->is_balance;
/*  	echo $qty." > ".'0';
	die();  */
    if ($qty_rpr >= 0 ) {
        $x= 'TRUE';
    } else {
		$result = '{ "respon":"'.'208'.'", "message":"'.'OK'.'", "records":"0","baris":"'.$ii.'"}';    
		echo $result;		
		die();
    }	
}

//check qty must better than rpr
//$ii = 0;
//for($i=0;$i<count($detail);$i++){
//	$ii = $i+1;
//	$_det = $detail[$i];
//	$qty =$_det->is_qty;
//	$rpr = $_det->is_rpr;
///*  	echo $qty." > ".'0';
//	die();  */
//    if ($qty >= $rpr ) {
//        $x= 'TRUE';
//    } else {
//		$result = '{ "respon":"'.'206'.'", "message":"'.'OK'.'", "records":"0","baris":"'.$ii.'"}';    
//		echo $result;		
//		die();
//    }	
//}


	//check qty current must >= qty next
$ii = 0;	
for($i=0;$i<count($detail);$i++){
	$ii = $i+1;
	$_det = $detail[$i];
	$qty =$_det->is_qty;
	$next_qty = $_det->is_next_qty;
/*  	echo $qty." > ".'0';
	die();  */
    if ($qty >= $next_qty ) {
        $x= 'TRUE';
    } else {
		$result = '{ "respon":"'.'206'.'", "message":"'.'OK'.'", "records":"0","baris":"'.$ii.'"}';    
		echo $result;		
		die();
    }	
}

if($code == '1'){
		if($format == '1'){
			$no_sew_qc_final_out 	= $Proses->get_no($header->date_output);		
			$key 		= $Proses->insert($header,$_SESSION['username'],$no_sew_qc_final_out);
			$id_sew_qc_final_out_header  = $Proses->get_id($no_sew_qc_final_out);
			$key 		= $Proses->insert_detail($header,$detail,$id_sew_qc_final_out_header,$_SESSION['username']);
			$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0","id_sew_qc_final_out_header" : "'.$id_sew_qc_final_out_header.'"}';
			echo $result;
			die();
		}else if($format == '2'){ 
			$id_sew_qc_final_out_header = $header->id_sew_qc_final_out_header;
			$key = $Proses->update($header,$_SESSION['username']);
			$key = $Proses->update_detail($header,$detail,$_SESSION['username']);
			$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0","id_sew_qc_final_out_header" : "'.$id_sew_qc_final_out_header.'"}';
			echo $result;
			die();
		}else{
			exit;
		}
}
else{
	exit;
}

$result = '{ "respon":"'.'200'.'", "message":"'.'Data Berhasil disimpan'.'", "records":"0"}';
print_r($result);
//  echo $result;
// die(); 
class Proses {
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
	
	public function check_query($sql,$connect,$my_result,$function){
				if(!$my_result)
		{
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
	public function flookup_new($fld,$tbl,$criteria){
		include __DIR__ .'/../../../include/conn.php';
		if ($fld!="" AND $tbl!="" AND $criteria!=""){	
			$quenya = "Select $fld as namafld from $tbl Where $criteria ";
			$strsql = mysql_query($quenya);
			if (!$strsql) { 
				die($quenya. mysql_error()); 
			}
			$rs = mysql_fetch_array($strsql);
			if (mysql_num_rows($strsql)=='0'){
				$hasil="";
			}
			else{
				$hasil=$rs['namafld'];
			}
			return $hasil;
		}
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
		$update = "UPDATE prod_pool SET seq=seq+1  WHERE category = '{$_category}' AND years = '{$_years}'
		";
		$this->eksekusi_query_insert_update($update,'update_pool');
		return 1;
	}		

	public function insert_pool($_category){
		$years =date("Y");
		$seq = 1;
		$update = "INSERT INTO prod_pool(category,years,seq)VALUES('{$_category}','{$years}','{$seq}')";
		$this->eksekusi_query_insert_update($update,'insert_pool');
		return 1;
	}
	
		
	
	public function get_no($_date_output){
		$category ="SEW/QCFN/OUT";
		$__d_out =  date("Y-m-d", strtotime($_date_output));
		$_pecah  = explode("-",$__d_out);
		
		
		$_years = $_pecah[0];
		$_month = $_pecah[1];
		$populasi_pool = $this->check_exist_pool($category);

		if((intval($populasi_pool['seq']) > 0)){
			if(intval($populasi_pool['years']) == intval($_years)){
				$years =$populasi_pool["years"];
				$seq   =sprintf('%06d', (intval($populasi_pool["seq"])+ 1));
				$this->update_pool($category,$years);
			}else{
				$years =$_years;
				$this->insert_pool($category);
				$seq   =sprintf('%06d', (1));
				
			}
		}else{
				$years =$populasi_pool["years"];
				$seq   =sprintf('%06d', (intval($populasi_pool["seq"])+ 1));
				$this->insert_pool($category);
		}
			
			//$seq   =sprintf('%06d', (intval($populasi_pool["seq"])+ 1));	
			$seq_years = substr($years,2,2);
			$_no_sew_qc_final_out = $category."/".$_month.$seq_years."/".$seq;
			return $_no_sew_qc_final_out;
	}	
	public function get_id($_no_sew_qc_final_out){
		$sql = "SELECT id_sew_qc_final_out_header FROM prod_qc_final_out_header WHERE no_sew_qc_final_out = '{$_no_sew_qc_final_out}' LIMIT 1";
		$tmp_array = $this->eksekusi_query($sql,"get_id");
		
		return $tmp_array[0]['id_sew_qc_final_out_header'];
	}
	public function insert($data,$username,$no_sew_qc_final_out)
	{
		$d_out =  date("Y-m-d", strtotime($data->date_output));
		$dt = date("Y-m-d H:i:s");
		$__time=explode(" ",$dt);
		$_time = explode(":",$__time[1]);
		$time  = $_time[0].":".$_time[1];
		$insert = "INSERT INTO prod_qc_final_out_header (notes,id_cost,id_line,username,dateinput,date_output,no_sew_qc_final_out,time) 
				VALUES (
					'{$data->notes}',
					'{$data->id_cost}',
					'{$data->id_line}',
					'{$username}',
					'{$dt}',
					'{$d_out}',
					'{$no_sew_qc_final_out}',
					'{$time}' )
					";
/* 			echo $insert;
die();		 */	
		$this->eksekusi_query_insert_update($insert,'insert');
		return 1;	
	}
	public function update($data,$username)
	{
		$d_out =  date("Y-m-d", strtotime($data->date_output));
		$dt = date("Y-m-d H:i:s");
		$update = "UPDATE prod_qc_final_out_header SET id_line = '{$data->id_line}',notes='{$data->notes}',userupdate='{$username}',dateupdate='{$dt}' WHERE id_sew_qc_final_out_header = '{$data->id_sew_qc_final_out_header}'";
		$this->eksekusi_query_insert_update($update,'update');
		return 1;	
	}	
	
	
	
	public function insert_detail($header,$_detail,$id_sew_qc_final_out_header,$username)
	{
		$dt = date("Y-m-d H:i:s");
		$insert = "INSERT INTO prod_qc_final_out_detail(id_so_det,id_sew_qc_final_out_header,qty,username,dateinput,rpr,id_defect) VALUES ";
		for($i=0;$i<count($_detail);$i++){
			$_det = $_detail[$i];
					if($_det->is_qty > 0){
						$insert .= "(";
						$insert .=" 
										'{$_det->is_id_so_det}',
										'{$id_sew_qc_final_out_header}',
										'{$_det->is_qty}',
										'{$username}',
										'{$dt}',
										'{$_det->is_rpr}',
										'{$_det->is_id_defect}'
										";		
						$insert .=	"),";						
					}		
		}
		$insert 	= substr($insert, 0, -1);	

		$this->eksekusi_query_insert_update($insert,'insert_detail');
		return 1;		
	
		}
	
	
	public function update_detail($header,$_detail,$username)
	{
		$dt = date("Y-m-d H:i:s");
		for($i=0;$i<count($_detail);$i++){
			$_det = $_detail[$i];
			if($_det->is_id_det > 0){
				if($_det->is_qty == 0){
					$delete = "DELETE FROM prod_qc_final_out_detail WHERE id = '{$_det->is_id_det}'";
					$this->eksekusi_query_insert_update($delete,'update_detail');
				}else{
					$update = "UPDATE prod_qc_final_out_detail SET id_defect='{$_det->is_id_defect}', rpr='{$_det->is_rpr}', qty='{$_det->is_qty}',userupdate='{$username}',dateupdate='{$dt}' WHERE id = '{$_det->is_id_det}'";
					$this->eksekusi_query_insert_update($update,'update_detail');
				}
			}else{
				if($_det->is_qty > 0){
					$insert = "INSERT INTO prod_qc_final_out_detail(id_so_det,id_sew_qc_final_out_header,qty,username,dateinput,rpr,id_defect) VALUES ";
					$insert .= "(";
					$insert .=" 
									'{$_det->is_id_so_det}',
									'{$header->id_sew_qc_final_out_header}',
									'{$_det->is_qty}',
									'{$username}',
									'{$dt}',
									'{$_det->is_rpr}',
									'{$_det->is_id_defect}',
									";		
					$insert .=	")";	

					$this->eksekusi_query_insert_update($insert,'update_detail');
				}		
			}
		}
		return 1;		
	
		}
	}		
	

	

?>