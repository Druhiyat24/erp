<?php 
$data = $_POST;

$getListWS = new getListWS();

$url = $_GET['id_url'];
$List = $getListWS->get($url);
print_r($List);
// die();

class getListWS {
	public function get($url){
		
		include __DIR__ .'/../../../include/conn.php';

		if($url == '-1'){
			$sql1 = "SELECT 
					cn.id_cost,
					CONCAT(ac.ws, ' | ', ac.style) AS ws
				FROM prod_cut_number AS cn
				INNER JOIN (
					SELECT 
						ac.id,
						ac.kpno AS ws,
						ac.styleno AS style
					FROM act_costing AS ac
				) AS ac ON ac.id = cn.id_cost
				GROUP BY cn.id_cost
				ORDER BY cn.id_cost DESC
			"; 
			// echo $sql1;
			$stmt = mysql_query($sql1);
		}
		else{
			$sql2 = "SELECT 
					cq.id_cut_qc,
					cq.id_cost,
					CONCAT(ac.kpno, ' | ', ac.styleno) AS ws
				FROM prod_cut_qc AS cq
				INNER JOIN act_costing AS ac ON ac.id = cq.id_cost
				WHERE cq.id_cut_qc = '{$url}'
			";
			// echo $sql2;
			$stmt = mysql_query($sql2);
		}

		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id_cost":"'.rawurlencode($row['id_cost']).'",';
			$outp .= '"ws":"'. rawurlencode($row["ws"]). '"}';
		}
		$result = '{ "status":"ok", "message":"1", "records":['.$outp.'] }';
		return $result;
	
	}
}

?>




