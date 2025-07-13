<?php 
$data = $_POST;

$getListData = new getListData();

// $id = explode("_",$_POST['id']);
// $id_cost = $id[0];
// $sec_in = $id[1];

$id_url = $_POST['id'];

// print_r($id_url);die();

$List = $getListData->get($id_url);
print_r($List);
 
class getListData {
	public function get($id_url){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT 
				os.id_sec_out,
				os.id_cost,
				CONCAT(ac.ws, ' | ', ac.style) AS ws,
				sod.id_supplier,
				msup.supplier,
				os.notes
			FROM prod_sec_out AS os
			INNER JOIN (
				SELECT 
					ac.id,
					ac.kpno AS ws,
					ac.styleno AS style
				FROM act_costing AS ac
			) AS ac ON ac.id = os.id_cost
			INNER JOIN (
				SELECT 
					sod.id_sec_out_detail,
					sod.id_sec_out,
					sod.id_sec_in_detail,
					sid.id_supplier
				FROM prod_sec_out_detail AS sod
				INNER JOIN (
					SELECT 
						sid.id_sec_in_detail,
						sid.id_sec_in,
						si.id_supplier
					FROM prod_sec_in_detail AS sid
					INNER JOIN (
						SELECT 
							si.id_sec_in,
							si.dept_subkon AS id_supplier
						FROM prod_sec_in AS si
					) AS si ON si.id_sec_in = sid.id_sec_in
				) AS sid ON sid.id_sec_in_detail = sod.id_sec_in_detail
			) AS sod ON sod.id_sec_out = os.id_sec_out
			INNER JOIN (
				SELECT 
					msup.Id_Supplier AS id_supplier,
					msup.Supplier AS supplier
				FROM mastersupplier AS msup
			) AS msup ON msup.id_supplier = sod.id_supplier
			WHERE os.id_sec_out = '{$id_url}'
			GROUP BY os.id_sec_out
		"; 
		// echo $q;die();
		$stmt = mysql_query($q);

		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['id_sec_out']).'",';
			$outp .= '"id_cost":"'.rawurlencode($row["id_cost"]).'",';
			$outp .= '"ws":"'.rawurlencode($row["ws"]).'",';
			$outp .= '"supplier":"'.rawurlencode($row["supplier"]).'",';
			$outp .= '"notes":"'.rawurlencode($row["notes"]).'"}';
		}
		$result = '{ "status":"ok", "message":"1", "records":['.$outp.'] }';
		return $result;
	}
}
?>