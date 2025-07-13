<?php

// $data = (object)$_POST;
$code = $_POST['code'];
$id_url = $_POST['id'];

//print_r($data);
if($code == '1'){
	$Proses = new Proses();
	$Proses->GetListData($id_url);
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
 
    public function result($res){
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
	
	
	public function GetListData($id_url){
		$connect = $this->connect();
		$sql="SELECT 
				cn.id_cut_number,
				cn.id_cost,
				cn.no_cut_number
				-- CONCAT(ac.kpno, ' | ', cn.color) AS ws
			FROM prod_cut_number AS cn
			-- INNER JOIN act_costing AS ac ON ac.id = cn.id_cost
			WHERE cn.id_cut_number = '{$id_url}'
		";
		// echo($sql);die();
	
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
						$outp .= '{"id":"'.rawurlencode($row['id_cut_number']).'",';
						$outp .= '"cost":"'.rawurlencode($row["id_cost"]).'",';
						$outp .= '"no_cut":"'.rawurlencode($row["no_cut_number"]).'"}';
					}	
				}else{
						if ($outp != "") {$outp .= ",";}
						$outp .= '{"id":"'.rawurlencode("").'",';
						$outp .= '"cost":"'.rawurlencode("").'",';
						$outp .= '"no_cut":"'.rawurlencode("").'"}';
					
				}
			//$result = '{ "respon":"'.$respon.'", "message":"'.$message.'", "records":"0"}';
			//print_r($result);
			//return result;			
		};
		$result = '{ "responds": "200", "message": "Oke", "records": ['.$outp.'] }';
		print_r($result);		
	}

}

?>