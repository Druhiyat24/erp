<?php 
$data = $_POST;

if($data['code'] == '1' ){
	$Periode = new Periode();
$List = $Periode->getDetail1($data['code']);
print_r($List);
}
else{
	exit;
}



class Periode {
	public function getDetail1($code){
		//include __DIR__ .'/../../../include/conn.php';
		$mydate = date('m/Y');
		$keyDate = intval(date('m'));
		$periodeDate = array();
		$years = date('Y');
		for($x=1;$x<=25;$x++){
			//echo "X=$x||";
			
			//$tmpDate = $tmpDate.'/'.date('Y');
			if($keyDate > 12){
				$keyDate = 1;
				$years = EXPLODE("-",date('Y-m-d', strtotime('+1 years', strtotime($years))));
				$years = $years[0];
				$tmpDate = sprintf("%02d", $keyDate );
				$tmpDate = $tmpDate.'/'.$years;
			}else{
				$tmpDate = sprintf("%02d", $keyDate );
				$tmpDate = $tmpDate.'/'.$years;			
			}
			$keyDate = $keyDate+1;
		
			array_push($periodeDate,$tmpDate);

		}
		
		
		$records[] 				= array();
		$records['periodeDate'] = $periodeDate;	
			$result = '{ "status":"ok", "message":"1", "records":'.json_encode($records).'}';
		return $result;
	}
	

}




?>




