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
		,IH.n_discount
		,IH.fg_discount
		,A.n_idinvoiceheader
		,A.v_from
		,A.v_to
		,A.v_pono
		,A.n_amount
		,A.bpbno
		,ACT.kpno
		,ACT.comm_cost
		,if(IH.fg_discount = '0',ACT.comm_cost,IH.n_discount)discount
				FROM invoice_commercial A
		LEFT JOIN invoice_header IH ON A.v_noinvoicecommercial = IH.invno
		LEFT JOIN invoice_detail ID ON IH.id = ID.id_inv
		LEFT JOIN so_det SOD ON SOD.id = ID.id_so_det
		LEFT JOIN so SO ON SO.id = SOD.id_so
		LEFT JOIN act_costing ACT ON ACT.id = SO.id_cost
		
				WHERE n_id = '$id' GROUP BY A.v_noinvoicecommercial
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
				$outp .= '"discount":"'. rawurlencode($row["discount"]). '",'; 
				$outp .= '"fg_discount":"'. rawurlencode($row["fg_discount"]). '",'; 
				$outp .= '"v_pono":"'. rawurlencode($row["v_pono"]). '",'; 
			$outp .= '"n_amount":"'. rawurlencode($row["n_amount"]). '"}'; 	
		}
		$records['id'] = $id;		
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}
}




?>




