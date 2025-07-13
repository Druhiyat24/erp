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
select A.n_id
		,A.v_noinvoicecommercial
		,IH.n_typeinvoice
		,IH.n_post
		,IH.invno
		,IH.v_fakturpajak
		,IH.n_idcoa
		,A.n_idinvoiceheader
		,A.v_from
		,A.v_to
		,A.v_pono
		,A.n_amount
		,A.bpbno
				FROM invoice_commercial A
		LEFT JOIN invoice_header IH ON A.v_noinvoicecommercial = IH.invno
				WHERE n_id = '$id'
";
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		$td = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}


			$outp .= '{"id":"'.rawurlencode($row['n_id']).'",';
				$outp .= '"v_noinvoicecommercial":"'. rawurlencode($row["v_noinvoicecommercial"]). '",'; 
				$outp .= '"n_idinvoiceheader":"'. rawurlencode($row["n_idinvoiceheader"]). '",'; 
				$outp .= '"v_from":"'. rawurlencode($row["v_from"]). '",'; 
				$outp .= '"post":"'. rawurlencode($row["n_post"]). '",'; 
				$outp .= '"invno":"'. rawurlencode($row["invno"]). '",';
				$outp .= '"fakturpajak":"'. rawurlencode($row["v_fakturpajak"]). '",';
				$outp .= '"typeinvoice":"'. rawurlencode($row["n_typeinvoice"]). '",';
				$outp .= '"v_to":"'. rawurlencode($row["v_to"]). '",'; 
				$outp .= '"bpbno":"'. rawurlencode($row["bpbno"]). '",'; 
				$outp .= '"bpbno":"'. rawurlencode($row["bpbno"]). '",'; 
				$outp .= '"idcoa":"'. rawurlencode($row["n_idcoa"]). '",'; 
				$outp .= '"v_pono":"'. rawurlencode($row["v_pono"]). '",'; 
			$outp .= '"n_amount":"'. rawurlencode($row["n_amount"]). '"}'; 	
		}
		$records['id'] = $id;		
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}
}




?>




