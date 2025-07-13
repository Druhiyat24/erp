<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data['journal']);
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
		
		$q = "SELECT id_journal
				,ifnull(A.fg_tax,0)fg_tax
				,ifnull(A.n_ppn,0)n_ppn
				,ifnull(A.n_pph,0)n_pph 
				,A.inv_supplier
				,B.v_fakturpajak
				,B.d_fakturpajak
				,A.inv_supplier
				,A.d_invoice
				FROM fin_journal_h A
				LEFT JOIN (SELECT v_idjournal,v_fakturpajak,d_fakturpajak FROM fin_journalheaderdetail)B ON A.id_journal = B.v_idjournal
				WHERE A.id_journal = '$id';
		";
		//echo $q;
		$q_det = "SELECT CONCAT('row_',id_bpb)id_select,row_id id_json,n_pph FROM fin_journal_d WHERE id_journal = '$id' AND credit > 0 AND (n_pph IS NOT NULL OR n_pph !='' OR n_pph > 0)";
		$MyList = $this->CompileQuery($q,'SELECT');
		$outp = '';
		$det = '';		
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
					

		
		
		
		
 		while($row = mysqli_fetch_array($MyList)){

			if ($outp != "") {$outp .= ",";}
			if(ISSET($row['d_fakturpajak']) || !EMPTY($row['d_fakturpajak'])  ){
			if($row['d_fakturpajak'] == '1970-01-01'){
				//echo "123";
				$row['d_fakturpajak'] = "";
			}else{
								$row['d_fakturpajak'] = date('d M Y', strtotime($row['d_fakturpajak']));
				
			}				
			}
			if($row['d_fakturpajak'] == '1970-01-01'){
				$row['d_fakturpajak'] = "";
			}
			$outp .= '{"fg_tax":"'.rawurlencode($row['fg_tax']).'",'; 	
			$outp .= '"ppn":"'.rawurlencode($row['n_ppn']).'",'; 	
			$outp .= '"inv_supplier":"'.rawurlencode($row['inv_supplier']).'",'; 	
			$outp .= '"fakturpajak":"'.rawurlencode($row['v_fakturpajak']).'",'; 	
			$outp .= '"tglpajak":"'.rawurlencode($row['d_fakturpajak']).'",'; 	
			$outp .= '"tglinvoice":"'.rawurlencode($row['d_invoice']).'",'; 
			$outp .= '"pph":"'.rawurlencode($row['n_pph']).'"}';	
		} 		
				
			}		
		}
		
		
		$MyList = $this->CompileQuery($q_det,'SELECT');
		
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
			while($row = mysqli_fetch_array($MyList)){
	
				if ($det != "") {$det .= ",";}

				$det .= '{"id_select":"'.rawurlencode($row['id_select']).'",'; 	
				$det .= '"id_json":"'.rawurlencode($row['id_json']).'",';	
				$det .= '"value":"'.rawurlencode($row['n_pph']).'"}';	
				} 		
				
			}		
		}		
		
		
		$result = '{ "status":"ok", "message":"1", "records":['.$outp.'],"detail":['.$det.']}';
		return $result;
	}
}




?>




