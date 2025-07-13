<?php 
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


		$data = $_POST;
$data = (object)$_POST;
//print_r($data);
if($data->code == '1' ){
	$getListData = new getListData($data->period);
$List = $getListData->get($data->period);
print_r($List);
}
else{
	exit;
}
class getListData {
	
	public function bulan_periode($from){
		$bulan_periode = explode("/",$from."/01");
		$bulan_periode = $bulan_periode[1]."-".$bulan_periode[0]."-".$bulan_periode[2];
		$bulan_periode = date("Y-m-d", strtotime("-1 days",strtotime($bulan_periode)));	
		return $bulan_periode;
	}	
	
	public function get($period){
		include __DIR__ .'/../../../include/conn.php';
		$bulan_periode = $this->bulan_periode($period);
		$q = "SELECT n_id,d_open,c_open FROM fin_open_saldo_awal WHERE d_open = '$bulan_periode';
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
				if($row['c_open'] == '1' ){
					$status = 'OK';
				}else{
					$status = 'NO';
				}
				if ($outp != "") {$outp .= ",";}
				$outp .= '{"status":"'.$status.'" }'; 
			}
		}else{
			$outp .= '{"status":"'.'NO'.'"}';
		}
		//$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		$result = '{ "status":"ok", "message":"1","records":['.$outp.'] }';
		return $result;
	}
}
?>





