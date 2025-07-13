<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){

	$getListData = new getListData();
$List = $getListData->get($_POST["id_inhouse_subcon"]);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($id_inhouse){
	
		if ($id_inhouse=="N")
		{ $sql = "select id_supplier isi,supplier tampil from 
			mastersupplier where area='F'";
		}
		else
		{ $sql = "select id_supplier isi,supplier tampil from 
			mastersupplier where area='L'";
		}		
		
		
		include __DIR__ .'/../../../include/conn.php';
		$q = $sql; 
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['isi']).'",';
			$outp .= '"nama":"'. rawurlencode($row["tampil"]). '"}'; 	
	
		}
	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}




?>




