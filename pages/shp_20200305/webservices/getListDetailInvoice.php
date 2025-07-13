<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data['id']);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($id){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT 
				A.id
				,A.id_inv
				,A.v_noso
				,A.id_so_det
				,A.qty
				,A.unit
				,A.price 
				FROM 
					invoice_detail A
				WHERE A.id = '$id'";
		$stmt = mysql_query($q);		
		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['isi']).'",';
			$outp .= '"id_inv":"'. rawurlencode($row["id_inv"]). '",'; 
			$outp .= '"v_noso":"'. rawurlencode($row["v_noso"]). '",'; 
			$outp .= '"id_so_det":"'. rawurlencode($row["id_so_det"]). '",'; 
			$outp .= '"qty":"'. rawurlencode($row["qty"]). '",'; 
			$outp .= '"unit":"'. rawurlencode($row["unit"]). '",'; 
			$outp .= '"price":"'. rawurlencode($row["price"]). '"}'; 	
		}	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}
}




?>




