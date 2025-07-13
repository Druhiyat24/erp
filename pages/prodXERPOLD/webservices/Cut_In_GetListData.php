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
$code = $_POST['code'];/* 
print_r($data); */
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
		$sql="SELECT id_cut_in AS id
				,CONCAT(id_act_costing,'_',id_jo,'_',request_no) AS ws
				,dateinput AS dt	
				FROM prod_cut_in WHERE id_cut_in = '{$id}'
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
						$outp .= '{"id":"'.rawurlencode($row['id']).'",';
						$outp .= '"ws":"'. rawurlencode($row["ws"]). '",';
						$outp .= '"dt":"'. rawurlencode($row["dt"]). '"}'; 
					}	
				}else{
						if ($outp != "") {$outp .= ",";}
						$outp .= '{"id":"'.rawurlencode("").'",';
						$outp .= '"ws":"'.rawurlencode(""). '",';
						$outp .= '"dt":"'.rawurlencode(""). '"}'; 
					
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