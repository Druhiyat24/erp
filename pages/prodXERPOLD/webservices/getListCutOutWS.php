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
					xxx.id_ws_cut_out
				FROM (
					SELECT 
						mr.id_cost,
						CONCAT(ac.ws, ' | ', mr.fabric_color) AS ws,
						mr.fabric_color AS color,
						CONCAT(mr.id_cost, '_', mr.fabric_color) AS id_ws_cut_out
					FROM prod_m_roll AS mr
					INNER JOIN (
						SELECT 
							ac.id,
							ac.kpno AS ws
						FROM act_costing AS ac
					) AS ac ON ac.id = mr.id_cost
					GROUP BY id_ws_cut_out
				) AS xxx
				-- WHERE xxx.id_ws_cut_out NOT IN (
				-- 	SELECT 
				-- 		co.id_ws_cut_out
				-- 	FROM prod_cut_out AS co
				-- )
			"; 
			// echo $sql1;
			$stmt = mysql_query($sql1);
		}
		else{
			$sql2 = "SELECT 
					co.id_cut_out,
					co.id_ws_cut_out,
					co.id_cost,
					co.color,
					CONCAT(ac.ws, ' | ', co.color) AS ws
				FROM prod_cut_out AS co
				INNER JOIN (
					SELECT 
						ac.id,
						ac.kpno AS ws
					FROM act_costing AS ac
				) AS ac ON ac.id = co.id_cost
				WHERE co.id_cut_out = '{$url}'
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




