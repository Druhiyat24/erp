<?php 
$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

$getListData = new getListData();
$id_cost = $_POST['id'];

$List = $getListData->get($id_cost);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($id_cost){
		include __DIR__ .'/../../../include/conn.php';

		$q1 = "SELECT 
				ac.id AS id_cost,
				CONCAT(ac.kpno, ' | ', ac.styleno) AS ws
			FROM act_costing AS ac
			where ac.id = '{$id_cost}'
		"; 
		// echo $q1;
		$stmt = mysql_query($q1);


		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['id_cost']).'",';
			$outp .= '"ws":"'. rawurlencode($row["ws"]). '"}';
		}
		$result = '{ "respon": "200", "status": "ok", "message": "1", "records": ['.$outp.'] }';
		return $result;
	}
}
?>