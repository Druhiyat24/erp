<?php 




		$data = $_POST;

//print_r($data);

if($data['code'] == '1' ){
	$GetDetailNama = new GetDetailNama();
$List = $GetDetailNama->getDetail1($data['nik']);
print_r($List);
}

else if($data['code'] == '2' ){
		$GetDetailNama = new GetDetailNama();
$List = $GetDetailNama->getDetail2($data['nik']);
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
				,'PT. Nirwana Alabare Garment' perusahaan,
				'Jl. Raya Rancaekek – Majalaya No. 289 Desa Solokan Jeruk Kecamatan Solokan Jeruk, Kabupaten Bandung' alamat
				FROM hr_masteremployee WHERE nik = '$nik'";
		$stmt = mysql_query($q);
		$outp = '';
		$nik 		= array();
		$jabatan 	= array();
		$perusahaan = array();
		$alamat		 = array();
		$bagian		 = array();
		while($row=mysql_fetch_array($stmt)){
			array_push($nik,rawurlencode($row['nik']));
			array_push($jabatan,rawurlencode($row['jabatan']));
			array_push($perusahaan,rawurlencode($row['perusahaan']));
			array_push($alamat,rawurlencode($row['alamat']));			
			array_push($bagian,rawurlencode($row['bagian']));	
			
		}
		
		$records[] 				= array();
		$records['nik'] 		= $nik;
		$records['jabatan'] 	= $jabatan;
		$records['perusahaan'] 	= $perusahaan;
		$records['alamat'] 		= $alamat;
		$records['bagian'] 		= $bagian;		
			$result = '{ "status":"ok", "message":"1", "records":'.json_encode($records).'}';
		return $result;
	}
	
	public function getDetail2($nik){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT nik
				,jabatan
				,'PT. Nirwana Alabare Garment' perusahaan
				,alamat_karyawan
				,department
				,divisi_kerja
				,bagian
				,tempat_lahir
				,tgl_lahir
				FROM hr_masteremployee WHERE nik = '$nik'";
		$stmt = mysql_query($q);
		$outp = '';
		$nik 		= array();
		$jabatan 	= array();
		$perusahaan = array();
		$alamat_karyawan = array();
		$department = array();
		$divisi_kerja = array();
		$tempat_lahir = array();
		$tgl_lahir = array();
		$bagian = array();
		//print_r(mysql_fetch_array($stmt));
		while($row=mysql_fetch_array($stmt)){
			array_push($nik,rawurlencode($row['nik']));
			array_push($jabatan,rawurlencode($row['jabatan']));
			array_push($perusahaan,rawurlencode($row['perusahaan']));
			array_push($alamat_karyawan,rawurlencode($row['alamat_karyawan']));	
			array_push($department,rawurlencode($row['department']));
			array_push($divisi_kerja,rawurlencode($row['divisi_kerja']));
			array_push($tempat_lahir,rawurlencode($row['tempat_lahir']));
			array_push($tgl_lahir,rawurlencode($row['tgl_lahir']));			
			array_push($bagian,rawurlencode($row['bagian']));		
		}
		//print_r($alamat_karyawan);
		$records[] 							= array();
		$records['nik'] 					= $nik;
		$records['jabatan'] 				= $jabatan;
		$records['perusahaan'] 				= $perusahaan;
		$records['alamat_karyawan'] 		= $alamat_karyawan;		
		$records['department'] 				= $department;
		$records['divisi_kerja'] 			= $divisi_kerja;
		$records['tempat_lahir'] 			= $tempat_lahir;
		$records['tgl_lahir'] 				= $tgl_lahir;	
		$records['bagian'] 				= $bagian;		
			$result = '{ "status":"ok", "message":"2", "records":'.json_encode($records).'}';
		return $result;
	}	
	
}




?>




