<?php
ini_set('memory_limit','-1');
ini_set('set_time_limit','-1');
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
//  print_r($detail); 
//die();  
$format= $_POST['format'];
 if(!ISSET($_SESSION['username'])){

		$result = '{ "respon":"'.'205'.'", "message":"'.'Session Expired!!!!!  '.'", "records":"0"}';   
		echo $result;	
		die();	 
 }
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
    $element = $_det->is_qty_qc_output;
    if (is_numeric($element)) {
        $x= 'TRUE';
    } else {
		$x = 'FALSE';
		$result = '{ "respon":"'.'201'.'", "message":"'.'OK'.'", "records":"0","baris":"'.$ii.'"}';   
		echo $result;
		die();
    }	
}

//validasi
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
	//$qty =$_det->is_qty_qc_output;
	$qty =$_det->is_qty_qc_output + $_det->is_rpr;
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
$ii = 0;
	//check qty must >= 0
for($i=0;$i<count($detail);$i++){
	$ii = $i+1;
	$_det = $detail[$i];
	$qty =$_det->is_qty_qc_output;
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
//	$qty =$_det->is_qty_qc_output;
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
	$qty =$_det->is_qty_qc_output;
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
		$dt__ = date("Y-m-d H:i:s");
		if($format == '1'){
			$is_qty_qc_out 			= $Proses->get_no_sew_qc_out($header->date_output);		
			$key 					= $Proses->insert($header,$_SESSION['username'],$is_qty_qc_out);
			$id_sew_qc_out  		= $Proses->get_id_sew_qc_out($is_qty_qc_out);
			$key 					= $Proses->insert_detail($header,$detail,$id_sew_qc_out,$_SESSION['username'],$dt__);
			$populasi_qty_reject 	= $Proses->populasi_qty_reject($dt__,$id_sew_qc_out,$_SESSION['username']);
			$key					= $Proses->insert_reject($populasi_qty_reject,$id_sew_qc_out,$_SESSION['username'],$dt__);
			$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0","id_sew_qc_out" : "'.$id_sew_qc_out.'"}';
			echo $result;
			die();
		}else if($format == '2'){ 
			$id_sew_qc_out = $header->id_sew_qc_out;
			$key = $Proses->update($header,$_SESSION['username'],$dt__);
			$key = $Proses->update_detail($header,$detail,$_SESSION['username'],$dt__);
			$key = $Proses->eksekusi_reject($id_sew_qc_out,$header,$detail,$_SESSION['username'],$dt__);
			$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0","id_sew_qc_out" : "'.$id_sew_qc_out.'"}';
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
	
		
	
	public function get_no_sew_qc_out($_date_output){
		$category ="SEW/QC/OUT";
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
			$_no_sew_qc_out = $category."/".$_month.$seq_years."/".$seq;
			return $_no_sew_qc_out;
	}	
	public function get_id_sew_qc_out($_no_sew_qc_out){
		$sql = "SELECT id_sew_qc_out_header FROM prod_qc_out_header WHERE no_sew_qc_out = '{$_no_sew_qc_out}' LIMIT 1";
		$tmp_array = $this->eksekusi_query($sql,"get_id_sew_qc_out");
		
		return $tmp_array[0]['id_sew_qc_out_header']; 
	}
	public function insert($data,$username,$is_qty_qc_out)
	{
		$d_out =  date("Y-m-d", strtotime($data->date_output));
		$dt = date("Y-m-d H:i:s");
		$__time=explode(" ",$dt);
		$_time = explode(":",$__time[1]);
		$time  = $_time[0].":".$_time[1];	
		$insert = "INSERT INTO prod_qc_out_header (notes,id_cost,id_line,username,dateinput,date_output,no_sew_qc_out,time) 
				VALUES (
					'{$data->notes}',
					'{$data->id_cost}',
					'{$data->id_line}',
					'{$username}',
					'{$dt}',
					'{$d_out}',
					'{$is_qty_qc_out}',
					'{$time}' )
					";
/* 			echo $insert;
die();		 */	
		$this->eksekusi_query_insert_update($insert,'insert');
		return 1;	
	}
	public function update($data,$username,$dt)
	{
		$d_out =  date("Y-m-d", strtotime($data->date_output));
		$dt = date("Y-m-d H:i:s");
		$update = "UPDATE prod_qc_out_header SET id_line = '{$data->id_line}',notes='{$data->notes}',userupdate='{$username}',dateupdate='{$dt}' WHERE id_sew_qc_out_header = '{$data->id_sew_qc_out}'";
		$this->eksekusi_query_insert_update($update,'update');
		return 1;	
	}	
	
	
	
	public function insert_detail($header,$_detail,$id_sew_qc_out,$username,$dt)
	{
		$insert = "INSERT INTO qc_out(id_so_det,id_sew_qc_out_header,qty,username,dateinput,rpr,id_defect) VALUES ";
		for($i=0;$i<count($_detail);$i++){
			$_det = $_detail[$i];
					if($_det->is_qty_qc_output > 0){
						$insert .= "(";
						$insert .=" 
										'{$_det->is_id_so_det}',
										'{$id_sew_qc_out}',
										'{$_det->is_qty_qc_output}',
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
	
	
	public function update_detail($header,$_detail,$username,$dt)
	{
		//$dt = date("Y-m-d H:i:s");
		for($i=0;$i<count($_detail);$i++){
			$_det = $_detail[$i];
			//print_r($_det);
			if($_det->is_id_sew_qc_out_det > 0){
				if($_det->is_qty_qc_output == 0){
					$delete = "DELETE FROM qc_out WHERE id = '{$_det->is_id_sew_qc_out_det}'";
					$this->eksekusi_query_insert_update($delete,'update_detail');
				}else{
					$update = "UPDATE qc_out SET id_defect='{$_det->is_id_defect}', rpr='{$_det->is_rpr}', qty='{$_det->is_qty_qc_output}',userupdate='{$username}',dateupdate='{$dt}' WHERE id = '{$_det->is_id_sew_qc_out_det}'";
					$this->eksekusi_query_insert_update($update,'update_detail');
				}
			}else{
				if($_det->is_qty_qc_output > 0){
					$insert = "INSERT INTO qc_out(id_so_det,id_sew_qc_out_header,qty,username,dateinput,rpr,id_defect) VALUES ";
					$insert .= "(";
					$insert .=" 
									'{$_det->is_id_so_det}',
									'{$header->id_sew_qc_out}',
									'{$_det->is_qty_qc_output}',
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
		
		public function populasi_qty_reject($dt,$id_sew_qc_out,$_username){
			$sql = "SELECT id id_det,id_so_det,rpr FROM qc_out WHERE id_sew_qc_out_header='{$id_sew_qc_out}'
						AND dateinput = '{$dt}' AND username = '{$_username}' AND ifnull(rpr,0) > 0
			";
			$_populasi_reject = array();
			$tmp_array = $this->eksekusi_query($sql,"populasi_qty_reject");			
				for($i=0;$i<count($tmp_array);$i++){
					$__tmp = array(
						"is_id_det" => $tmp_array[$i]["id_det"],
						"qty_reject" => $tmp_array[$i]["rpr"],
						"is_id_so_det" => $tmp_array[$i]["id_so_det"]
					
					);
					array_push($_populasi_reject,$__tmp);
				}
			return $_populasi_reject;
			
		}
	public function insert_reject($populasi_qty_reject,$id_sew_qc_out,$username,$dt)
	{
		$insert = "INSERT INTO prod_rej_qc_sew_detail(id_so_det,id_qc_sew_out_detail,qty,username,dateinput) VALUES ";
		for($i=0;$i<count($populasi_qty_reject);$i++){
			$_det = (object)$populasi_qty_reject[$i];
 			for($j=0;$j<($_det->qty_reject);$j++){
						$insert .= "(";
						$insert .=" 
										'{$_det->is_id_so_det}',
										'{$_det->is_id_det}',
										'1',
										'{$username}',
										'{$dt}'
										";		
						$insert .=	"),";						
										
			
			
			}
			
	//	die(); 
		$insert 	= substr($insert, 0, -1);	
//echo $insert;
		$this->eksekusi_query_insert_update($insert,'insert_detail');
		return 1;				
	
		}
		
 		
	
	
		}

		
		public function populasi_qty_qc_out_qty_reject($id_sew_qc_out,$header){
			$_populasi_reject = array();
			$sql = "SELECT A.id id_det
					,A.rpr qty_rej_qc_out
					,A.id_sew_qc_out_header
					,A.id_so_det
					,ifnull(B.qty_rej_det,0)qty_rej_det
					FROM qc_out A LEFT JOIN(
					SELECT   id_qc_sew_out_detail
						,id_rej_qc_sew_detail
						,SUM(ifnull(qty,0))qty_rej_det
						FROM prod_rej_qc_sew_detail
		  		GROUP BY id_qc_sew_out_detail
				)B ON A.id = B.id_qc_sew_out_detail
				WHERE A.id_sew_qc_out_header = '{$id_sew_qc_out}'	";
			$tmp_array = $this->eksekusi_query($sql,"populasi_qty_qc_out_qty_reject");
			return $tmp_array;
			
		}
		
		
		public function insert_reject_detail($pop_reject,$id_sew_qc_out,$header,$detail,$username,$dt){
			$_populasi_reject = array();
			$_count = $pop_reject["qty_rej_qc_out"] - $pop_reject["qty_rej_det"];
					$__tmp = array(
						"is_id_det" => $pop_reject["id_det"],
						"qty_reject" => $_count,
						"is_id_so_det" => $pop_reject["id_so_det"]
					
					);
					array_push($_populasi_reject,$__tmp);	
					$this->insert_reject($_populasi_reject,$id_sew_qc_out,$username,$dt);
					return 1;
			
		}


		public function populasi_id_delete($id_sew_qc_out,$jumlah_qty_delete,$category,$id_so_det){
			if($category == "FREE"){
				$sql = "	SELECT A.id_rej_qc_sew_detail
							FROM prod_rej_qc_sew_detail A
							INNER JOIN (SELECT id FROM qc_out WHERE id_sew_qc_out_header = '{$id_sew_qc_out}' AND id_so_det ='{$id_so_det}' )B ON A.id_qc_sew_out_detail = B.id
							AND ifnull(id_rej_qc_sew_header,0) = 0
							LIMIT {$jumlah_qty_delete}
							
						";
			}else{
				$sql = "SELECT A.id_rej_qc_sew_detail
							FROM prod_rej_qc_sew_detail A
							INNER JOIN (SELECT id FROM qc_out WHERE id_sew_qc_out_header = '{$id_sew_qc_out}' AND id_so_det ='{$id_so_det}')B ON A.id_qc_sew_out_detail = B.id 
							WHERE ifnull(id_rej_qc_sew_header,0) != 0
							LIMIT {$jumlah_qty_delete}";
			}
			//echo $sql;
			$tmp_array = $this->eksekusi_query($sql,"populasi_id_delete");
			if(count($tmp_array) > 0 ){
				$trigger = count($tmp_array) - 1;
				$_str = "";
				for($i=0;$i<count($tmp_array);$i++){
					if($i == $trigger){
						$_str .= "'".$tmp_array[$i]["id_rej_qc_sew_detail"]."'";
					}else{
						$_str .= "'".$tmp_array[$i]["id_rej_qc_sew_detail"]."',";
					}
					
				}
			}else{
				$_str .= "'"."X"."'";
			}
			return $_str;
		}
		
		public function delete_qty_free($id_sew_qc_out,$category,$jumlah_qty_delete,$id_so_det){

			$populasi_id_delete = $this->populasi_id_delete($id_sew_qc_out,$jumlah_qty_delete,$category,$id_so_det);
			$where = "id_rej_qc_sew_detail IN({$populasi_id_delete})";
			$delete = "DELETE FROM prod_rej_qc_sew_detail WHERE 1=1 AND   {$where}";
		//	echo $delete;
			$this->eksekusi_query_insert_update($delete,'delete_qty_free');
			return 1;
		}
		public function delete_qty_combine($id_sew_qc_out,$category,$jumlah_qty_delete,$_count,$id_so_det){
					
			
			//$this->delete_qty_free($id_sew_qc_out,"FREE",$_count,$id_so_det);
			$this->delete_qty_free($id_sew_qc_out,$category,$jumlah_qty_delete,$id_so_det);
			return 1;
		}
		
		public function populasi_qty_free($id_sew_qc_out,$id_so_det){
			$sql = "SELECT A.id_qc_sew_out_detail
							FROM prod_rej_qc_sew_detail A
							INNER JOIN (SELECT id FROM qc_out WHERE id_sew_qc_out_header = '{$id_sew_qc_out}' AND id_so_det ='{$id_so_det}')B ON A.id_qc_sew_out_detail = B.id
							AND ifnull(id_rej_qc_sew_header,0) = 0";
			
			$tmp_array = $this->eksekusi_query($sql,"populasi_qty_free");
			return $tmp_array;			
		}
		
		public function populasi_qty_existing($id_sew_qc_out,$id_so_det){
			$sql = "SELECT A.id_qc_sew_out_detail
							FROM prod_rej_qc_sew_detail A
							INNER JOIN (SELECT id FROM qc_out WHERE id_sew_qc_out_header = '{$id_sew_qc_out}' AND id_so_det ='{$id_so_det}')B ON A.id_qc_sew_out_detail = B.id 
							WHERE ifnull(id_rej_qc_sew_header,0) != 0";
			
			$tmp_array = $this->eksekusi_query($sql,"populasi_qty_existing");
			return $tmp_array;
			
			
		}		
		
		public function delete_reject_detail($pop_reject,$id_sew_qc_out,$header,$detail,$username,$dt){
			$_count = $pop_reject["qty_rej_det"] - $pop_reject["qty_rej_qc_out"];
			$populasi_qty_existing 	= $this->populasi_qty_existing($id_sew_qc_out,$pop_reject['id_so_det']);
			$populasi_qty_free 		= $this->populasi_qty_free($id_sew_qc_out,$pop_reject['id_so_det']);
			//print_r($populasi_qty_existing);
			$jumlah_qty_delete = $_count - count($populasi_qty_free);
			if(count($populasi_qty_free) >= $_count ){ //id_so_det
				$this->delete_qty_free($id_sew_qc_out,"FREE",$_count,$pop_reject['id_so_det']);
			}else{
				/* echo $pop_reject['id_so_det'];
				die(); */
				$this->delete_qty_combine($id_sew_qc_out,"EXIST",$jumlah_qty_delete,$_count,$pop_reject['id_so_det']);
			}
			return 1;
			
		}		
		

		public function eksekusi_reject($id_sew_qc_out,$header,$detail,$username,$dt){
			$populasi_qty_qc_out_qty_reject		= $this->populasi_qty_qc_out_qty_reject($id_sew_qc_out,$header);
//print_r($populasi_qty_qc_out_qty_reject);
			if(count($populasi_qty_qc_out_qty_reject) == '0'){
				return 1;
			}else{
				for($i=0;$i<count($populasi_qty_qc_out_qty_reject);$i++){
					$qty_rej_qc_out 	= (float)$populasi_qty_qc_out_qty_reject[$i]["qty_rej_qc_out"];
					$qty_rej_det 		= (float)$populasi_qty_qc_out_qty_reject[$i]["qty_rej_det"];	
					
					if($qty_rej_qc_out == $qty_rej_det ){
						$continue= "1";
						
					} if($qty_rej_qc_out > $qty_rej_det){
						$this->insert_reject_detail($populasi_qty_qc_out_qty_reject[$i],$id_sew_qc_out,$header,$detail,$username,$dt);
						
						
					} if($qty_rej_qc_out < $qty_rej_det)
						$this->delete_reject_detail($populasi_qty_qc_out_qty_reject[$i],$id_sew_qc_out,$header,$detail,$username,$dt);
					
				}
				
				
				
			}
			return $_populasi_reject;
			
		}		
		
		
		
		
	}		
	

	

?>