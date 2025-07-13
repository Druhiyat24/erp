<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get();
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get(){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT id,invno, invdate FROM shp_pro_invoice_header";

		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = ''; 
		$td = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['id']).'",';
			$outp .= '"no_surat":"'. rawurlencode($row["invno"]). '",'; 
			$outp .= '"date":"'. rawurlencode($row["invdate"]). '"}'; 			
		}	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}
}




?>




