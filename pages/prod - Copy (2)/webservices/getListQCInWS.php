<?php 
$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

$getListData = new getListData();
$url = $_GET['id_url'];
// print_r($url);die();
$List = $getListData->get($url);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($url){
		include __DIR__ .'/../../../include/conn.php';

		if($url == '-1'){
			$q1 = "SELECT 
					swo.id_sew_out,
					swo.id_cost,
					CONCAT(ac.kpno, ' | ',ac.styleno) AS ws
				FROM prod_sew_out AS swo
				INNER JOIN (
					SELECT 
						ac.id,
						ac.kpno,
						ac.styleno
					FROM act_costing AS ac
				) AS ac ON ac.id = swo.id_cost
				GROUP BY swo.id_cost
				ORDER BY swo.id_sew_out DESC
			"; 
			// echo $q1;
			$stmt = mysql_query($q1);
		}
		else{
			$q2 = "
			";
			// echo $q2;
			$stmt = mysql_query($q2);
		}

				
		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['id_cost']).'",';
			$outp .= '"sew_out":"'. rawurlencode($row["id_sew_out"]). '",';
			$outp .= '"ws":"'. rawurlencode($row["ws"]). '"}';
		}
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}
?>




