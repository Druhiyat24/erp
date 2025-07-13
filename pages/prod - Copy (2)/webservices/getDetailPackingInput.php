<?php
$data = $_POST;
//$data = (object)$_POST['data'];


//print_r($data);
//if($data['code'] == '1' ){

$getListData = new getListData();
$List = $getListData->get($_POST['id_ws']);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($id){
		include __DIR__ .'/../../../include/conn.php';
		include __DIR__ .'/../../forms/fungsi.php';
		#$id_jo = flookup("group_concat(distinct id_jo)","bppb","bppbno='$id'");
		// echo "$id_jo";die();
		$q = "SELECT 
				scd.* 
			FROM (
			
				SELECT 
					co.id_cut_out,
					co.id_cost,
					cod.id_cut_out_detail,
					cod.embro,
					cod.printing,
					cod.heat_transfer,
					cod.size,
					cod.color,
					cod.ok_cutt,
					buy.buyer,
					ac.styleno,
					ac.ws
				FROM prod_cut_out AS co
				INNER JOIN prod_cut_out_detail AS cod ON cod.id_cut_out = co.id_cut_out
				INNER JOIN (
					SELECT 
						ac.id,
						ac.kpno AS ws,
						ac.id_buyer,
						ac.styleno
					FROM act_costing AS ac
				) AS ac ON ac.id = co.id_cost
				INNER JOIN (
					SELECT 
						buy.Id_Supplier,
						buy.Supplier AS buyer
					FROM mastersupplier AS buy
					WHERE buy.tipe_sup = 'C'
				) AS buy ON buy.Id_Supplier = ac.id_buyer
				
				WHERE cod.embro != '0.00' OR cod.printing != '0.00' OR cod.heat_transfer != '0.00'
			
			) AS scd WHERE scd.id_cost = '{$id}'
		"; 
			// echo "$q";
		$stmt = mysql_query($q);		
		$id = array();
		$outp = '';
		// $no = 0;
		while($row = mysql_fetch_array($stmt)){
			
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"supplier":"'.rawurlencode($row['buyer']).'",';
			$outp .= '"styleno":"'. rawurlencode($row["styleno"]). '",';
			$outp .= '"ws":"'. rawurlencode($row["ws"]). '",';
			$outp .= '"color":"'. rawurlencode($row["color"]). '",';
			$outp .= '"size":"'. rawurlencode($row["size"]). '",';
			$outp .= '"ok_cutt":"'. rawurlencode($row["ok_cutt"]). '"}';
		}
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}
?>




