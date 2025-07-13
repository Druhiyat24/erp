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
	$arr = explode('/',$data->period);
	$tgl =sprintf('%02d', $arr[0]);
	$data->period =  $tgl."/".$arr['1'];
$List = $getListData->get($data->period);
print_r($List);
}
else{
	exit;
}
class getListData {
	public function get($period){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT period,fg_status FROM masteraccperiod WHERE period = '$period';
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
				$outp .= '{"status":"'.rawurlencode($row['fg_status']).'" }'; 
			}
		}else{
			$outp .= '{"status":"'.rawurlencode('99').'"}';
		}
		//$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		$result = '{ "status":"ok", "message":"1","records":['.$outp.'] }';
		return $result;
	}
}
?>





