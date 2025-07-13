<?php 
		$data = $_POST;
	$type_rate = $_POST['par_k_tipe'];
	$Proses = new Proses();

		$last_date = $Proses -> get_last_tanggal($type_rate);
		//echo $check_range;
			$result = '{ "status":"ok", "message":"0", "last_date": "'.$last_date.'" }';
			print_r($result);

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
	public function get_last_tanggal($type_rates){
			$connect 	= $this->connect();
			if($type_rates == 'HARIAN'){
				$like = "LIKE '%HARIAN%'";
			}else if($type_rates == 'PAJAK'){
				$like = "LIKE '%PAJAK%'";
			}else{
				$like = "LIKE '%COSTING%' OR v_codecurr LIKE '%X%'";
			}
			$sql="SELECT (tanggal + INTERVAL 1 DAY)last_date FROM masterrate WHERE v_codecurr {$like} ORDER BY tanggal DESC LIMIT 1";
				
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
					$stmt = mysql_query($sql);
					//echo "$tgl_from - ";
						if($result->num_rows > 0){
							
							while($row = mysql_fetch_array($stmt)){
								$row['last_date'] = date('d/m/Y', strtotime($row['last_date']));
								return $row['last_date'];
								
								
								
							}
							

								
				}else{
							return date('d/m/Y');
				}
			}	

		

		//$result = '{ "respon":"200", "message":"1", "records":['.$outp.']}';
	//	print_r($result);		

}
}
?>




