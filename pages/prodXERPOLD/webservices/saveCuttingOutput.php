<?php
include __DIR__.'/../../log_activity/log.php';
session_start();

/*
format =  1 : INSERT
format =  2 : UPDATE
format =  3 : DELETE
*/
$data =(object)$_POST['data'];
$det = $_POST['detail'];
$id_header = $_POST['id_header'];

$dedet = base64_decode($det);
$detail = json_decode($dedet);
// print_r($data->date);die();

$code = $_POST['code'];
$format = $_POST['format'];

if($code == '1'){
	$Proses = new Proses();
		if($format == '1'){
			$no_cut_out = $Proses->get_no_cut_out($data->date);
			$key = $Proses->insert($data,$_SESSION['username'],$no_cut_out);
			$key = $Proses->insert_detail($data,$detail);	
			// $key = $proses->delete_tempo($detail);
			// $result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
			echo $result;
			exit;
		}
		else if($format == '2'){
			$key = $Proses->update($data,$detail,$id_header);
		}
		else{
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


	public function get_no_cut_out($_date){
		$category ="CUT/OUT";
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

	
	public function insert($data,$username,$no_cut_out){
		$population_id = explode("_",$data->ws);
		$id_costing = $population_id[0];
		$color = $population_id[1];
		// print_r($id_costing);die();

		$connect = $this->connect();	
		$dt = date("Y-m-d h:i:s");

		$sql = "INSERT INTO prod_cut_out (id_ws_cut_out,id_cost,color,no_cut_out,username_create,dateinput_create) 
				VALUES (
					'{$data->ws}',
					'{$id_costing}',
					'{$color}',
					'{$no_cut_out}',
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

	public function insert_detail($data,$detail){
		$connect = $this->connect();
		$sqli = "SELECT max(id_cut_out) AS id FROM prod_cut_out";
		$result = $connect->query($sqli);

		$row = mysqli_fetch_array($result);
		$last_id = $row['id'];

		$population_id = explode("_",$data->ws);
		$id_costing = $population_id[0];
		$color = $population_id[1];

		for($i=0;$i<count($detail);$i++){
			$detail[$i] = (array)$detail[$i];

			$sql = "INSERT INTO prod_cut_out_detail (id_cut_out,id_cat,id_cost,id_item,fabric_code,color,fabric_desc,id_grouping,id_panel,lot,
					id_so_det,size,cutting_output,reject,qty_cut_out)
						VALUES (
							'$last_id',
							'{$detail[$i]['id']}',
							'{$id_costing}',
							'{$detail[$i]['id_item']}',
							'{$detail[$i]['goods_code']}',
							'{$detail[$i]['color']}',
							'{$detail[$i]['itemdesc']}',
							'{$detail[$i]['idg']}',
							'{$detail[$i]['idp']}',
							'{$detail[$i]['lot']}',
							'{$detail[$i]['id_so_det']}',
							'{$detail[$i]['size']}',
							'{$detail[$i]['cutt']}',
							'{$detail[$i]['reject']}',
							'{$detail[$i]['okeCutt']}'
						)";
					//  echo $sql;
			$result = $connect->query($sql);
			
			$sqlc = "UPDATE prod_cut_out_category SET is_save = 'Y' WHERE id_cat = '{$detail[$i]['id']}'";
			$result = $connect->query($sqlc);

			// $this->eksekusi_query_insert_update($sql,'insert_detail');
		}
		// die();
		// print_r($sqlc);die();
		
		$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0", "last_id": "'.$last_id.'" }';
		print_r($result);	
		$connect->close();
	}
	
    
	public function update($data,$detail,$id_header){

		$connect = $this->connect();

		$population_id = explode("_",$data->ws);
		$id_costing = $population_id[0];
		$color = $population_id[1];

		for($i=0;$i<count($detail);$i++){
			$detail[$i] = (array)$detail[$i];
			if($detail[$i]['id_det'] == '0'){

				$sql = "INSERT INTO prod_cut_out_detail (id_cut_out,id_cat,id_cost,id_item,fabric_code,color,fabric_desc,id_grouping,id_panel,lot,
						id_so_det,size,cutting_output,reject,qty_cut_out)
						VALUES (
							'{$id_header}',
							'{$detail[$i]['id_cat']}',
							'{$id_costing}',
							'{$detail[$i]['id_item']}',
							'{$detail[$i]['goods_code']}',
							'{$detail[$i]['color']}',
							'{$detail[$i]['itemdesc']}',
							'{$detail[$i]['idg']}',
							'{$detail[$i]['idp']}',
							'{$detail[$i]['lot']}',
							'{$detail[$i]['id_so_det']}',
							'{$detail[$i]['size']}',
							'{$detail[$i]['cutt']}',
							'{$detail[$i]['reject']}',
							'{$detail[$i]['okeCutt']}'
						)
				";
				
				// $this->eksekusi_query_insert_update($sql,'insert_detail');
				if(!$connect->query($sql)){
					$message = "Error :".$connect->error;
					$respon  = "500";
						//$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
						//return result;				
				}
				else{
					$message = "SUKSES!";
					$respon  = "200";
						//$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
						//return result;
				}	

				$sqlc = "UPDATE prod_cut_out_category SET is_save = 'Y' WHERE id_cat = '{$detail[$i]['id_cat']}'";
				// print_r($sqlc);
				$result = $connect->query($sqlc);
			}
			
			else{

				$sql = "UPDATE prod_cut_out_detail SET
							cutting_output = '{$detail[$i]['cutt']}',
							reject = '{$detail[$i]['reject']}',
							qty_cut_out = '{$detail[$i]['okeCutt']}'
						WHERE id_cut_out_detail = '{$detail[$i]['id_det']}'
				";
				
				$this->eksekusi_query_insert_update($sql,'update_detail');
				if(!$connect->query($sql)){
					$message = "Error :".$connect->error;
					$respon  = "500";
						//$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
						//return result;	
				}
				else{
					$message = "SUKSES!";
					$respon  = "200";
						//$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
						//return result;
				}
			}
		}

		$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0", "last_id": "'.$last_id.'"}';
		print_r($result);		
	}

}
?>