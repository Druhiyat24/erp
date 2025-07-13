<?php 
session_start();
include "FunctionPrivillages.php";
//print_r($_POST);
if($_POST['typeidcoa'] == 'CASH'){
$Privillages = new Privillages();
$andwhere_nya = $Privillages->getListCoaCash($_SESSION['username']);
//echo "AND:$andwhere_nya";
}
else{
 $andwhere_nya = "AND id_coa >= 11001 AND id_coa <= 11999";	
}
//print_r($_SESSION);
		$data = $_POST;
//$data = (object)$_POST['data'];
//if($data['code'] == '1' ){
$getListData = new getListData();
$List = $getListData->get($andwhere_nya);
print_r($List); 
//}
//else{
//	exit;
//}
class getListData {
	public function get($andwhere){
		//	echo $andwhere;
		if($andwhere == ''){
			$outp = '';	
		}else{
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
		}
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}
?>




