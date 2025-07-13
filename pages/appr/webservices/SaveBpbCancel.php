<?php 
session_start();
include __DIR__ .'/../../forms/journal_interface.php';
include __DIR__ .'/../../forms/fungsi.php';
		$data = (object)$_POST;
//$data = (object)$_POST['data'];
//print_r($data);
if($data->code == '1' ){
	$getListData = new getListData();
	$date = date('Y-m-d H:m:s');
	if(!ISSET($_SESSION['username'])){
		$List = '{ "status":"no", "message":"SESSION HABIS, Silahkan Login Kembali", "records":""}';
	
	}else{
		$bpb_history = $getListData->get_detail_current_bpb($data->no_bpb,$_SESSION['username'],$date);
		$List = $getListData->Cancel($bpb_history);
		$del_tem  = $getListData->Del_Temp($bpb_history);
		$insert_reverse = insert_bpb_reverse(trim(preg_replace('/\s+/', ' ', $data->no_bpb)));

	}	
//$List = $getListData->get($data);
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


	public function Del_Temp($bpb_history){
		$count = count($bpb_history);
		$user_cancel = $bpb_history[0]['user_cancel'];

		$d_cancel = $bpb_history[0]['d_cancel'];
		$bpbno_int = TRIM($bpb_history[0]['bpbno_int']);
/* 		print_r($bpb_history);
		die(); */
		$bpbno = $bpb_history[0]['bpbno'];
		$id_jo = $bpb_history[0]['id_jo'];
		$q = "DELETE FROM bpb_roll WHERE id_h IN (SELECT id FROM bpb_roll_h WHERE bpbno = '{$bpbno}')";
			$MyList = $this->CompileQuery($q,'CRUD');
		$q_h = "DELETE FROM bpb_roll_h WHERE bpbno = '{$bpbno}'";
			$MyList = $this->CompileQuery($q_h,'CRUD');		
			//echo $q_h;
		if($MyList == '0'){
			$result = '{ "status":"ok", "message":"2", "records":"0"}';
		}
		else{
			    if (!is_object($MyList)) {
					//$EXP = explode("__ERRTRUE",$MyList);
					if(ISSET($EXP[1])){
						$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
					}
					$result = '{ "status":"ok", "message":"1", "records":"0"}';
				}
			else{
				$result = '{ "status":"ok", "message":"1", "records":"0"}';
			}		
		}		
		
		//print_r($result);
		
		

		return $result;
	}	

	
	public function Cancel($bpb_history){
		$count = count($bpb_history);
		$user_cancel = $bpb_history[0]['user_cancel'];

		$d_cancel = $bpb_history[0]['d_cancel'];
		$bpbno_int = TRIM($bpb_history[0]['bpbno_int']);
		$q = "UPDATE bpb SET 
				confirm = 'N'
				,is_cancel = 'Y'
				,confirm_by = NULL
				,confirm_date = NULL
				,user_cancel = '$user_cancel'
				,d_cancel = '$d_cancel'
				,n_cancel = (n_cancel + 1)
				WHERE trim(bpbno_int) = '$bpbno_int'
		";
		
		$MyList = $this->CompileQuery($q,'CRUD');
		if($MyList == '0'){
			$result = '{ "status":"ok", "message":"2", "records":"0"}';
		}
		else{
			    if (!is_object($MyList)) {
					//$EXP = explode("__ERRTRUE",$MyList);
					if(ISSET($EXP[1])){
						$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
					}
				}
			else{
				$message_cancel = "Cancel OK";
			}		
		}
		
		$q = "INSERT INTO bpb_history_cancel(n_id_bpb,bpbno_int,id_item,qty,price,last_confirm,d_last_confirm,d_cancel,user_cancel) VALUES";
		$detail = array();
		$x=count($bpb_history) - 1;
		$y = 0;
		foreach($bpb_history as $_history){
			$q .="('{$_history['id_bpb']}'
					 ,'{$_history['bpbno_int']}'
					 ,'{$_history['id_item']}'
					 ,'{$_history['qty']}'
					 ,'{$_history['price']}'
					 ,'{$_history['last_confirm']}'
					  ,'{$_history['d_last_confirm']}'
					 ,'{$_history['d_cancel']}'
					 ,'{$_history['user_cancel']}'
			
			)";
			if($y == $x){
				$q .=" ";
			}
			else{
				$q .=",";
			}			
			
			
			$y++;
		}
	
		$MyList = $this->CompileQuery($q,'CRUD');
		if($MyList == '0'){
			$result = '{ "status":"ok", "message":"2", "records":"0"}';
		}
		else{
			    if (!is_object($MyList)) {
					$EXP = explode("__ERRTRUE",$MyList);
				if(ISSET($EXP[1])){
						$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
					}
					$message_records = "Record History OK";
				}
			else{
				$message_records = "Record History OK";
			}		
			$result = '{ "status":"ok","records":"0","message":"1","message_cancel" : "'.$message_records.'","message_records" : "'.$message_records.'"}';
		}		
		
		//print_r($result);
		
		

		return $result;
	}	
	
	
	public function get_detail_current_bpb($no_bpb,$user_cancel,$date){
		$history = array();
		$no_bpb =trim($no_bpb);
		$q = "SELECT id,bpbno_int,id_item,qty,price,confirm_by,confirm_date,id_jo,bpbno FROM bpb WHERE trim(bpbno_int) = '$no_bpb' GROUP BY id";
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
				while($row = mysqli_fetch_array($MyList)){
					$data = array(
						'id_bpb' => $row['id'],
						'bpbno_int' => TRIM($row['bpbno_int']),
						'id_item' => $row['id_item'],
						'qty' => $row['qty'],
						'price' => $row['price'],
						'last_confirm' => $row['confirm_by'],
						'd_last_confirm' => $row['confirm_date'],
						'bpbno' => $row['bpbno'],
						'id_jo' => $row['id_jo'],
						'user_cancel' => $user_cancel,
						'd_cancel' => $date,
					
					);					
					array_push($history,$data);
				} 		

				$result = $history;
			}		
		}
		return $result;
	}
	

	
	
	
}




?>




