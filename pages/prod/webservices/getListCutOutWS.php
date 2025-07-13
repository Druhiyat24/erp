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
					xxx.ws
				FROM (
					SELECT 
						mr.id_cost,
						CONCAT(ac.ws, ' | ', ac.style) AS ws
					FROM prod_m_roll AS mr
					INNER JOIN (
						SELECT 
							ac.id,
							ac.kpno AS ws,
							ac.styleno AS style
						FROM act_costing AS ac
					) AS ac ON ac.id = mr.id_cost
					GROUP BY mr.id_cost
				) AS xxx
				ORDER BY xxx.id_cost DESC
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
					CONCAT(ac.ws, ' | ', ac.style) AS ws
				FROM prod_cut_out AS co
				INNER JOIN (
					SELECT 
						ac.id,
						ac.kpno AS ws,
						ac.styleno AS style
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
			$outp .= '"ws":"'. rawurlencode($row["ws"]). '"}';
		}
		$result = '{ "status":"ok", "message":"1", "records":['.$outp.'] }';
		return $result;
	
	}
}

?>




