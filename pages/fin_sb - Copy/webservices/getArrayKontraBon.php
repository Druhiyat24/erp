<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data['id_journal']);
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
		$q = "
SELECT   A.id_journal
		,A.id_bpb
		,A.id_supplier
		,A.reff_doc2
		,A.id_po
		,A.id_supplier
		,B.invno
		,B.bpbno
		,B.bpbno_int
		,A.curr
		,D.date_journal
		,C.pono
		,((A.qty*A.price) + ((A.qty*A.price) * ( (A.n_ppn/100) * (A.qty*A.price) ) ) -  ((A.qty*A.price) * ( (A.n_pph/100) * (A.qty*A.price) ) ))amount
		FROM fin_journal_d A
		LEFT JOIN
		bpb B ON A.id_bpb = B.id
		LEFT JOIN
		po_header C ON A.id_po = C.id
		LEFT JOIN fin_journal_h D ON A.id_journal = D.id_journal
		WHERE A.id_journal = '$id' AND id_bpb IS NOT NULL
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
		$array = array();
 		while($row = mysqli_fetch_array($MyList)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"key":"'.($row['bpbno']).'",';
			$outp .= '"id":"'.($row["id_supplier"]). '",'; 
			$outp .= '"nilai":"'.($row["amount"]). '",'; 
			$outp .= '"bpbnoint":"'.($row["bpbno_int"]). '",'; 
			$outp .= '"journal_reff":"'.($row["reff_doc2"]). '",'; 
			$outp .= '"date_journal":"'.($row["date_journal"]). '",'; 
			$outp .= '"invno":"'.($row["invno"]). '",'; 
			$outp .= '"date_inv":"'.($row["date_journal"]). '",'; 
			$outp .= '"pono":"'.($row["pono"]). '",'; 
			$outp .= '"curr":"'.($row["curr"]). '"}'; 	
		} 		
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';	
			}		
		}
		
		return $result;
	}
}




?>




