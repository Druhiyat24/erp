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
$data = (object)$_POST;
$code = $_POST['code']; 
// print_r($code);die();
if($code == '1'){
	$Proses = new Proses();
	$Proses->GetListData($data->id);
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
	
	public function GetListData($id){
		$connect = $this->connect();
		$sql = "SELECT 
				ds.id_dc_set,
				ds.id_cost,
				ds.notes,
				ds.no_dc_set
			FROM prod_dc_set AS ds
			WHERE ds.id_dc_set = '{$id}'
		";
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
						$outp .= '{"id":"'.rawurlencode($row['id_dc_set']).'",';
						$outp .= '"ws":"'.rawurlencode($row["id_cost"]).'",';
						$outp .= '"num":"'.rawurlencode($row["no_dc_set"]).'",';
						$outp .= '"notes":"'.rawurlencode($row["notes"]).'"}'; 
					}	
				}else{
						if ($outp != "") {$outp .= ",";}
						$outp .= '{"id":"'.rawurlencode("").'",';
						$outp .= '"ws":"'.rawurlencode("").'",';
						$outp .= '"num":"'.rawurlencode("").'",';
						$outp .= '"notes":"'.rawurlencode("").'"}';  
				}
			//$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
			//print_r($result);
			//return result;			
		}	
		$result = '{ "respond":"200", "message":"1", "records":['.$outp.']}';
		print_r($result);		
	}
}


?>