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
		$q = "SELECT 
				l.Id_Supplier AS id_line,
				l.Supplier AS nm_line,
				l.area
			FROM mastersupplier AS l WHERE l.area='LINE'
		"; 
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['id_line']).'",';
			$outp .= '"line":"'. rawurlencode($row["nm_line"]). '"}'; 	
	
		}
	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}




?>




