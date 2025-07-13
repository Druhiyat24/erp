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
		$q = "SELECT
			ms.Supplier
			,a.styleno
			,a.smv_min
			,smv_sec
			,a.qty
			FROM act_costing  a 
			LEFT JOIN mastersupplier ms ON a.id_buyer=ms.Id_Supplier 
			LEFT JOIN so c ON a.id=c.id_cost
			LEFT JOIN so_det d ON c.id=d.id_so
			"; 
				//	echo $q;
		$stmt = mysql_query($q);		

		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['id']).'",';
			$outp .= '"Supplier":"'. rawurlencode($row["buyer"]). '"}';
		}
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}
?>




