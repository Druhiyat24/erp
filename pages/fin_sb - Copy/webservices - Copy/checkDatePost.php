<?php 
///ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($_POST['idJournal']);
print_r($List);
}
//else{
//	exit;
//}
class getListData {

	
	public function get($id_journal){
		include __DIR__ .'/../../../include/conn.php';
		//		$sql ="SELECT date_post FROM fin_jourbal_h WHERE id_journal = '$id_journal'
		//";
		$q = "SELECT date_post FROM fin_journal_h WHERE id_journal = '$id_journal'";
		//echo "$q";
		$stmt = mysql_query($q);
		$outp = ''
		;
		
		while($row = mysql_fetch_array($stmt)){	
			$outp .= '{"date_post":"'.rawurlencode($row['date_post']).'"}';	
		}		
		$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}




?>




