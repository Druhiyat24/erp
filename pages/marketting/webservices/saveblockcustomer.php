<?php 
		$data = $_POST;
$data = json_decode($_POST['data']);
//print_r($data);
if($_POST['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->update($data);
print_r($List);
}
else{
	exit;
}
class getListData {
	public function update($data){
		// print_r($data[0]); 
		include __DIR__ .'/../../../include/conn.php';
			for($i=0;$i<count($data);$i++){
				$obj = $data[$i];
				$set_query = "b_mkt = '{$obj->b_mkt}'
							 ,b_inv = '{$obj->b_inv}'
							 ,b_shp = '{$obj->b_shp}'
				
				";
				$where_query = "id_cust = '{$obj->id_sup}'";
				
				$query = "UPDATE tbl_block_cust SET $set_query WHERE $where_query"; 
/* 				echo $query."--";*/
				mysql_query($query); 
			}	
			$result = '{ "status":"ok", "message":"1", "records":"OK"}';
		return $result;
	}
}
?>




