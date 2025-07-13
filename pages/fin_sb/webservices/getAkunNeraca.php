<?php 
session_start();

//print_r($_SESSION);
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

$getListData = new getListData();
$List = $getListData->get($data['typeidcoa']);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($){
			$andwhere = "AND (substring(id_coa,1,1) IN ('1','2','3','4'))";
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT id_coa,nm_coa,post_to,fg_active FROM mastercoa WHERE (post_to !='' ) $andwhere AND fg_active='1' "; 
		//echo "$q";
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




