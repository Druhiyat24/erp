<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data);
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
	public function get($paramss){
		require_once "../../forms/journal_interface.php";
		include __DIR__ .'/../../../include/conn.php';
		$q = GetQuery_Detail_Payment('SERVICES',$paramss);
		
		$MyList = $this->CompileQuery($q,'SELECT');
		if($MyList == '0'){
			$result = '{ "status":"ok", "message":"2", "records":"0"}';
		}
		else{
			$EXP = explode("__ERR",$MyList);
			if($EXP[1]){
				$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
			}
			else{
				$outp = '';
				while($row = mysqli_fetch_array($MyList)){
					if ($outp != "") {$outp .= ",";}
					$outp .= '{"reference":"'.rawurlencode($row['v_nojournal']).'",';
					$outp .= '"no_payment":"'.rawurlencode($row["no_payment"]). '",'; 	
					$outp .= '"amount":"'.rawurlencode($row["bpb_amount"]).'"}'; 	
				} 		
				$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';	
			}		
		}
		return $result;
	}
}




?>




