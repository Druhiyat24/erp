<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data['id_journal']);
print_r($List);
}
//else{
//	exit;
//}
class getListData {
	protected function GetAllAccountActiva($id_journal){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT v_akunactiva,v_akunakumulasipenyusutan,v_akunbiayapenyusutan,n_iddept,n_idsupdept
				FROM masteractiva WHERE id_journal = '$id_journal'";
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '[{"akunactiva":"'.rawurlencode($row['v_akunactiva']).'",';
			$outp .= '"akunakumulasipenyusutan":"'.rawurlencode($row['v_akunakumulasipenyusutan']).'",';
			$outp .= '"department":"'.rawurlencode($row['n_iddept']).'",';
			$outp .= '"subdepartment":"'.rawurlencode($row['n_idsupdept']).'",';
			$outp .= '"akunbiayapenyusutan":"'. rawurlencode($row["v_akunbiayapenyusutan"]). '"}]'; 	
		}
		return $outp;
	}	


	protected function getCodeActiva($id_journal){
		include __DIR__ .'/../../../include/conn.php';
		$q2 = "SELECT kd_aktiva
				FROM masteractiva WHERE id_journal = '$id_journal' ";
				//echo $q2;
		$stmt2 = mysql_query($q2);		
		$row2 = mysql_fetch_array($stmt2);
		$id = substr($row2[0],-3);
		$id = str_replace(substr($row2[0],-3),"",$row2[0]);	
		
		$q = "SELECT kd_tipe_aktiva,nm_tipe_aktiva
				FROM masteractivatype WHERE kd_tipe_aktiva = '$id' ";
		$stmt = mysql_query($q);	
		

		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '[{"id":"'.rawurlencode($row['kd_tipe_aktiva']).'",';
			$outp .= '"nama":"'. rawurlencode($row["nm_tipe_aktiva"]). '"}]'; 	
		}
		return $outp;
	}		
	
	public function get($Subid){
		
		//$Split = substr($Subid,-3);
		$id = $Subid;
		
		$GetCodeActiva = $this->getCodeActiva($id);	
		
		$GetAllAccountActiva = $this-> GetAllAccountActiva($Subid);
		//print_r($GetCodeActiva);
		//print_r($GetAllAccountActiva);
	
			$result = '{ "status":"ok", "message":"1", "akun":['.$GetAllAccountActiva.'],"id":['.$GetCodeActiva.']    }';
		return $result;
	}
}




?>




