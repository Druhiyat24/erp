<?php 
session_start();

//print_r($_SESSION);
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

	$getListData = new getListData();
$List = $getListData->get($data['tgl_journal']);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($tgljurnal){
		$tgljurnal = date('Y-m-d', strtotime($tgljurnal));
		//echo $tgl;
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT rate,tanggal from masterrate WHERE tanggal = '$tgljurnal' AND v_codecurr = 'PAJAK'"; 
		//echo "$q";
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '[{"rate":"'.rawurlencode($row['rate']).'"}]'; 	
		}
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}




?>




