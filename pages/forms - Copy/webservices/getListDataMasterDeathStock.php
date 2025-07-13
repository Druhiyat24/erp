<?php 
session_start();
error_reporting(E_ERROR | E_PARSE);
include "../../forms/fungsi.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
		$data = $_GET;
//$data = (object)$_POST['data'];

$getListData = new getListData();
if(ISSET($_POST['id'])){
	$List = $getListData->getDetailById($_POST['id']);
}else{
	$List = $getListData->get();
}
//if($data['code'] == '1' ){
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	function CompileQuery($query,$mode){
		include __DIR__ .'/../../../include/conn.php';
		$stmt = mysqli_query($conn_li,$query);	
		if(mysqli_error($conn_li)){
		
			$result = mysqli_error($conn_li)."__ERRTRUE";
			return $result;
		}	
		else{
			if($mode == "CRUD"){
				print_r($query);
				$result = '{ "status":"ok", "message":"1"}';
				return $result;
			}
			else{
				
				if(mysqli_num_rows($stmt) == '0' ){
					$result = '{ "status":"ok", "message":"2"}';
					return '0';
				}
				else{
					return $stmt;
				}
			}
		} 
	}	
	
	public function getDetailById($data){
		$_id = $data;
		$q = "SELECT c_type
		,n_id
		,v_klasifikasi
		,v_c_bahan_baku
		,v_n_bahan_baku
		,v_color
		,v_ukuran
		,n_qty
		,v_unit
		,v_no_rak
		,v_keterangan
		,d_insert
		,v_user_insert
		,d_update
		,v_user_update
		FROM masterdeathstock WHERE 1 AND n_id = '$_id'
		ORDER BY d_insert DESC
		";
		$MyList = $this->CompileQuery($q,'SELECT');
		if($MyList == '0'){
			$result = '{ "status":"ok", "message":"2", "records":"0"}';
		}
		else{
			if(!is_object($MyList)){
			$EXP = explode("__ERR",$MyList);
			if($EXP[1]){
				$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
			}				
			}
			else{	

		
		
		$outp = '';

 		while($row = mysqli_fetch_array($MyList)){

			
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"n_id":"'.$row["n_id"].'",'; 
			$outp .= '"v_klasifikasi":"'.$row["v_klasifikasi"].'",';
			$outp .= '"c_type":"'.$row["c_type"].'",'; 
			$outp .= '"v_c_bahan_baku":"'.$row["v_c_bahan_baku"].'",'; 
			$outp .= '"v_n_bahan_baku":"'.$row["v_n_bahan_baku"].'",'; 
			$outp .= '"v_color":"'.$row["v_color"].'",'; 
			$outp .= '"v_ukuran":"'.$row['v_ukuran'].'",'; 
			$outp .= '"n_qty":"'.$row['n_qty'].'",'; 
			$outp .= '"v_unit":"'.$row['v_unit'].'",'; 
			$outp .= '"v_no_rak":"'.$row['v_no_rak'].'",';
			$outp .= '"v_keterangan":"'.$row['v_keterangan'].'",';
			$outp .= '"d_insert":"'.$row['d_insert'].'",';
			$outp .= '"v_user_insert":"'.$row['v_user_insert'].'",';
			$outp .= '"d_update":"'.$row['d_update'].'",';
			$outp .= '"v_user_update":"'.$row['v_user_update'].'",';
			$outp .= '"crud":"EDIT"}'; 
		} 
			//$result = '{"data":['.$outp.']}';	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
			}		
		}
		return $result;
	}
	
	public function get(){
		$q = "SELECT c_type
		,n_id
		,v_klasifikasi
		,v_c_bahan_baku
		,v_n_bahan_baku
		,v_color
		,v_ukuran
		,n_qty
		,v_unit
		,v_no_rak
		,v_keterangan
		,d_insert
		,v_user_insert
		,d_update
		,v_user_update
		FROM masterdeathstock WHERE 1
		ORDER BY d_insert DESC
		";
		//echo $q;
	
		$MyList = $this->CompileQuery($q,'SELECT');
	if($MyList == '0'){
			$result = '{ "status":"ok", "message":"2", "records":"0"}';
		}
		else{
			if(!is_object($MyList)){
			$EXP = explode("__ERR",$MyList);
			if($EXP[1]){
				$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
			}				
			}
			else{	
		$outp = '';
		$my_id = 1; 
		//echo $type_invoice;
 		while($row = mysqli_fetch_array($MyList)){
		$button = '';
		$button .= " <a class='btn btn-warning btn-s' href='#' onclick='edit(".'"'.$row['n_id'].'"'.")'
                data-toggle='tooltip' title='Edit'><i class='fa fa-pencil'></i></a></td>"; 	
		$button .= " <a class='btn btn-warning btn-s' href='#' onclick='deletes(".'"'.$row['n_id'].'"'.")'
                data-toggle='tooltip' title='Edit'><i class='fa fa-trash'></i></a></td>"; 				
			

			
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"n_id":"'.$row["n_id"].'",'; 
			$outp .= '"v_klasifikasi":"'.$row["v_klasifikasi"].'",';
			$outp .= '"c_type":"'.$row["c_type"].'",'; 
			$outp .= '"v_c_bahan_baku":"'.$row["v_c_bahan_baku"].'",'; 
			$outp .= '"v_n_bahan_baku":"'.$row["v_n_bahan_baku"].'",'; 
			$outp .= '"v_color":"'.$row["v_color"].'",'; 
			$outp .= '"v_ukuran":"'.$row['v_ukuran'].'",'; 
			$outp .= '"n_qty":"'.$row['n_qty'].'",'; 
			$outp .= '"v_unit":"'.$row['v_unit'].'",'; 
			$outp .= '"v_no_rak":"'.$row['v_no_rak'].'",';
			$outp .= '"v_keterangan":"'.$row['v_keterangan'].'",';
			$outp .= '"d_insert":"'.$row['d_insert'].'",';
			$outp .= '"v_user_insert":"'.$row['v_user_insert'].'",';
			$outp .= '"d_update":"'.$row['d_update'].'",';
			$outp .= '"v_user_update":"'.$row['v_user_update'].'",';
			$outp .= '"button":"'.rawurlencode($button).'"}'; 
		} 		
			$result = '{"data":['.$outp.']}';	
			}		
		}
		return $result;
	}
}




?>




