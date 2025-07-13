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
		$q = "SELECT kd_tipe_aktiva,nm_tipe_aktiva,v_metodepenyusutan,n_yearsestimasiumur,n_monthestimasiumur,n_pernyusutanbydate
				FROM masteractivatype ";
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';  
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '[{"kodeaktiva":"'.rawurlencode($row['kd_tipe_aktiva']).'",';
			$outp .= '"kodeaktivacustom":"'. rawurlencode($row["kd_tipe_aktiva"]."001"). '",'; 
			$outp .= '"namaaktiva":"'. rawurlencode($row["nm_tipe_aktiva"]). '"}]'; 	
			


		}
		$records[] 				= array();
		$records['numberjournal'] = $numberjournal;
		$records['id'] = $id;		
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}




?>




