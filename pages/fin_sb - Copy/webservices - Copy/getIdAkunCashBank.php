<?php 
		$data = $_POST;
$data = (object)$_POST;
//print_r($data);
if($data->code == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data->id);
print_r($List);
}
else{
	exit;
}
class getListData {
	public function get($id){
		
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT v_idjournal,v_idcoa,nm_coa FROM fin_prosescashbank cashbank
		LEFT JOIN( SELECT id_coa,nm_coa FROM mastercoa )coa ON cashbank.v_idcoa = coa.id_coa
		
		WHERE cashbank.v_idjournal = '$id'";
		//echo $q;
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		$td = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '[{"id":"'.rawurlencode($row['v_idcoa']).'"}]'; 
		}
	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}




?>




