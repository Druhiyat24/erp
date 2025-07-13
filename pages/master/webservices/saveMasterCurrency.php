<?php 
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Save {
	public function Add($data){
		$userUpdate = $_SESSION['username'];
		//print_r($_SESSION);
		include __DIR__ .'/../../../include/conn.php';
		$q = "INSERT INTO masterrate(curr,tanggal,rate,rate_jual,rate_beli,v_idgroup,v_lastupdate,v_codecurr)
				VALUES ('$data->curr','$data->tanggal',
				'$data->rate','$data->ratejual','$data->ratebeli','$data->idgroup','$userUpdate','$data->v_codecurr'
				)
			";	
			$stmt = mysqli_query($conn_li,$q);;
		if(mysqli_error($conn_li)){
			$result = '{ "status":"ok", "message":"'.mysqli_error($conn_li).'","lastDate":"", "records":"" }';
			return $result;
		}	
			$result = '{ "status":"ok", "message":"1", "records":""}';
			return $result;
	}
	public function Edit($data){     
		include __DIR__ .'/../../../include/conn.php';
		$q = " UPDATE masterrate SET 
				   curr = '$data->curr', 
				   rate = '$data->rate',
				   rate_jual = '$data->ratejual',
				   rate_beli = '$data->ratebeli'
				   WHERE v_idgroup = '$data->id'
			";	
			//echo $q;
			$stmt = mysqli_query($conn_li,$q);;
		if(mysqli_error($conn_li)){
			$result = '{ "status":"ok", "message":"'.mysqli_error($conn_li).'","lastDate":"", "records":"" }';
			return $result;
		}			
			
			$result = '{ "status":"ok", "message":"1", "records":""}';
			return $result;
	}

	public function Delete($data){
		include __DIR__ .'/../../../include/conn.php';
		$q = " DELETE FROM masterrate WHERE v_idgroup = $data->id
			";	
			
			$stmt = mysqli_query($conn_li,$q);;
		if(mysqli_error($conn_li)){
			$result = '{ "status":"ok", "message":"'.mysqli_error($conn_li).'","lastDate":"", "records":"" }';
			return $result;
		}					
			$result = '{ "status":"ok", "message":"1", "records":""}';
			return $result;
	}

	}




	//print_r($data);
	
//		

$data = (object)$_POST['data'];
$dataDeletes = (object)$_POST['dataDelete'];
$code = $_POST['code'];
//print_r($data);
if($code == '1'){
/* 	print_r($_POST);
	die(); */
	if(!ISSET($_SESSION['username'])){
		$List = '{ "status":"no", "message":"SESSION HABIS, Silahkan Login Kembali", "records":""}';
		
	}
	else{
		$time = strtotime($data->tanggal);
		
		$explodedate = explode("/",$data->tanggal);
		if(ISSET($explodedate[1])){
			$data->tanggal = $explodedate[2].'-'.$explodedate[1].'-'.$explodedate[0];
			
		}
			$Save = new Save();
			if($data->type=="Add"){
				$data->idgroup =date("Ymdhmssh");
				$explodedateto = explode("/",$data->tanggalto);
				$data->tanggalto = $explodedateto[2].'-'.$explodedateto[1].'-'.$explodedateto[0];
				//echo $data->tanggalto;
				$from = new DateTime($data->tanggal);
				$to = new DateTime($data->tanggalto);	
				$selisih = $from->diff($to)->format("%a");
/* 				echo "SELISIH:$selisih";
				die(); */
				//'$data->rate','$data->ratejual','$data->ratebeli'
				
				if($selisih == '6' || $selisih == '7'){
					$data->v_codecurr = 'PAJAK';
					$data->ratejual = $data->rate;
					$data->ratebeli = $data->rate;
				}else if($selisih == '0'){
					$data->v_codecurr = 'HARIAN';
				}else if($selisih == '89' || $selisih == '90'){
					
					$data->v_codecurr = 'COSTING3';
					$data->ratejual = $data->rate;
					$data->ratebeli = $data->rate;
				}else if($selisih == '179' ||  $selisih == '180'){
					$data->v_codecurr = 'COSTING6';
					$data->ratejual = $data->rate;
					$data->ratebeli = $data->rate;					
					
				}else if($selisih == '239' || $selisih == '240'){
					$data->v_codecurr = 'COSTING8';
					$data->ratejual = $data->rate;
					$data->ratebeli = $data->rate;					
					
				}else if($selisih == '364' || $selisih == '365'|| $selisih == '366'){
					$data->v_codecurr = 'COSTING12';
					$data->ratejual = $data->rate;
					$data->ratebeli = $data->rate;					
				}
				if(strtotime($data->tanggal) > strtotime($data->tanggalto)){
					$result = '{ "status":"ok", "message":"2", "records":""}';
					print_r($result);	
					exit;
				}  

				while (strtotime($data->tanggal) <= strtotime($data->tanggalto)) {
				$List = $Save->Add($data);
				$data->tanggal = date ("Y-m-d", strtotime("+1 day", strtotime($data->tanggal)));//looping tambah 1 date
			//	print_r($data->tanggal."<br/>");
				
				}	
			}
			else if($data->type=="Edit"){
				$List = $Save->Edit($data);
			}
			else if($data->type=="Delete"){
				$List = $Save->Delete($dataDeletes);
			}				
			}
	print_r($List);
}
else{
	exit;
}



?>




