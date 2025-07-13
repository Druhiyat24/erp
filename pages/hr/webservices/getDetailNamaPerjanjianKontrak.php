<?php 




		$data = $_POST;

//print_r($data);

if($data['code'] == '1' ){
//print_r($data)
	$GetDetailNama = new GetDetailNama();
$List = $GetDetailNama->getDetail1($data['nik']);
print_r($List);
}
else{
	$GetDetailNama = new GetDetailNama();
$List = $GetDetailNama->getDetail2($data['nik']);
print_r($List);	
	

}
class GetDetailNama {
	public function getDetail1($nik){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT nik
				,nama
				,jabatan
				,bagian
				,'PT. Nirwana Alabare Garment' perusahaan
				,alamat_karyawan
				FROM hr_masteremployee WHERE nik = '$nik'";
				//echo $q;
		$stmt = mysql_query($q);
		$outp = '';
		$nama 		= array();
		$nik 		= array();
		$jabatan 	= array();
		$perusahaan = array();
		$alamat		 = array();
		$bagian		 = array();
		while($row=mysql_fetch_array($stmt)){
			array_push($nama,rawurlencode($row['nama']));
			array_push($nik,rawurlencode($row['nik']));
			array_push($jabatan,rawurlencode($row['jabatan']));
			array_push($perusahaan,rawurlencode($row['perusahaan']));
			array_push($alamat,rawurlencode($row['alamat_karyawan']));			
			array_push($bagian,rawurlencode($row['bagian']));	
			
		}
		
		$records[] 				= array();
		$records['nama'] 		= $nama;
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
				,nama
				,jabatan
				,bagian
				,'PT. Nirwana Alabare Garment' perusahaan
				,alamat_karyawan
				FROM hr_masteremployee WHERE nik = '$nik'";
				//echo $q;
		$stmt = mysql_query($q);
		$outp = '';
		$nama 		= array();
		$nik 		= array();
		$jabatan 	= array();
		$perusahaan = array();
		$alamat		 = array();
		$bagian		 = array();
		while($row=mysql_fetch_array($stmt)){
			array_push($nama,rawurlencode($row['nama']));
			array_push($nik,rawurlencode($row['nik']));
			array_push($jabatan,rawurlencode($row['jabatan']));
			array_push($perusahaan,rawurlencode($row['perusahaan']));
			array_push($alamat,rawurlencode($row['alamat_karyawan']));			
			array_push($bagian,rawurlencode($row['bagian']));	
			
		}
		
		$records[] 				= array();
		$records['nama'] 		= $nama;
		$records['nik'] 		= $nik;
		$records['jabatan'] 	= $jabatan;
		$records['perusahaan'] 	= $perusahaan;
		$records['alamat'] 		= $alamat;
		$records['bagian'] 		= $bagian;		
			$result = '{ "status":"ok", "message":"2", "records":'.json_encode($records).'}';
		return $result;
	}	
	

	
}




?>




