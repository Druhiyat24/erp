<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($_POST["id"]);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($id){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT  SOD.id idsod
				,SOD.id_so
				,SOD.dest
				,SOD.size
				,SOD.unit
				,SOD.qty
				,SOD.price
				,SOACT.ws
				,SOACT.idcosting
				,SOACT.so_no
				FROM so_det SOD
			LEFT JOIN(
				SELECT SO.id, SO.id_cost,SO.so_no,ACT.id idcosting,ACT.kpno ws FROM so SO
				LEFT JOIN(
					SELECT id ,kpno FROM act_costing
				)ACT ON SO.id_cost = ACT.id
			) SOACT ON SOD.id_so = SOACT.id
			WHERE SOACT.idcosting = '$id'";
			//echo $q;
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		$td = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"idsod":"'.rawurlencode($row['idsod']).'",';
			$outp .= '"id_so":"'. rawurlencode($row["id_so"]). '",'; 
			$outp .= '"so_no":"'. rawurlencode($row["so_no"]). '",'; 
			$outp .= '"size":"'. rawurlencode($row["size"]). '",'; 
			$outp .= '"qty":"'. rawurlencode($row["qty"]). '",'; 
			$outp .= '"dest":"'. rawurlencode($row["dest"]). '",'; 
			$outp .= '"unit":"'. rawurlencode($row["unit"]). '",';
			$outp .= '"lot":" ",';
			$outp .= '"carton":" ",';
			$outp .= '"price":"'. rawurlencode($row["price"]). '"}'; 	
		}
		$records['id'] = $id;		
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}
}




?>




