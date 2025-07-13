<?php 
		$data = $_POST;
	$xx= str_replace("/","-",$_POST['par_tgl']);
	$x = date('Y-m-d', strtotime($xx));
	$type_rate = $_POST['par_k_tipe'];
	$Proses = new Proses();
	$par_day = $_POST['par_day'];
	if($_POST['par_day'] == '0'){
		$par_day = $_POST['par_day'];
	}else{
		$par_day = $_POST['par_day'] - 1;
	}
/*  print_r($_POST);
die();  */
		$tanggal_to = date('Y-m-d', strtotime("+{$par_day} days", strtotime($x)));
/* 		echo $tanggal_to;
		die(); */
		$check_range = $Proses -> check_range($x,$tanggal_to,$type_rate);
		//echo $check_range;
		if($check_range == '0'){
			$result = '{ "status":"ok", "message":"0", "tgl_to": "--" }';
			print_r($result);
		}else{
			$tanggal_to = date('d/m/Y', strtotime($tanggal_to));
			$result 	= '{ "status":"ok", "message":"1", "tgl_to": "'.$tanggal_to.'" }';		
			$tanggal_to = date('d/m/Y', strtotime($tanggal_to));			
			print_r($result);
		}
class Proses{
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
	public function check_range($tgl_from,$tgl_to,$type_rates){
			$connect 	= $this->connect();
			$from 		= new DateTime($tgl_from);
			$to 		= new DateTime($tgl_to);
			if($type_rates == 'HARIAN'){
				$like = "LIKE '%HARIAN%'";
			}else if($type_rates == 'PAJAK'){
				$like = "LIKE '%PAJAK%'";
			}else{
				$like = "LIKE '%COSTING%' OR v_codecurr LIKE '%X%'";
			}
			while (strtotime($tgl_from) <= strtotime($tgl_to)) {
			$sql="SELECT tanggal,v_codecurr FROM masterrate WHERE tanggal ='{$tgl_from}' AND v_codecurr {$like} ";
				
				//echo $sql;
				
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
					//echo "$tgl_from - ";
						if($result->num_rows > 0){
							$key = 0;
							return $key;
						}else{
							$key = 1; 
						}
					if($tgl_to == $tgl_from){
						return $key;
					}	
								
				};
				$tgl_from = date ("Y-m-d", strtotime("+1 day", strtotime($tgl_from)));
			}	

		

		//$result = '{ "respon":"200", "message":"1", "records":['.$outp.']}';
	//	print_r($result);		
	}
}



?>




