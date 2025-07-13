<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//die();
//print_r($data);
if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data['idjo'],$data['nilai_konfirmasi']);
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
				//print_r($query);
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
	
	
	public function get($id,$nilai_konfirmasi){
		//include __DIR__ .'/../../../include/conn.php';
	//	echo "$id";
		//echo "123";
	//	die();
		if($nilai_konfirmasi == '1'){
		$q = "UPDATE jo SET konfirmasi_1 = '1' WHERE id = '$id';
		";	 
//echo $q;		 
		}else if($nilai_konfirmasi == '2' ){
		$q = "UPDATE jo SET konfirmasi_2 = '1' WHERE id = '$id';
		";			
		}else if($nilai_konfirmasi == '3'){
		$q = "UPDATE jo SET konfirmasi_3 = '1' WHERE id = '$id';
		";			
		}	
		$MyList = $this->CompileQuery($q,'CRUD');
		if($MyList == '0'){
			$result = '{ "status":"ok", "message":"2", "records":"0"}';
		}
		else{
			$EXP = explode("__ERR",$MyList);
			if($EXP[1]){
				$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
			}
			else{
				$result = '{ "status":"ok", "message":"1", "records":"0"}';
			}		
		}
		
		return $result;
	}
}
?>




