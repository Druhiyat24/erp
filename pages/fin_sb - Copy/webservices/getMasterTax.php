<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data['type_tax']);
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
				print_r($query);
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
	
	
	public function get($tax){
		$where = "000";
		if($tax == "PPN"){
			$where = "PPN";
		}else{
			$where = "PPH";
		}
		$q = "SELECT idtax,category_tax,kriteria,percentage FROM mtax WHERE category_tax = '$where';
		";
		//echo $q;
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
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['idtax']).'",'; 	
			$outp .= '"isi":"'.rawurlencode($row['kriteria']."-".$row['percentage']."%").'"}'; 	
		} 		
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';	
			}		
		}
		
		return $result;
	}
}




?>




