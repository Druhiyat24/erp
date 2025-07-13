<?php 
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Save {

	public function DELETES($data){
		include __DIR__ .'/../../../include/conn.php';
		$sql = " DELETE FROM fin_status_journal_ar 
				WHERE id_rekap = '$data->id'
			";
			//echo $q;
			$stmt = $con_new->query($sql);	
			if(!$con_new->connect_error){
				$result = '{ "status":"ok", "message":"1", "records":""}';
			}else{
				$result = '{ "status":"ok", "message":"E", "records":"'.$con_new->connect_error.'"}';
			}
			return $result;
	}	
	

}
	//print_r($data);
	
//		

$data = (object)$_POST['Senddata'];
$code = $_POST['code'];
//print_r($data);
if($code == '1'){
	if(!ISSET($_SESSION['username'])){
		$List = '{ "status":"no", "message":"SESSION HABIS, Silahkan Login Kembali", "records":""}';
		
	}
	else{
		$Save = new Save();
				if($data->type == "SEND"){
					$List = $Save->UPDATES($data);
				}
				else if($data->type == "DELETE"){
					$List = $Save->DELETES($data);
				}
			}
	print_r($List);
}
else{
	exit;
}



?>




