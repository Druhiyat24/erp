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
		$q = "SELECT id_coa,nm_coa
				FROM mastercoa WHERE (post_to !='' ) AND  (id_coa >= 50000 AND id_coa <= 79901)  ";
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '[{"id":"'.rawurlencode($row['id_coa']).'",';
			$outp .= '"nama":"'. rawurlencode($row["nm_coa"]). '"}]'; 	
			


		}
	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}




?>




