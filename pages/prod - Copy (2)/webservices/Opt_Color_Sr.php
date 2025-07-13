<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

	$getListData = new getListData();
$List = $getListData->get($_GET['id_number']);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($id){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT ACT.id id_cost,ACT.kpno,SOD.color,PSR.id_cost, SOD.color nama,SOD.color id FROM act_costing ACT
		INNER JOIN prod_spread_report_number PSR ON ACT.id = PSR.id_cost
		INNER JOIN so SO ON ACT.id = SO.id_cost
		INNER JOIN (SELECT id,id_so  ,color FROM so_det )SOD ON SO.id = SOD.id_so
WHERE PSR.id_number ='{$id}'
GROUP BY SOD.color
"; 
/* echo $q;
die(); */
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['id']).'",';
			$outp .= '"isi":"'. rawurlencode($row["nama"]). '"}';
		}
	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}




?>




