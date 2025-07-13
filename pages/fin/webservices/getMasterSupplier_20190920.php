<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data['codesup']);
if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get();
print_r($List);
}
else if($data['code'] == '2' ){
	$getListData = new getListData();
$List = $getListData->getNamaSup($data['codesup']);
print_r($List);
}
else if($data['code'] == '3' ){
	//print_r("ABC");
	$getListData = new getListData();
$List = $getListData->getDefaultCode($data['idJournals']);
print_r($List);
}
else{
	exit;
}
class getListData {
	public function get(){
		include __DIR__ .'/../../../include/conn.php';
		$q = "select id_supplier,supplier,supplier_code from mastersupplier WHERE supplier_code != ''";
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		$td = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['id_supplier']).'",';
			$outp .= '"code":"'. rawurlencode($row["supplier_code"]). '",'; 
			$outp .= '"nama":"'. rawurlencode($row["supplier"]). '"}'; 	
		}
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}
	
	
	public function getNamaSup($codesup){
		include __DIR__ .'/../../../include/conn.php';
		$q = "select id_supplier,supplier,supplier_code from mastersupplier WHERE supplier_code= '$codesup'";
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		$td = '';
		//print_r($q);
		while($row = mysql_fetch_array($stmt)){
			
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['id_supplier']).'",';
			$outp .= '"code":"'. rawurlencode($row["supplier_code"]). '",'; 
			$outp .= '"nama":"'. rawurlencode($row["supplier"]). '"}'; 	
		}
		$records['id'] = $id;		
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}		
	
	public function getDefaultCode($idJournal){
		//print_r("ABC");
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT A.v_idjournal,A.v_idkonsumen,B.supplier FROM fin_prosescashbank A LEFT JOIN(select supplier_code,id_supplier,supplier from mastersupplier )B ON A.v_idkonsumen = B.supplier_code WHERE v_idjournal = '$idJournal'";
		//print_r($q);
		$stmt = mysql_query($q);		 
		$numberjournal = array();
		$id = array();
		$outp = '';
		$td = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"code":"'.rawurlencode($row['v_idkonsumen']).'",'; 	
			$outp .= '"nama":"'.rawurlencode($row['supplier']).'"}';
		}
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}			
	
}




?>




