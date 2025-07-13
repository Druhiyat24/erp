<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get();
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get(){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT A.n_id
		,A.n_id_invoice
		,A.v_pono
		,B.invno
		,C.so_no
		,DATE(A.d_insert) d_insert 
		FROM shp_estimatepackinglist A
		LEFT JOIN(
			SELECT id,invno FROM invoice_header
		)B ON A.n_id_invoice = B.id
		LEFT JOIN(
			SELECT id,so_no FROM so
		)C ON A.n_so_id = C.id";

		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		$td = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['n_id']).'",';
			$outp .= '"no_surat":"'. rawurlencode($row["so_no"]). '",'; 
			$outp .= '"date":"'. rawurlencode($row["d_insert"]). '",'; 
			$outp .= '"po":"'. rawurlencode($row["v_pono"]). '"}'; 			
		}
		$records['id'] = $id;		
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}
}




?>




