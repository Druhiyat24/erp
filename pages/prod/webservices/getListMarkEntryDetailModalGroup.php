<?php 
$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

$getListData = new getListData();
$id_cost = $_POST['id'];
$color = $_POST['clr'];

$List = $getListData->get($id_cost,$color);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($id_cost,$color){
		include __DIR__ .'/../../../include/conn.php';

		$q_group = "SELECT 
						meg.id_group,
						meg.gsm,
						meg.width,
						meg.b_cons_kg
					FROM prod_mark_entry_group AS meg
					WHERE meg.id_cost = '{$id_cost}' AND meg.color = '{$color}'
					GROUP BY meg.id_group
		";
		// echo $q_group;
		$stmt2 = mysql_query($q_group);


		$id = array();
		$g = '';
		while($row2 = mysql_fetch_array($stmt2)){
			if ($g != "") {$g .= ",";}
			$g .= '{"id":"'.rawurlencode($row2['id_group']).'",';
			$g .= '"gsm":"'.rawurlencode($row2["gsm"]). '",';
			$g .= '"width":"'.rawurlencode($row2["width"]). '",';
			$g .= '"bcg":"'.rawurlencode($row2["b_cons_kg"]). '"}';
		}


		$result = '{ "status":"ok", "message":"1", "records": ['.$g.'] }';
		return $result;
	}
}
?>