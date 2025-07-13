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
				,A.v_noinvoicecommercial
				,A.v_pono
				,date(A.d_insert) d_insert
				,A.n_idinvoiceheader,IH.n_typeinvoice
				,IH.n_post 
				,IH.n_typeinvoice 
				,IH.invdate 
				,ACT.kpno
				,MSST.Styleno
				FROM invoice_commercial A 
					LEFT JOIN invoice_header IH ON A.v_noinvoicecommercial = IH.invno
					LEFT JOIN invoice_detail ID ON IH.id = ID.id_inv
					LEFT JOIN so_det SOD ON SOD.id = ID.id_so_det
					LEFT JOIN so SO ON SO.id = SOD.id_so
					LEFT JOIN act_costing ACT ON ACT.id = SO.id_cost
					LEFT JOIN bpb BPB ON BPB.id_so_det = SOD.id
					LEFT JOIN masterstyle MSST ON BPB.id_item = MSST.id_item
					
					GROUP BY A.n_id 
			
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
			$outp .= '"id_invoiceheader":"'. rawurlencode($row["n_idinvoiceheader"]). '",'; 
			$outp .= '"no_surat":"'. rawurlencode($row["v_noinvoicecommercial"]). '",'; 
			$outp .= '"date":"'. rawurlencode($row["invdate"]). '",'; 
			$outp .= '"typeinvoice":"'. rawurlencode($row["n_typeinvoice"]). '",'; 
			$outp .= '"post":"'. rawurlencode($row["n_post"]). '",'; 
			$outp .= '"ws":"'. rawurlencode($row["kpno"]). '",'; 
			$outp .= '"style":"'. rawurlencode($row["Styleno"]). '",'; 
			$outp .= '"po":"'. rawurlencode($row["v_pono"]). '"}'; 			
		}
		$records['id'] = $id;		
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}
}




?>




