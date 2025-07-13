<?php 
session_start();
$data = $_POST;
$data = (object)$_POST['data'];
$data->v_user_insert =$_SESSION['username'];
$data->v_user_update =$_SESSION['username'];
$data->d_update = date('Y-m-d H:m:s');

if($_POST['code'] == '1' ){
$Save = new Save();
if($data->crud == 'INSERT'){ 
	$result = $Save->Insert($data);
	//die();
}else if($data->crud == 'UPDATE'){ 

	$result = $Save->Update($data);

}else if($data->crud == 'DELETE'){ 

	$result = $Save->Deletes($data);
}
print_r($result);
}
else{
	exit;
}
class Save {
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
	
	
	public function Insert($data){
			$q ="insert into masterdeathstock 
				(				
					 c_type				
					,v_klasifikasi
					,v_c_bahan_baku				
					,v_n_bahan_baku				
					,v_color				
					,v_ukuran				
					,n_qty				
					,v_unit				
					,v_no_rak				
					,v_keterangan				
					,v_user_insert							
				)
					values 
				(
					'$data->c_type'
					,'$data->v_klasifikasi'
					,'$data->v_c_bahan_baku'
					,'$data->v_n_bahan_baku'
					,'$data->v_color'
					,'$data->v_ukuran'
					,'$data->n_qty'
					,'$data->v_unit'
					,'$data->v_no_rak'
					,'$data->v_keterangan'
					,'$data->v_user_insert'

				)	
					";
		$MyList = $this->CompileQuery($q,'CRUD');
		if($MyList == '0'){
			$result = '{ "status":"ok", "message":"2", "records":"0"}';
		}
		else{
			
			$EXP = explode("__ERR",$MyList);
			if(ISSET($EXP[1])){
				$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
			}
			else{
				$result = '{ "status":"ok", "message":"1", "records":"0"}';
			}		
		}
		
		return $result;
	}
	
	public function Update($data){
			$q ="UPDATE masterdeathstock 
					SET			
					 c_type         ='$data->c_type'
					,v_klasifikasi  ='$data->v_klasifikasi'
					,v_c_bahan_baku	='$data->v_c_bahan_baku'		
					,v_n_bahan_baku	='$data->v_n_bahan_baku'		
					,v_color		='$data->v_color'	
					,v_ukuran		='$data->v_ukuran'	
					,n_qty			='$data->n_qty'
					,v_unit			='$data->v_unit'
					,v_no_rak		='$data->v_no_rak'	
					,v_keterangan	='$data->v_keterangan'		
					,d_update       ='$data->d_update'	
					,v_user_update  ='$data->v_user_update'
				WHERE n_id = $data->n_id";
		$MyList = $this->CompileQuery($q,'CRUD');
		if($MyList == '0'){
			$result = '{ "status":"ok", "message":"2", "records":"0"}';
		}
		else{
			$EXP = explode("__ERR",$MyList);
			if(ISSET($EXP[1])){
				$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
			}
			else{
				$result = '{ "status":"ok", "message":"1", "records":"0"}';
			}		
		}
		return $result;
	}	
	public function Deletes($data){
			$q ="DELETE FROM masterdeathstock 
				WHERE n_id = '$data->n_id'";
		$MyList = $this->CompileQuery($q,'CRUD');
		if($MyList == '0'){
			$result = '{ "status":"ok", "message":"2", "records":"0"}';
		}
		else{
			$EXP = explode("__ERR",$MyList);
			if(ISSET($EXP[1])){
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




