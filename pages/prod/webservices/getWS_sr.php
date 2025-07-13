<?php 
$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

$getListData = new getListData();

$id_cost = $_POST['id'];
// print_r($id_cost);die();

$List = $getListData->get($id_cost);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($id){
		include __DIR__ .'/../../../include/conn.php';
		$q = " SELECT A.id,A.kpno ws,B.id_mark_entry FROM act_costing A
					INNER JOIN prod_mark_entry B ON A.id = B.id_cost
		WHERE B.id_mark_entry = '{$id}' LIMIT 1
		"; 
		// echo $q;
		$stmt = mysql_query($q);		

		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"ws":"'.rawurlencode($row['ws']).'",';
			$outp .= '"id_cost":"'.rawurlencode($row['id']).'"}';
		}
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}
?>




