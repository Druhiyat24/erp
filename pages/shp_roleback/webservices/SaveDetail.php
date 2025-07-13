<?php 
		//$data = $_POST;
$data =$_POST['data'];


//if($data['code'] == '1' ){
	$Save = new Save();
///	if($_POST['crud'] == "Add" ){
//		$List = $Save->Insert($data);
//	}
//	else if($_POST['crud'] == "Edit" ){

		$List = $Save->Update($data);
		
//	}
print_r($List);
//}
//else{
//	exit;
//}
class Save {
	public function Insert($data){
		include __DIR__ .'/../../../include/conn.php';
		$q = "INSERT INTO invoice_detail (id_inv,v_noso,qty,unit,price) VALUES('$data->MyidInv','$data->MyNoSo','$data->MyQty','$data->MyUnit','$data->MyPrice')";
		//echo $q;
		//die();
		$stmt = mysql_query($q);		
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}	
	public function Update($data){
		include __DIR__ .'/../../../include/conn.php';
		$cu = count($data);
		for($i=0;$i<$cu;$i++){
		$q = "UPDATE invoice_detail SET 
					lot = '".$data[$i]['lot']."',
					carton = '".$data[$i]['carton']."',
					qty = '".$data[$i]['i_qty']."'
					WHERE 
					id	= '".$data[$i]['id']."' ";
					
		$stmt = mysql_query($q);					
			



			
		}

	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}
}




?>




