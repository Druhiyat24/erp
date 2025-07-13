<?php 




		$data = $_POST;

//print_r($data);

if($data['code'] == '1' ){
	$GetDetailNama = new GetDetailNama();
$List = $GetDetailNama->getDetail1($data['nik']);
print_r($List);
}
else{
	exit;
}
class GetDetailNama {
	public function getDetail1($nik){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT nik
				,jabatan
				,bagian
				,mulai_kerja
				,'PT. Nirwana Alabare Garment' perusahaan,
				'Jl. Raya Rancaekek – Majalaya No. 289 Desa Solokan Jeruk Kecamatan Solokan Jeruk, Kabupaten Bandung' alamat
				FROM hr_masteremployee WHERE nik = '$nik'";
				//echo $q;
		$stmt = mysql_query($q);
		$outp = '';
		$nik 		= array();
		$jabatan 	= array();
		$perusahaan = array();
		$alamat		= array();
		$bagian		= array();
		$day		= array();
		$month		= array();
		$years		= array();
		while($row=mysql_fetch_array($stmt)){
			$date = explode('-',$row['mulai_kerja']);
			//print_r($date);
			array_push($nik,rawurlencode($row['nik']));
			array_push($jabatan,rawurlencode($row['jabatan']));
			array_push($perusahaan,rawurlencode($row['perusahaan']));
			array_push($alamat,rawurlencode($row['alamat']));			
			array_push($bagian,rawurlencode($row['bagian']));	
			array_push($day,rawurlencode(intval($date[2])));	
			array_push($month,rawurlencode(intval($date[1]-1)));	
			array_push($years,rawurlencode(intval($date[0])));	
		}
		$records[] 				= array();
		$records['nik'] 		= $nik;
		$records['jabatan'] 	= $jabatan;
		$records['perusahaan'] 	= $perusahaan;
		$records['alamat'] 		= $alamat;
		$records['bagian'] 		= $bagian;		
		$records['day'] 		= $day;		
		$records['month'] 		= $month;		
		$records['years'] 		= $years;		
		
			$result = '{ "status":"ok", "message":"1", "records":'.json_encode($records).'}';
		return $result;
	}
}




?>




