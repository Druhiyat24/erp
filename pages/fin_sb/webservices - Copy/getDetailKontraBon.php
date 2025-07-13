<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
include __DIR__ .'/../../forms/journal_interface.php';
if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data['id']);
print_r($List);
}
else{
	exit;
}
class getListData {
	
	function CompileQuery($query,$mode){
		
		include __DIR__ .'/../../../include/conn.php';
		$stmt = mysqli_query($conn_li,$query);	
		if(mysqli_error($conn_li)){
		
			$result = mysqli_error($conn_li)."__ERRTRUE";
			return $result;
		}	
		else{
			if($mode == "CRUD"){
				print_r($query);
				$result = '{ "status":"ok", "message":"1"}';
				return $result;
			}
			else{
				
				if(mysqli_num_rows($stmt) == '0' ){
					$result = '{ "status":"ok", "message":"2"}';
					return '0';
				}
				else{
					return $stmt;
				}
			}
		} 
	}	
	
	
	public function get($id){
		//$value_pph = get_pph_kontra_bon($id,"KB");
		$q = "SELECT   A.id_journal
		,A.id_bpb
		,A.reff_doc2 journal_ref
		,A.reff_doc
		,A.id_po
		,A.id_supplier
		,FH_ref.date_journal date_journal_ref
		,B.invno
		,B.bpbno
		,B.bpbno_int
		,A.curr
		,D.date_journal
		,C.pono
		,MS.Supplier
		,A.n_ppn
		,A.n_pph
		,(A.qty*A.price)amt_ori
		,((A.qty*A.price) * ( (A.n_ppn/100) * (A.qty*A.price) ) )ppn
		,((A.qty*A.price) * ( (MT.percentage/100) * (A.qty*A.price) ) )pph
		,DATE_ADD(B.bpbdate, INTERVAL MS.terms_of_pay DAY) due_date
		,((A.qty*A.price) + ( ( (ifnull(A.n_ppn,0)/100) * (A.qty*A.price) ) ) -  ( ( (ifnull(MT.percentage,0)/100) * (A.qty*A.price) ) ))amount
		FROM fin_journal_d A
		LEFT JOIN
		bpb B ON A.id_bpb = B.id
		LEFT JOIN
		po_header C ON A.id_po = C.id
		LEFT JOIN fin_journal_h D ON A.id_journal = D.id_journal
		LEFT JOIN mastersupplier MS ON A.id_supplier = MS.Id_Supplier
		LEFT JOIN fin_journal_h FH_ref ON A.reff_doc2 = FH_ref.id_journal
		LEFT JOIN mtax MT ON A.n_pph = MT.idtax
		WHERE A.id_journal = '$id' AND id_bpb IS NOT NULL
		AND A.credit > 0
		
		UNION ALL
		
SELECT   A.id_journal
		,A.id_bpb
		,A.reff_doc2 journal_ref
		,A.reff_doc
		,A.id_po
		,A.id_supplier
		,FH_ref.date_journal date_journal_ref
		,B.invno
		,B.bppbno
		,B.bppbno_int
		,A.curr
		,D.date_journal
		,C.pono
		,MS.Supplier
		,A.n_ppn
		,A.n_pph
		,(A.qty*A.price)amt_ori
		,((A.qty*A.price) * ( (A.n_ppn/100) * (A.qty*A.price) ) )ppn
		,((A.qty*A.price) * ( (MT.percentage/100) * (A.qty*A.price) ) )pph
		,DATE_ADD(B.bppbdate, INTERVAL MS.terms_of_pay DAY) due_date
		,(((A.qty*A.price) + ( ( (ifnull(A.n_ppn,0)/100) * (A.qty*A.price) ) ) -  ( ( (ifnull(MT.percentage,0)/100) * (A.qty*A.price) ) ))*-1)amount
		FROM fin_journal_d A
		LEFT JOIN
		bppb B ON A.id_bppb = B.id
		LEFT JOIN
		po_header C ON A.id_po = C.id
		LEFT JOIN fin_journal_h D ON A.id_journal = D.id_journal
		LEFT JOIN mastersupplier MS ON A.id_supplier = MS.Id_Supplier
		LEFT JOIN fin_journal_h FH_ref ON A.reff_doc2 = FH_ref.id_journal
		LEFT JOIN mtax MT ON A.n_pph = MT.idtax
		WHERE A.id_journal = 'NAG-PK-2003-00486' AND id_bppb IS NOT NULL
		AND A.credit > 0		
		
		";
		$MyList = $this->CompileQuery($q,'SELECT');
		
		if($MyList == '0'){
			$result = '{ "status":"ok", "message":"2", "records":"0"}';
		}
		else{
			    if (!is_object($MyList)) {
					$EXP = explode("__ERRTRUE",$MyList);
					if($EXP[1]){
						$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
					}
				}
			else{
					
		$outp = '';
		$total_nilai = 0;
		$utang = 0;
		$pph = 0;
		$pajak = 0;
		$nama  = "";
		$total_nilai_final = 0;
 		while($row = mysqli_fetch_array($MyList)){
				$total_nilai_final = $total_nilai_final + $row["amount"];
			$nama  = $row['Supplier'];
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"bpb":"'.rawurlencode($row['bpbno']).'",';
			$outp .= '"bpbnoint":"'.rawurlencode($row["reff_doc"]). '",'; 	
			$outp .= '"curr":"'.rawurlencode($row["curr"]). '",'; 	
			$outp .= '"date_inv":"'.rawurlencode($row["due_date"]). '",'; 	
			$outp .= '"id":"'.rawurlencode($row["id_supplier"]). '",'; 	
			$outp .= '"invno":"'.rawurlencode($row["invno"]). '",'; 
			$outp .= '"journal_reff":"'.rawurlencode($row["journal_ref"]). '",'; 
			$outp .= '"date_journal":"'.rawurlencode($row["date_journal"]). '",'; 
			$outp .= '"supplier":"'.rawurlencode($row["Supplier"]). '",';
			$outp .= '"key":"'.rawurlencode($row["bpbno"]). '",'; 
			$outp .= '"pono":"'.rawurlencode($row["pono"]). '",'; 
			$outp .= '"nilai":"'.rawurlencode(number_format($row["amount"],2,',','.')). '"}'; 	
		} 		
			$result = '{ "status":"ok", "message":"1","nama":"'.$nama .'","total_nilai":"'.number_format($total_nilai_final,2,',','.').'", "arraynya":['.$outp.']}';	
			}		
		}
		
		return $result;
	}
}




?>




