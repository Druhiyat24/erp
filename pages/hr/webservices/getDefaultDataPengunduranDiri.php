<?php 
		$data = (object)($_POST);

//print_r($data);

if($data->code == '1' ){
	$GetDetailNama = new GetDetailNama();
$List = $GetDetailNama->getDetail1($data->id);
print_r($List);
}
else{
	exit;
}
class GetDetailNama {
	
	protected function decodeDate($date){
		$mydate = explode('-',$date);
		return $mydate;
		
	}
	
	
	public function getDetail1($id){
		include __DIR__ .'/../../../include/conn.php';
		$q = " SELECT A.n_id
						,A.v_nik
						,A.d_insert
						,DATE(A.d_create) d_create
						,DATE(A.d_header) d_header
						,B.alamat_karyawan 
						,B.jabatan 
						,B.mulai_kerja
						,B.nama
						FROM hr_pengundurandiri A
					LEFT JOIN (SELECT nik,jabatan,alamat_karyawan,mulai_kerja,nama FROM hr_masteremployee) B
					ON A.v_nik = B.nik
					WHERE A.n_id = $id";
				//echo "$q";
		$stmt = mysql_query($q);
		$outp = '';
		$nik        = array();
		$jabatan    = array();
		$alamat     = array();
		$day        = array();
		$month      = array();
		$years        = array();
		$dayheader  = array();
		$monthheader= array();
		$yearsheader= array();  
		$dayfooter  = array();
		$monthfooter= array();
		$yearsfooter= array();  
		$nama       = array(); 
		$rowCount=count(mysql_num_rows($stmt));
		//echo "COYUNT: $rowCount";
		if($rowCount > 0){
		while($row=mysql_fetch_array($stmt)){
			$date = $this->decodeDate($row['mulai_kerja']);
		//	print_r($date); 
			$dateHeader = $this->decodeDate($row['d_header']);
			//echo $row['d_header'];
			array_push($nik,rawurlencode($row['v_nik']));
			array_push($jabatan,rawurlencode($row['jabatan']));
			array_push($alamat,rawurlencode($row['alamat_karyawan']));	
			array_push($day,rawurlencode(intval($date[2])));
			array_push($month,rawurlencode(intval($date[1])-1));
			array_push($years,rawurlencode(intval($date[0])));
			array_push($dayheader,rawurlencode(intval($dateHeader[2])));
			array_push($monthheader,rawurlencode(intval($dateHeader[1])-1));
			array_push($yearsheader,rawurlencode(intval($dateHeader[0])));
			array_push($dayfooter,rawurlencode(intval($dateHeader[2])));
			array_push($monthfooter,rawurlencode(intval($dateHeader[1])-1));
			array_push($yearsfooter,rawurlencode(intval($dateHeader[0])));	
			array_push($nama,rawurlencode($row['nama']));			
		}			
		}
		else{

			array_push($alamat,rawurlencode(''));	
			array_push($day,rawurlencode(''));
			array_push($month,rawurlencode(''));
			array_push($years,rawurlencode(''));
			array_push($dayheader,rawurlencode(''));
			array_push($monthheader,rawurlencode(''));
			array_push($yearsheader,rawurlencode(''));
			array_push($dayfooter,rawurlencode(''));
			array_push($dayfooter,rawurlencode(''));
			array_push($dayfooter,rawurlencode(''));
			array_push($nama,rawurlencode(''));				
			
			
		}
		$records[]             = array();
		$records['nik']        = $nik;
		$records['jabatan']    = $jabatan;
		$records['alamat']     = $alamat; 
		$records['day']        = $day;
		$records['month']      = $month;
		$records['years']      = $years;
		$records['dayheader']  = $dayheader;
		$records['monthheader']= $monthheader;
		$records['yearsheader']= $yearsheader;
		$records['dayfooter']  = $dayfooter;
		$records['monthfooter']= $monthfooter;
		$records['yearsfooter']= $yearsfooter;  
		$records['nama']       = $nama; 
			$result = '{ "status":"ok", "message":"1", "records":'.json_encode($records).'}';
		return $result;
	}
	

	
}




?>




