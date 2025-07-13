<?php 
$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

$getListWS = new getListWS();
$List = $getListWS->get();
print_r($List);

class getListWS {
	public function get(){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT ac.id AS id_cost,ac.kpno AS ws,j.id AS id_jo
				FROM act_costing AS ac 
				INNER JOIN so AS s ON s.id_cost=ac.id
				INNER JOIN so_det AS sd ON sd.id_so=s.id
				INNER JOIN bom_jo_item AS bji ON bji.id_so_det=sd.id
				INNER JOIN jo AS j ON j.id=bji.id_jo GROUP BY ac.kpno ORDER BY ac.id DESC"; 
				//	echo $q;
		$stmt = mysql_query($q);		

		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id_cost":"'.rawurlencode($row['id_cost']).'",';
			$outp .= '"ws":"'. rawurlencode($row["ws"]). '",';
			$outp .= '"id_jo":"'. rawurlencode($row["id_jo"]). '"}';
		}
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}
?>




