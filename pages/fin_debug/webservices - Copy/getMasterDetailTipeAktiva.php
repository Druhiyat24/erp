<?php 
		$data = $_POST;
//$data = (object)$_POST;
//print_r($data);
//if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data['codeaktiva']);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($data){
	//	print_r($data);
		$myExplode = $data->codeaktiva;
		include __DIR__ .'/../../../include/conn.php';
		
		//Check Jumlah Data
		$q = "SELECT ifnull(COUNT(*),0) jlh FROM masteractiva WHERE v_tipeactiva  = '$data'";
		
		$stmt = mysql_query($q);	
		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
				$jlh = intval($row["jlh"]) + 1;
		}			
		$jlh = sprintf('%03d', $jlh);
		$q = "SELECT kd_tipe_aktiva,nm_tipe_aktiva,v_metodepenyusutan,n_yearsestimasiumur,n_monthestimasiumur,n_pernyusutanbydate
				FROM masteractivatype WHERE kd_tipe_aktiva = '$data'";
		$stmt = mysql_query($q);	
		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '[{"kodeaktiva":"'.rawurlencode($row['kd_tipe_aktiva']).'",';
			$outp .= '"kodeaktivacustom":"'. rawurlencode($row['v_metodepenyusutan']). '",'; 
			$outp .= '"years":"'. rawurlencode($row['n_yearsestimasiumur']). '",'; 
			$outp .= '"month":"'. rawurlencode($row['n_monthestimasiumur']). '",'; 
			$outp .= '"kodeaktivacustom":"'. rawurlencode($row['kd_tipe_aktiva'].$jlh). '",'; 
			$outp .= '"namaaktiva":"'. rawurlencode($row["nm_tipe_aktiva"]). '"}]'; 	
			


		}

		$records['id'] = $id;		
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}




?>




