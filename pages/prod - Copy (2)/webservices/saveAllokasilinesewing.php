<?php 
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class save {
	public function Add($data){
		include __DIR__ .'/../../../include/conn.php';
		//print_r($data);
		//die();
		for($i=0;$i< count($data->detail);$i++){

			$id = $data->detail[$i]['id'];
			$id_cost = $data->detail[$i]['id_cost'];
			$id_so = $data->detail[$i]['id_so'];
			$id_line = $data->detail[$i]['id_line'];
			// $create_date = $data->detail[$i]['create_date'];
			$data->create_date = date("Y-m-d", strtotime($data->create_date));
			$data->created_by = date("Y-m-d", strtotime($data->created_by));
				//echo $tmp_tgl_input;
				//die();
			
		$q = "INSERT INTO prod_allokasi_line_sewing(id,id_cost,id_so,id_line,create_date,created_by) 
				VALUES('$id','$data->id_cost','$id_so','$_SESSION[username]','$created_date','$created_by')";
			$stmt = mysql_query($q);
				
		}

					$result = '{ "status":"ok", "message":"1", "records":""}';
			return $result;

	}
// 	public function Edit($data){
// 		include __DIR__ .'/../../../include/conn.php';
// 		$q = " UPDATE fin_mscurrency SET 
// 				   curr = '$data->curr', 
// 				   rate = '$data->rate',
// 				   rate_jual = '$data->ratejual',
// 				   rate_beli = '$data->ratebeli'
// 				   WHERE v_idgroup = '$data->id'
// 			";	
// 			//echo $q;
// 			$stmt = mysql_query($q);
// 			$result = '{ "status":"ok", "message":"1", "records":""}';
// 			return $result;
// 	}

// 	public function Delete($data){
// 		include __DIR__ .'/../../../include/conn.php';
// 		$q = " DELETE FROM fin_mscurrency WHERE v_idgroup = $data->id
// 			";	
// 			//echo $q;
// 			$stmt = mysql_query($q);
// 			$result = '{ "status":"ok", "message":"1", "records":""}';
// 			return $result;
// 	}

// 	}




// 	//print_r($data);
	
// //		

// $data = (object)$_POST['data'];
// if($_POST['code'] == '1'){
// 	$Save = new Save();
// 		$List = $Save->Add($data);
		
// 	print_r($List);	

// }
// else{
// 	exit;
// }



?>




