<?php 
		$data = $_POST;
$data = (object)$_POST;
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
		$q = "SELECT v_idgroup,id,curr,max(tanggal)tos,min(tanggal)froms, rate,rate_jual,rate_beli FROM fin_mscurrency GROUP BY v_idgroup HAVING v_idgroup = $id";
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		$td = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '[{"id":"'.rawurlencode($row['v_idgroup']).'",';
			$outp .= '"tanggal":"'.rawurlencode(date("d/m/Y",strtotime($row["froms"]))). '",'; 
			$outp .= '"tanggalto":"'.rawurlencode(date("d/m/Y",strtotime($row["tos"]))). '",';
			$outp .= '"curr":"'. rawurlencode($row["curr"]). '",'; 
			$outp .= '"rate":"'. rawurlencode($row["rate"]). '",'; 
			$outp .= '"ratejual":"'. rawurlencode($row["rate_jual"]). '",'; 
			$outp .= '"ratebeli":"'. rawurlencode($row["rate_beli"]). '"}]'; 
		}
	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}




?>




