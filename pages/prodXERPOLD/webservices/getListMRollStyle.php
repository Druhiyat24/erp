<?php 
$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

$getListWS = new getListWS();
$id_cost = $_POST['id_cost'];
$List = $getListWS->get($id_cost);
print_r($List);
// print_r($id_url);die();

class getListWS {
	public function get($id_cost){
		include __DIR__ .'/../../../include/conn.php';

		$q = "SELECT 
				ac.id AS id_cost, 
				ac.kpno AS ws, 
				ac.styleno 
			FROM act_costing AS ac WHERE ac.id = '{$id_cost}'
		";
		// echo $q;
		$stmt = mysql_query($q);
				

		$id = array(); 
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id_cost":"'.rawurlencode($row['id_cost']).'",';
			$outp .= '"style":"'. rawurlencode($row["styleno"]). '"}';
		}
			$result = '{ "respon": "200", "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}
?>