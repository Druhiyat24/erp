<?php 
$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

$getListWS = new getListWS();
$url = $_GET['id_url'];
$List = $getListWS->get($url);
print_r($List);
// print_r($id_url);die();

class getListWS {
	public function get($url){
		include __DIR__ .'/../../../include/conn.php';

		if($url == '-1'){
			$q1 = "SELECT 
					sr.id_number,
					sr.id_cost,
					ac.ws
				FROM prod_spread_report_number AS sr
				INNER JOIN (
					SELECT 
						ac.id,
						ac.kpno AS ws,
						ac.styleno
					FROM act_costing AS ac
				) AS ac ON ac.id = sr.id_cost
				WHERE sr.id_cost NOT IN (
					SELECT mr.id_cost FROM prod_m_roll AS mr
				)
			";
			// echo $q;
			$stmt = mysql_query($q1);
		} else {
			$q2 = "SELECT 
					mr.id_m_roll, 
					mr.id_cost,
					ac.ws
				FROM prod_m_roll AS mr
				INNER JOIN (SELECT ac.id, ac.kpno AS ws FROM act_costing AS ac) AS ac ON ac.id = mr.id_cost
				WHERE mr.id_m_roll = '{$url}'
			";
			$stmt = mysql_query($q2);
		}
			// echo $q2;		

		$id = array(); 
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id_cost":"'.rawurlencode($row['id_cost']).'",';
			$outp .= '"ws":"'. rawurlencode($row["ws"]). '"}';
		}
			$result = '{ "respon" : "200", "status" : "ok", "message" : "1", "records" : ['.$outp.'] }';
		return $result;
	}
}
?>