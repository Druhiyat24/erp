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
	
	public function headListPayment($id_rekap){
		$q = "SELECT v_list_src,v_listcode FROM fin_payment_detail WHERE v_listcode = '$id_rekap' LIMIT 1
		"; 

		$MyList = $this->CompileQuery($q,'SELECT');
		if($MyList == '0'){
			return 'NA';
		}
		else{
			$EXP = explode("__ERR",$MyList);
			if($EXP[1]){
				$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
			}
			else{
				$outp = '';
				while($row = mysqli_fetch_array($MyList)){
					$head_list = $row['v_list_src'];
					

				} 		
				return $head_list;	
			}		
		}	

	
	}
	
	public function get($paramss){
		$list_code = $paramss['v_list_code'];
		if($paramss['source'] == "ADD" ){
		$q = "SELECT PD.v_list_src,PD.v_listcode,PD.n_amount,sisa,total,sisa nilai_input FROM fin_payment_detail PD
				LEFT JOIN (SELECT v_list_src, (n_total_amount - n_amount)sisa,n_total_amount total FROM fin_payment_header)
				PH ON PD.v_list_src = PH.v_list_src
				WHERE PD.v_listcode = '$list_code'
		"; }
		else{
			//check_head list payment
			$head_payment = $this->headListPayment($paramss['v_list_code']);


		$q = "SELECT PD.v_list_src,PD.v_listcode,(PH.n_total_amount - SUM(PD.n_amount))sisa,sisa,sisa sisa_new,PH.n_total_amount total
				,(SELECT n_amount FROM fin_payment_detail WHERE v_listcode = '$list_code' LIMIT 1)nilai_input

		FROM fin_payment_detail PD
					LEFT JOIN (SELECT v_list_src, (n_total_amount - n_amount)sisa,n_total_amount  FROM fin_payment_header)
					PH ON PD.v_list_src = PH.v_list_src
					WHERE PD.v_list_src = '$head_payment' AND PD.v_listcode != '$list_code' GROUP BY v_list_src
		";			

		}
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
					$outp .= '{"v_listcode":"'.rawurlencode($row['v_listcode']).'",';
					$outp .= '"v_list_src":"'.rawurlencode($row["v_list_src"]). '",'; 	
					$outp .= '"sisa":"'.rawurlencode($row["sisa"]). '",'; 
					$outp .= '"nilai_input":"'.rawurlencode($row["nilai_input"]). '",'; 
					$outp .= '"total":"'.rawurlencode($row["total"]).'"}'; 	
				} 		
				$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';	
			}		
		}
		return $result;
	}
}




?>




