<?php 
$data = $_POST;

$getListWS = new getListWS();

$ws = $_GET['id_cost'];
$url = $_GET['id_url'];
$List = $getListWS->get($ws, $url);
print_r($List);
// die();

class getListWS {
	public function get($ws, $url){
		
		include __DIR__ .'/../../../include/conn.php';

		if($url == '-1'){
			$sql1 = "SELECT 
					mr.fabric_color AS color,
					CONCAT(mr.id_cost, '_', mr.fabric_color) AS id_ws_cut_out
				FROM prod_m_roll AS mr
				WHERE mr.id_cost = '{$ws}'
				GROUP BY mr.fabric_color
			"; 
			// echo $sql1;
			$stmt = mysql_query($sql1);
		}
		else{
			$sql2 = "SELECT 
					co.id_cut_out,
					co.id_ws_cut_out,
					co.id_cost,
					co.color
				FROM prod_cut_out AS co
				WHERE co.id_cut_out = '{$url}'
			";
			// echo $sql2;
			$stmt = mysql_query($sql2);
		}

		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"color":"'.rawurlencode($row['color']).'",';
			$outp .= '"ws_color":"'.rawurlencode($row['id_ws_cut_out']).'"}';
		}
		$result = '{ "status":"ok", "message":"1", "records":['.$outp.'] }';
		return $result;
	
	}
}

?>