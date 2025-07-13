<?php 
session_start(); //$username

include '../../forms/fungsi.php';
$username =$_SESSION['username'];
if(!ISSET($username)){
	$result = '{ "respon":"'.$respon.'", "message":"SESSION HABIS SILAHKAN LOGIN LAGI!","part" : "Validasi", "records":"0"}';
	exit;
}

/*  print_r($_POST);
		die();  */
//error_reporting(E_ALL);
		$data = $_POST;
		$id_poi = $data['id_poi'];
		$format = 3;
		$getListData = new proses();		
if($format == '3'){
/* 		print_r($_POST);
		die();	 */
	$cancel_poi 	= $getListData->cancel_poi($id_poi);
	$result = '{ "respon":"200", "message":"Item Berhasil di Cancel","part" : "Finish", "records":"0"}';
	print_r($result);
	
}
else{
	exit;
}
//else{
//	exit;
//}
class proses{
	public function connect()
		{
			include __DIR__ .'/../../../include/conn.php';
			return $con_new;
		}
		
	public function json_array($res)
    {
		
        $rows = array();
		if($res->num_rows > 0)
		{
			while($row = $res->fetch_array()){
				$rows[] = $row;
			}
		}

        return $rows;
	}		
		


	
	public function result($res)
    {
        $result = array();
		if($res->num_rows > 0)
		{
			while($row = $res->fetch_array()){
				$result[] = $row;
			}
		}
        return $result;
	}		
	
	public function check_query($connect,$my_result,$function){
				if(!$my_result)
		{
			$message = "Error :".$connect->error;
			$respon  = "500";
			$result = '{ "respon":"'.$respon.'", "message":"'.$message.'","part" : "'.$function.'", "records":"0"}';
			print_r($result);			
			exit;		
		}else{
			return 1;
		}
	}
	public function eksekusi_query($sql,$function){
		$connect = $this->connect();
		$result = $connect->query($sql);
		$check_query = $this->check_query($connect,$result,$function);
		$tmp_array = $this->result($result);
		return $tmp_array;
	}
	public function eksekusi_query_insert_update($sql,$function){
		$connect = $this->connect();
		$result = $connect->query($sql);
		$check_query = $this->check_query($connect,$result,$function);
		return '1';
	}	
	
	

public function flookup_new($fld,$tbl,$criteria)
{	if ($fld!="" AND $tbl!="" AND $criteria!="")
	{	$sql = "Select $fld as namafld from $tbl Where $criteria ";
		$tmp_array = $this->eksekusi_query($sql,"flookup_new");
		$hasil = $tmp_array[0]['namafld'];
		return $hasil;
	}
}
	
	
	

	public function cancel_poi($id_poi){
		$update = "UPDATE po_item_draft 
					SET  cancel				= 	'Y'
						WHERE id = '{$id_poi}'
						";
/* 						echo $update;
						die(); */
		$this->eksekusi_query_insert_update($update,'update_header');				
	}

	
}
?>




