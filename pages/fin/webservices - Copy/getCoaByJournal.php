<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data['journal']);
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
	
	
	public function get($id){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT distinct(id_coa)id_coa FROM fin_journal_d WHERE id_journal = '$id' AND id_coa IN('10101','10102','10103','11001','11002','11011') AND credit > 0;
		";
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
			$outp .= '{"id_coa":"'.rawurlencode($row['id_coa']).'"}'; 	
		} 		
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';	
			}		
		}
		
		return $result;
	}
}




?>




