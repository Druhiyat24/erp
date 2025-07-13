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
		$q = "
		
	SELECT A.n_id
		,A.n_id_invoice
		,A.v_pono
		,A.v_from
		,A.v_to
		,A.n_so_id
		,A.n_amount	
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
		)C ON A.n_so_id = C.id	
		WHERE A.n_id = '$id'
		


";
//echo $q;
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		$td = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}


			$outp .= '{"id":"'.rawurlencode($row['n_id']).'",';
				$outp .= '"n_idinvoiceheader":"'. rawurlencode($row["n_id_invoice"]). '",'; 
				$outp .= '"v_from":"'. rawurlencode($row["v_from"]). '",'; 
				$outp .= '"v_to":"'. rawurlencode($row["v_to"]). '",'; 
				$outp .= '"so_no":"'. rawurlencode($row["n_so_id"]). '",'; 
				$outp .= '"v_pono":"'. rawurlencode($row["v_pono"]). '",'; 
			$outp .= '"n_amount":"'. rawurlencode($row["n_amount"]). '"}'; 	
		}
		$records['id'] = $id;		
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}
}




?>