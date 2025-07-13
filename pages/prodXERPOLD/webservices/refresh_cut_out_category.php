<?php
session_start();
//header('Content-Type: application/json');
/*  IF(!ISSET($_SESSION['username'])){
	$respon  = "503";
	$message = "SESSION TIDAK ADA/HABIS SILAHKAN LOGIN KEMBALI";
	$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
	return $result;
}  */

/*
format =  1 : INSERT
format =  2 : UPDATE
format =  3 : DELETE
*/
$data = (object)$_POST;
$code = $_POST['code'];/* 
print_r($data); */
if($code == '1'){
	$Proses = new Proses();
	$Proses->GetListData($_SESSION['username']);
}
else{
	exit;
}

class Proses {/* 
/* 	 require_once "conn.php"; 
	public $proses_sql =$conn; */

/*  public function __construct($a, $b){
	/*  include "conn.php";  
		$connect = $conn; */
 
    public function result($res)
    {
        $rows = array();
        if(!$res){
            return $rows;
        }
        while($row = mysqli_fetch_object($res)){
            $rows[] = $res;
        }
        return $rows;
    }
public function connect(){
	include __DIR__ .'/../../../include/conn.php';
	return $conn_li;
	
	
}
	public function GetListData($username){
		$connect = $this->connect();
		$sql="DELETE FROM prod_cut_out_category WHERE is_save='N' AND  username ='{$username}'";
				
	
		$result = $connect->query($sql);
		if(!$connect->query($sql)){
			$message = "Error :".$connect->error;
			$respon  = "500";
			//$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
			//return result;			
			
		}else{
			$message = "SUKSES!";
			$respon  = "200";			
			  $result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
			//$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
			//print_r($result);
			//return result;			
		};
		$result = '{ "respon":"200", "message":"1", "records":"-"}';
		print_r($result);		
	}
}


?>