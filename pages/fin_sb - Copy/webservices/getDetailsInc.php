<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data['id'],$data['row']);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($id,$row){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT id_coa,SUBSTRING(id_coa,1,1) test FROM fin_journal_d
				 WHERE id_journal ='$id'";
				// echo $q;
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '[{"id":"'.rawurlencode($row['id_coa']).'",';
			$outp .= '"type":"'. rawurlencode($row["test"]). '"}]'; 	
			


		}
	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}




?>




