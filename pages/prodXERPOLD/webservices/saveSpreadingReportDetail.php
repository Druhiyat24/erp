<?php 
include __DIR__.'/../../log_activity/log.php';
$Proses = new Proses();

$format = $_POST['format'];
$code = $_POST['code'];
$header = json_decode($_POST['header']);
$detail = json_decode($_POST['detail']);
//print_r($detail);die();

if($code =='1'){
		//$id_jo 					= $Proses->get_id_jo($header->id_number);
		$update_header  		= $Proses->update_header($header); 
	if($format =='1'){
		$insert_detail  		= $Proses->insert_detail($detail,$header->id_jo,$header->id_item,$header->id_number); // bermain disini jika ada attribut
	}else if($format == '2'){
		
		$delete_all_uncheck		= $Proses->delete_all_uncheck($detail); // 
		$update_detail  		= $Proses->update_detail($detail,$header->id_jo,$header->id_item,$header->id_number); // bermain disini jika ada attribut		
	}
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
	
	
	public function get_id_jo($_id_number){
		$sql = "SELECT A.id_so,A.id_number,B.id_jo FROM prod_spread_report_number A
					INNER JOIN jo_det B ON  A.id_so = B.id_so
					WHERE A.id_number = '{$_id_number}' LIMIT 1
					
		";



		$tmp_array = $this->eksekusi_query($sql,"get_id_jo");
		return $tmp_array[0]['id_jo'];
	}	
	
	
	public function update_header($_header){
			$update="UPDATE prod_spread_report_number SET
					color 			= '".$_header->color."'
					,cons 			= '".$_header->cons."'
					,id_group_det 	= '".$_header->id_group_det."'
					,width_marker 	= '".$_header->lebar_marker."'
					,length_marker 	= '".$_header->panjang_marker."'
					,efficiency		= '".$_header->efficiency."'
					,yield			= '".$_header->yield."'
					,id_panel 		= '".$_header->id_panel."'
					,bagian 		= '".$_header->bagian."'
				WHERE id_number = '".$_header->id_number."'";
			$this->eksekusi_query_insert_update($update,'update_header');
		return 1;
		
	}
	
	public function insert_detail($_detail,$_id_jo,$_id_item,$_id_number){
		$insert = "INSERT INTO prod_spread_report_detail(id_roll_det,id_number,	bppbno_req,id_jo,lembar_gelaran,sambung_duluan_bisa,sisa_tidak_bisa,qty_reject_yds,total_yds,short_roll,percent,remark,sisa_gelar,id_item) VALUES ";
		
		//$trigger = count($_detail) - 1;
		for($i=0;$i<count($_detail);$i++){
			$_det = $_detail[$i];
/* 			print_r($_detail);
			die(); */
			if($_det->is_checklist == '1'){
				$insert .= "(";
				$insert .=" '{$_det->id_roll_det}',
								'{$_id_number}',
								'{$_det->bppbno}',
								'{$_id_jo}',
								'{$_det->is_lembar_gelar}',
								'{$_det->is_sambung_duluan_bisa}',
								'{$_det->is_sisa_tidak_bisa}',
								'{$_det->is_qty_reject_yds}',
								'{$_det->is_total_yds}',
								'{$_det->is_short_roll}',
								'{$_det->is_percent}',
								'{$_det->is_remark}',
								'{$_det->is_sisa_gelar}',
								'{$_id_item}'
								";		
				//if($trigger == $i){
				//	$insert .=	")";
				//}else{
					$insert .=	"),";
				//}		
									
			}

								
		}
		$insert 	= substr($insert, 0, -1);
		
		
/*  echo $insert;
die();  */
			$this->eksekusi_query_insert_update($insert,'insert_detail');
		return 1;
		
	}	
	public function update_detail($_detail,$_id_jo,$_id_item,$_id_number){
		// print_r($_id_number);die();
		for($i=0;$i<count($_detail);$i++){
			
			$_det = $_detail[$i];
			if($_det->is_checklist == '1'){
				if($_det->id_det > 0){
					$update = "UPDATE prod_spread_report_detail SET
								lembar_gelaran				='{$_det->is_lembar_gelar}',
								sambung_duluan_bisa        ='{$_det->is_sambung_duluan_bisa}',
								sisa_tidak_bisa            ='{$_det->is_sisa_tidak_bisa}',
								qty_reject_yds             ='{$_det->is_qty_reject_yds}',
								total_yds                  ='{$_det->is_total_yds}',
								short_roll                 ='{$_det->is_short_roll}',
								percent                    ='{$_det->is_percent}',
								remark                     ='{$_det->is_remark}',
								sisa_gelar					='{$_det->is_sisa_gelar}'				
							WHERE id_number='{$_id_number}' AND id_roll_det = '{$_det->id_roll_det}'
					";
					// echo $update;
					
					
						$this->eksekusi_query_insert_update($update,'update_detail');					
				}else{
					$insert = "INSERT INTO prod_spread_report_detail(id_roll_det,id_number,	bppbno_req,id_jo,lembar_gelaran,sambung_duluan_bisa,sisa_tidak_bisa,qty_reject_yds,total_yds,short_roll,percent,remark,sisa_gelar,id_item) VALUES (
						'{$_det->id_roll_det}',
						'{$_id_number}',
						'{$_det->bppbno}',
						'{$_id_jo}',
						'{$_det->is_lembar_gelar}',
						'{$_det->is_sambung_duluan_bisa}',
						'{$_det->is_sisa_tidak_bisa}',
						'{$_det->is_qty_reject_yds}',
						'{$_det->is_total_yds}',
						'{$_det->is_short_roll}',
						'{$_det->is_percent}',
						'{$_det->is_remark}',
						'{$_det->is_sisa_gelar}',
						'{$_id_item}'
					)";
					$this->eksekusi_query_insert_update($insert,'insert_detail');
				}
		
			
				
			}

		}
		// die();
		return 1;
	}
	
		
	
	
	
	public function delete_all_uncheck($_detail){
		
		for($i=0;$i<count($_detail);$i++){
			$_det = $_detail[$i];
			
			 //print_r($_det);//;die();
			if(($_det->is_checklist =='0') && ($_det->id_det > 0)){
				
			$update = "DELETE FROM prod_spread_report_detail WHERE id = '{$_det->id_det}'";
				$this->eksekusi_query_insert_update($update,'update_detail');
		}
		// die();
		
	}	
	return 1;
	}
}

?>