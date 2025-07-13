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
						xxx.id_cost,
						xxx.ws,
						xxx.color,
						xxx.id_ws_cut_num
					FROM (
						SELECT 
							cn.id_cost,
							CONCAT(ac.ws, ' | ', cn.color) AS ws,
							cn.color,
							CONCAT(cn.id_cost, '_', cn.color) AS id_ws_cut_num
						FROM prod_cut_number AS cn
						INNER JOIN (
							SELECT 
								ac.id,
								ac.kpno AS ws
							FROM act_costing AS ac
						) AS ac ON ac.id = cn.id_cost
						GROUP BY id_ws_cut_num
					) AS xxx
					ORDER BY xxx.id_cost DESC
			"; 
			// echo $sql1;
			$stmt = mysql_query($sql1);
		}
		else{
			$sql2 = "SELECT 
					cq.id_cut_qc,
					cq.id_cost,
					cq.color,
					cq.id_ws_cut_qc,
					CONCAT(ac.kpno, ' | ', cq.color) AS ws
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
			$outp .= '"color":"'. rawurlencode($row["color"]). '",';
			$outp .= '"ws":"'. rawurlencode($row["ws"]). '"}';
		}
		$result = '{ "status":"ok", "message":"1", "records":['.$outp.'] }';
		return $result;
	
	}
}

?>




