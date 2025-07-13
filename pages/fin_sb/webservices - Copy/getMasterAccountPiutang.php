<?php
$data = $_POST;
if($data['code'] == '1' ){
	    $Supplier = new Supplier();
$List = $Supplier->get();
print_r($List);
}
else{
	exit;
}
class Supplier {
	public function get(){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT Id_Supplier,Supplier FROM mastersupplier";
		$stmt = mysql_query($q);		
		$Id_Supplier = array();
		$Supplier = array();
		while($row = mysql_fetch_array($stmt)){
			array_push($Id_Supplier,$row['Id_Supplier']);
			array_push($Supplier,$row['Supplier']);
		}	
		$records[] 				= array();
		$records['Id_Supplier'] = $Id_Supplier;
		$records['Supplier']    = $Supplier;		
		$result = '{ "status":"ok", "message":"1", "records":'.json_encode($records).'}';
		return $result;
	}
}




?>




