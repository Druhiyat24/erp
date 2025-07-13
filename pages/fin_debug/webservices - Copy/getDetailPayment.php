<?php 
		$data = $_POST;
		//$arr = $_POST['payment_array'];
//$data = (object)$_POST['data'];
//print_r($data);
if($data['code'] == '1' ){
	$getListData = new getListData();
//	print_r($data);
$List = $getListData->get($_POST['payment_array']);
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
		//print_r($paramss);
		$cnt = count($paramss);
		$trigger = count($paramss) - 1;
		$no_payment_id = '';
		for($i=0;$i<$cnt;$i++){
			if($i == $trigger){
				$no_payment_id .= "'".$paramss[$i]['id']."'";
			}else{
				$no_payment_id .= "'".$paramss[$i]['id']."',";
			}
			
			
		}
		require_once "../../forms/journal_interface.php";
		include __DIR__ .'/../../../include/conn.php';
		$q = GetQuery_Detail_Payment('SERVICES',$no_payment_id);
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
				$amnt = 0;
				while($row = mysqli_fetch_array($MyList)){
					$amnt = $amnt + $row["bpb_amount"];
					if ($outp != "") {$outp .= ",";}
					$outp .= '{"reference":"'.rawurlencode($row['v_nojournal']).'",';
					$outp .= '"no_payment":"'.rawurlencode($row["no_payment"]). '",'; 
					$outp .= '"curr":"'.rawurlencode($row["curr"]). '",';			
					$outp .= '"amount":"'.rawurlencode($row["bpb_amount"]).'"}';
				}
				$result = '{ "status":"ok", "message":"1", "records":['.$outp.'],"total_nilai" : "'.$amnt.'"}';	
			}		
		}
		return $result;
	}
}




?>




