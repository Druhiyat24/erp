<?php 
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Save {
	public function Insert($data){
		include __DIR__ .'/../../../include/conn.php';
		$q = "INSERT INTO fin_status_journal_ap (v_nojournal,v_status,v_notes,v_listcode,n_row_id) VALUES(
					'$data[nokontrabon]'
					,'$data[code_status]'
					,'$data[notes]'
					,'$data[nolist]'
					,'$data[row]'
				)
			";	
			echo "$q";
			$stmt = mysql_query($q);
			$result = '{ "status":"ok", "message":"1", "records":""}';
			return $result;
	}
	public function UPDATE($data){
		include __DIR__ .'/../../../include/conn.php';
		$q = " UPDATE fin_status_journal_ap 
					SET v_notes = '$data[notes]'
					,v_status = '$data[code_status]'
				WHERE v_listcode = '$data[nolist]';
			";
			echo $q;
			$stmt = mysql_query($q);
			$result = '{ "status":"ok", "message":"1", "records":""}';
			return $result;
	}
	public function checkdata($id,$row){
		$count = 0;
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT count(v_nojournal) counts from fin_status_journal_ap WHERE v_nojournal = '$id' AND n_row_id = '$row'
			";	
		
			$stmt = mysql_query($q);
			while($row = mysql_fetch_array($stmt)){
				$count = $row['counts'];

			}
			if(!ISSET($count)){
				$count = 0;
				
			}
			$stmt = mysql_query($q);
			$result = '{ "status":"ok", "message":"1", "records":""}';
			return $count;		
		
		
	}
}
	//print_r($data);
	
//		

$data = (object)$_POST['data'];
$code = $_POST['code'];
//print_r($data);
//die();
if($code == '1'){
	if(!ISSET($_SESSION['username'])){
		$List = '{ "status":"no", "message":"SESSION HABIS, Silahkan Login Kembali", "records":""}';
	}
	else{
		$Save = new Save();
		foreach($data as $row){
			if($row['checkbox'] == '1' ){
					$List = $Save->Update($row);	
				}
			}
		}
	print_r($List);
}
else{
	exit;
}



?>




