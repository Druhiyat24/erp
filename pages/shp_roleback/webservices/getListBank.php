<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get();
print_r($List);
}
else{
	exit;
}
class getListData {
	
	function CompileQuery($query,$mode){
		
		include __DIR__ .'/../../../include/conn.php';
		$stmt = mysqli_query($conn_li,$query);	
		if(mysqli_error($conn_li)){
		
			$result = mysqli_error($conn_li)."__ERRTRUE";
			return $result;
		}	
		else{
			if($mode == "CRUD"){
				$result = '{ "status":"ok", "message":"1"}';
				return $result;
			}
			else{
			
				if(mysqli_num_rows($stmt) == '0' ){
					$result = '{ "status":"ok", "message":"2"}';
					return '0';
				}
				else{
					
					return $stmt;
				}
			}
		} 
	}	
	public function get(){ 
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT id_coa id,nm_coa tampil FROM mastercoa WHERE id_coa > 11000 AND id_coa < 12000";
		$MyList = $this->CompileQuery($q,'SELECT');
			
		if($MyList == '0'){
			$result = '{ "status":"ok", "message":"2", "records":"0"}';
		}
		else{
			
			    if (!is_object($MyList)) {
					$EXP = explode("__ERRTRUE",$MyList);
					if($EXP[1]){
						$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
					}
				}
			
			else{
					
		$outp = '';
 		while($row = mysqli_fetch_array($MyList)){
//echo "123";
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['id']).'",';
			$outp .= '"tampil":"'.rawurlencode($row["tampil"]). '"}';	
			}		
		}
	$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';	
		return $result;
	}
}


}

?>




