<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
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
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT
				 A.id
				,A.invno
				,A.date_paclist
				,A.id_buyer
				,A.consignee
				,A.shipper
				,A.notify_party
				,A.country_of_origin
				,A.manufacture_address
				,A.vessel_name
				,A.port_of_loading
				,A.port_of_discharge
				,A.port_of_entrance
				,A.lc_no
				,A.lc_issue_by
				,A.hs_code
				,A.etd
				,A.eta
				,A.eta_lax
				,A.id_pterms
				,A.shipped_by
				,A.route
				,A.ship_to
				,A.nw
				,A.gw
				,A.measurement
				,A.container_no
				,A.seal_no
				,A.n_typeinvoice
				,A.n_post
				,if(A.v_fakturpajak = '',C.v_fakturpajak,A.v_fakturpajak) v_fakturpajak
				,ACT.id id_cost
				,ACT.kpno
				,MS.Supplier
			FROM invoice_header A
				LEFT JOIN(
					SELECT id_journal,reff_doc FROM fin_journal_h 
				
				)B ON A.invno = B.reff_doc
				LEFT JOIN(
					SELECT v_idjournal,v_fakturpajak FROM fin_journalheaderdetail
				)C ON C.v_idjournal =  B.id_journal
				LEFT JOIN invoice_detail ID ON ID.id_inv = A.id
				LEFT JOIN so_det SOD ON SOD.id = ID.id_so_det
				LEFT JOIN so SO ON SOD.id_so = SO.id
				LEFT JOIN act_costing ACT ON ACT.id = SO.id_cost
				LEFT JOIN mastersupplier MS ON MS.Id_Supplier = A.id_buyer				
			WHERE A.id = '$id' GROUP BY A.id";
		
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
 		while($row = mysqli_fetch_array($MyList)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['id']).'",';
			$outp .= '"invno":"'.rawurlencode($row["invno"]). '",'; 	
			$outp .= '"invdate":"'.rawurlencode(date('d M Y',strtotime($row["date_paclist"]))). '",'; 	
			$outp .= '"id_buyer":"'.rawurlencode($row["id_buyer"]). '",'; 	
			$outp .= '"consignee":"'.rawurlencode($row["consignee"]). '",'; 	
			$outp .= '"shipper":"'.rawurlencode($row["shipper"]). '",'; 	
			$outp .= '"notify_party":"'.rawurlencode($row["notify_party"]). '",'; 	
			$outp .= '"country_of_origin":"'.rawurlencode($row["country_of_origin"]). '",'; 	
			$outp .= '"manufacture_address":"'.rawurlencode($row["manufacture_address"]). '",'; 	
			$outp .= '"vessel_name":"'.rawurlencode($row["vessel_name"]). '",'; 	
			$outp .= '"port_of_loading":"'.rawurlencode($row["port_of_loading"]). '",'; 	
			$outp .= '"port_of_discharge":"'.rawurlencode($row["port_of_discharge"]). '",'; 	
			$outp .= '"port_of_entrance":"'.rawurlencode($row["port_of_entrance"]). '",'; 	
			$outp .= '"lc_no":"'.rawurlencode($row["lc_no"]). '",'; 	
			$outp .= '"lc_issue_by":"'.rawurlencode($row["lc_issue_by"]). '",'; 	
			$outp .= '"hs_code":"'.rawurlencode($row["hs_code"]). '",'; 	
			$outp .= '"etd":"'.rawurlencode(date('d M Y',strtotime($row["etd"]))). '",'; 	
			$outp .= '"eta":"'.rawurlencode(date('d M Y',strtotime($row["eta"]))). '",'; 	
			$outp .= '"eta_lax":"'.rawurlencode(date('d M Y',strtotime($row["eta_lax"]))). '",'; 	
			$outp .= '"id_pterms":"'.rawurlencode($row["id_pterms"]). '",'; 	
			$outp .= '"shipped_by":"'.rawurlencode($row["shipped_by"]). '",'; 	
			$outp .= '"route":"'.rawurlencode($row["route"]). '",'; 	  
			$outp .= '"ship_to":"'.rawurlencode($row["ship_to"]). '",'; 	
			$outp .= '"nw":"'.rawurlencode($row["nw"]). '",'; 	
			$outp .= '"gw":"'.rawurlencode($row["gw"]). '",'; 	
			$outp .= '"ws":"'.rawurlencode($row["kpno"]). '",'; 	
			$outp .= '"typeinvoice":"'.rawurlencode($row["n_typeinvoice"]). '",'; 	
			$outp .= '"post":"'.rawurlencode($row["n_post"]). '",'; 	
			$outp .= '"measurement":"'.rawurlencode($row["measurement"]). '",'; 	
			$outp .= '"container_no":"'.rawurlencode($row["container_no"]). '",'; 	
			$outp .= '"faktur_pajak":"'.rawurlencode($row["v_fakturpajak"]). '",';
			$outp .= '"supplier":"'.rawurlencode($row["Supplier"]). '",';
			$outp .= '"seal_no":"'.rawurlencode($row["seal_no"]). '"}'; 	
		} 		
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';	
			}		
		}
		
		return $result;
	}
}




?>




