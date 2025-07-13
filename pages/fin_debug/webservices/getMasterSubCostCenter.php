<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data['id']);
print_r($List);
}
//else{
//	exit;
//}
class getListData {
	public function get($id){
		//print_r($id);
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT subdept.* FROM (
SELECT id_cost_sub_dept
		,nm_cost_sub_dept
		,substring(id_cost_sub_dept,'1','2') iddep
		FROM mastercostsubdept
		) subdept WHERE subdept.iddep = '$id'";
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '[{"id":"'.rawurlencode($row['id_cost_sub_dept']).'",';
			$outp .= '"nama":"'. rawurlencode($row["nm_cost_sub_dept"]). '"}]'; 	
			


		}
	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}




?>




