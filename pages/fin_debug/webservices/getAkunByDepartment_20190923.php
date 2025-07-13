<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data["idDepartment"]);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($id){
		include __DIR__ .'/../../../include/conn.php';
		$andWhere = "";
		if($id == "01"){
			$andWhere = " AND  (id_coa >= 50000 AND id_coa <= 59999)";
		}
		else if($id == "02"){
			$andWhere = " AND  (id_coa >= 60000 AND id_coa <= 69999)";
		}
		elseif($id == "03"){
			$andWhere = " AND  (id_coa >= 70000 AND id_coa <= 99999)";
		}else if($id == "Neraca"){
			$andWhere = "AND (substring(id_coa,1,1) IN ('1','2','3','4'))";
		}
		
		
		$q = "SELECT id_coa,nm_coa
				FROM mastercoa WHERE (post_to !='' ) $andWhere  AND fg_active='1' ";
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




