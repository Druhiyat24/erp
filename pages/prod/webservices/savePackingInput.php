<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Save {
	public function Add($data){
		include __DIR__ .'/../../../include/conn.php';
		$q = "INSERT INTO fin_mscurrency(curr,tanggal,rate,rate_jual,rate_beli,v_idgroup)
				VALUES ('$data->curr','$data->tanggal',
				'$data->rate','$data->ratejual','$data->ratebeli','$data->idgroup'
				)

			";	

			$stmt = mysql_query($q);
			$result = '{ "status":"ok", "message":"1", "records":""}';
			return $result;
	}
	public function Edit($data){
		include __DIR__ .'/../../../include/conn.php';
		$q = " UPDATE fin_mscurrency SET 
				   curr = '$data->curr', 
				   rate = '$data->rate',
				   rate_jual = '$data->ratejual',
				   rate_beli = '$data->ratebeli'
				   WHERE v_idgroup = '$data->id'
			";	
			//echo $q;
			$stmt = mysql_query($q);
			$result = '{ "status":"ok", "message":"1", "records":""}';
			return $result;
	}

	public function Delete($data){
		include __DIR__ .'/../../../include/conn.php';
		$q = " DELETE FROM fin_mscurrency WHERE v_idgroup = $data->id
			";	
			//echo $q;
			$stmt = mysql_query($q);
			$result = '{ "status":"ok", "message":"1", "records":""}';
			return $result;
	}

	}




	//print_r($data);
	
//		

$data = (object)$_POST['data'];
$dataDeletes = (object)$_POST['dataDelete'];
if($code == '1'){
	$Save = new Save();
	if($data->type=="Add"){
	$List = $Save->Add($data);
	}
	else if($data->type=="Edit"){
		$List = $Save->Edit($data);
	}
	else if($data->type=="Delete"){
		$List = $Save->Delete($dataDeletes);
	}	
	print_r($List);	

}
else{
	exit;
}



?>




