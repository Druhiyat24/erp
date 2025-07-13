<?php
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
// $data = (object)$_POST;
$cost= $_POST['id_cost'];
$color = $_POST['clr'];
$url = $_POST['id'];

$code = $_POST['code']; 
// print_r($code);die();
if($code == '1'){
	$Proses = new Proses();
	$Proses->GetListData($cost,$color,$url);
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
	
	public function GetListData($cost,$color,$url){
		$connect = $this->connect();
		$sql = "SELECT 
				med.id_panel,
				mp.nama_panel
			FROM prod_mark_entry_detail AS med
			INNER JOIN masterpanel AS mp ON mp.id = med.id_panel
			WHERE med.id_cost = '{$cost}' AND med.color = '{$color}' 
			GROUP BY med.id_panel
		";
		// echo $sql;
		// print_r($sql);die();
				
	
		$result = $connect->query($sql);
		if(!$connect->query($sql)){
			$message = "Error :".$connect->error;
			$respon  = "500";
			//$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
			//return result;		
			
		}else{
			$message = "SUKSES!";
			$respon  = "200";			
			$outp = "";
				if($result->num_rows > 0){
					while($row = $result->fetch_array()){
						
						if ($outp != "") {$outp .= ",";}
						$outp .= '{"id":"'.rawurlencode($row['id_panel']).'",';
						$outp .= '"panel":"'. rawurlencode($row["nama_panel"]). '"}'; 
					}	
				}else{
						if ($outp != "") {$outp .= ",";}
						$outp .= '{"id":"'.rawurlencode("").'",';
						$outp .= '"panel":"'. rawurlencode(""). '"}';  
					
				}
			//$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
			//print_r($result);
			//return result;			
		};
		$result = '{ "respon":"200", "message":"1", "records":['.$outp.']}';
		print_r($result);		
	}
}


?>