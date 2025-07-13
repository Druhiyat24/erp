<?php 
$data = $_POST;

$getListData = new getListData();
$url = $_GET['id_url'];

$List = $getListData->get($url);
print_r($List);

class getListData {
	public function get($url){
		include __DIR__ .'/../../../include/conn.php';

		if($url == '-1'){
			$q1 = "SELECT 
					cqc.id_cost,
					CONCAT(ac.kpno, ' | ', ac.styleno) AS ws
				FROM prod_cut_qc AS cqc
				INNER JOIN act_costing AS ac ON ac.id = cqc.id_cost
				GROUP BY cqc.id_cost
				ORDER BY cqc.id_cost DESC
			"; 
			// echo $q1;
			$stmt = mysql_query($q1);
		}
		else{
			$q2 = "SELECT 
					dc.id_dc_join,
					dc.id_cost,
					CONCAT(ac.kpno, ' | ', ac.styleno) AS ws
				FROM prod_dc_join AS dc
				INNER JOIN act_costing AS ac ON ac.id = dc.id_cost
				WHERE dc.id_dc_join = '{$url}'
			";
			// echo $q2;
			$stmt = mysql_query($q2);
		}

		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['id_cost']).'",';
			$outp .= '"ws":"'.rawurlencode($row["ws"]).'"}';
		}
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.'] }';
		return $result;
	}
}

?>