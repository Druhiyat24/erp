<?php 
$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

$getListData = new getListData();

$id = explode("_",$_POST['id']);
$id_cost = $id[0];
$sec_in = $id[1];
// print_r($id_cost);die();

$List = $getListData->get($id_cost,$sec_in);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($id_cost,$sec_in){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT 
				psi.id_sec_in,
				psi.id_cost AS id,
				psi.dept_subkon AS id_supp,
				sup.supplier
				-- psi.proces AS id_pro,
				-- pro.proses
			FROM prod_sec_in AS psi
			INNER JOIN (
				SELECT 
					sup.Id_Supplier AS id,
					sup.Supplier AS supplier
				FROM mastersupplier AS sup
			) AS sup ON sup.id = psi.dept_subkon
			-- INNER JOIN (
			-- 	SELECT 
			-- 		pro.id AS id_pro,
			-- 		CONCAT(pro.cfcode, '-', pro.cfdesc) AS proses
			-- 	FROM mastercf AS pro
			-- ) AS pro ON pro.id_pro = psi.proces
			WHERE psi.id_cost = '{$id_cost}' AND psi.id_sec_in = '{$sec_in}'
		"; 
		// echo $q;die();
		$stmt = mysql_query($q);		

		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['id']).'",';
			// $outp .= '"id_supp":"'.rawurlencode($row["id_supp"]).'",';
			$outp .= '"supplier":"'.rawurlencode($row["supplier"]).'"}';
			// $outp .= '"id_pro":"'. rawurlencode($row["id_pro"]). '",';
			// $outp .= '"proses":"'. rawurlencode($row["proses"]). '"}';
		}
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.'] }';
		return $result;
	}
}
?>