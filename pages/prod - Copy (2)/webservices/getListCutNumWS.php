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
							co.id_cost,
							CONCAT(ac.ws, ' | ', co.color) AS ws,
							co.color,
							CONCAT(co.id_cost, '_', co.color) AS id_ws_cut_num
						FROM prod_cut_out AS co
						INNER JOIN (
							SELECT 
								ac.id,
								ac.kpno AS ws
							FROM act_costing AS ac
						) AS ac ON ac.id = co.id_cost
						GROUP BY id_ws_cut_num
					) AS xxx
					ORDER BY xxx.id_cost DESC
			"; 
			// echo $sql1;
			$stmt = mysql_query($sql1);
		}
		else{
			$sql2 = "SELECT 
					cn.id_cut_number,
					cn.id_cost,
					cn.color,
					cn.id_ws_cut_num,
					CONCAT(ac.kpno, ' | ', cn.color) AS ws
				FROM prod_cut_number AS cn
				INNER JOIN act_costing AS ac ON ac.id = cn.id_cost
				WHERE cn.id_cut_number = '{$url}'
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




