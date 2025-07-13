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
		$q = "SELECT A.v_codeterimakeluar,B.v_description from fin_prosescashbank A LEFT JOIN(SELECT v_id,v_description FROM fin_msTerimaKeluar)B ON A.v_codeterimakeluar = B.v_id WHERE A.v_idjournal = '$id'";
		//echo $q;
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		$td = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['v_codeterimakeluar']).'",'; 
			$outp .= '"nama":"'.rawurlencode($row['v_description']).'"}'; 
		}	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}
?>




