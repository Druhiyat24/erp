<?php 
$data = (object)$_POST['data'];
//print_r($data);

if($data->idForm == '1'){
	//echo "123";
$Save = new Save();
$result = $Save->Insert($data);	
print_r($result);
	
}
if($data->idForm == '2'){
	//echo "123";
$Save = new Save();
$result = $Save->Update($data);	
print_r($result);	
	
}


//print_r($List);


class Save {

	 protected function generateDate($date,$month,$years){
		$month = intval($month) + 1;
		$month = sprintf("%02d", $month);
		$dates =$years.'-'.$month.'-'.$date;	
		return $dates;
	}
	public function Insert($data){
		include_once __DIR__ .'/../../../include/conn.php';
		//echo "ID :$id";
		$myDate = date('Y-m-d H:s:i');
		$createdate = $this->generateDate($data->day,$data->month,$data->years);
		$q = "INSERT INTO hr_keterangankerja 
			(
				v_nik,
				v_nik2,
				d_create,
				d_insert,
				v_lamakerja,
				v_reason
			) values('$data->nik','$data->nik2','$createdate','$myDate','$data->lamabekerja','$data->reason')";
			//echo $q;
		$stmt = mysql_query($q);

			$result = '{ "status":"ok", "message":"1", "records":" " }';
		return $result;
	}
	
	
	public function Update($data){
		include_once __DIR__ .'/../../../include/conn.php';
		//echo "ID :$id";
		$myDate = date('Y-m-d H:s:i');
		$createdate = $this->generateDate($data->day,$data->month,$data->years);
		//echo $headerdate;
		$q = "UPDATE hr_keterangankerja SET
				v_nik 			='$data->nik',
				v_nik2 			='$data->nik2',
				v_reason 		='$data->reason',
				v_lamakerja 	='$data->lamabekerja',
				d_create 		= '$createdate',
				d_insert 		= '$myDate'
					WHERE n_id = '$data->id'
			";
			//echo $q;
		$stmt = mysql_query($q);
			$result = '{ "status":"ok", "message":"1", "records":" " }';
		return $result;
	}	
	
	
}
?>
