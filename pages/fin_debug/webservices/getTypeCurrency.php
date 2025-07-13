<?php 
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


		$data = $_POST;
$data = (object)$_POST;
//print_r($data);
if($data->code == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data->curr);
print_r($List);
}
else{
	exit;
}
class getListData {
	public function get($id){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT curr,id_coa FROM masterbank WHERE id_coa = '$id'
			";	
			//echo $q;
			$stmt = mysqli_query($conn_li,$q);
		if(mysqli_error($conn_li)){
			$result = '{ "status":"no", "message":"'.mysqli_error($conn_li).'"}';
			return $result;
		}		
		$outp = '';
		if(mysqli_num_rows($stmt) > 0 ){
			while($row = mysqli_fetch_array($stmt)){
				if ($outp != "") {$outp .= ",";}
				$outp .= '{"curr":"'.rawurlencode($row['curr']).'" }'; 
			}
		}else{
			$outp .= '{"curr":"'.rawurlencode('IDR').'"}';
		}
		//$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		$result = '{ "status":"ok", "message":"1","records":['.$outp.'] }';
		return $result;
	}
}
?>





