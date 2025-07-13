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
					dj.id_dc_join,
					dj.id_cost,
					CONCAT(ac.kpno, ' | ', ac.styleno) AS ws
				FROM prod_dc_join AS dj
				INNER JOIN act_costing AS ac ON ac.id = dj.id_cost
				GROUP BY dj.id_cost
				ORDER BY dj.id_dc_join DESC
			"; 
			// echo $q1;
			$stmt = mysql_query($q1);
		}
		else{
			$q2 = "SELECT 
					ds.id_dc_set,
					ds.id_cost,
					CONCAT(ac.kpno, ' | ', ac.styleno) AS ws
				FROM prod_dc_set AS ds
				INNER JOIN (
					SELECT 
						ac.id,
						ac.kpno,
						ac.styleno
					FROM act_costing AS ac
				) AS ac ON ac.id = ds.id_cost
				WHERE ds.id_dc_set = '{$url}'
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
		$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}

?>