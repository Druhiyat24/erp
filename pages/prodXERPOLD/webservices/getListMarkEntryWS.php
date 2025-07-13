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

		$q1 = "SELECT 
				ac.id AS id_cost,
				s.id AS id_so,
				CONCAT(ac.kpno, ' | ', ac.styleno) AS ws
			FROM act_costing AS ac
			INNER JOIN (
				SELECT 
					s.id,
					s.id_cost 
				FROM so AS s
				WHERE s.cancel_h = 'N'
			) AS s ON s.id_cost = ac.id
			INNER JOIN jo_det JOD ON JOD.id_so = s.id
			INNER JOIN bppb_req BREQ ON BREQ.id_jo = JOD.id_jo
			INNER JOIN view_portal_bom VBOM ON BREQ.id_jo = VBOM .id_jo AND VBOM.id_item_ms_item = BREQ.id_item
			WHERE ac.id NOT IN (SELECT me.id_cost FROM prod_mark_entry AS me)
			GROUP BY ac.id
			ORDER BY ac.id DESC
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
		$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}
?>




