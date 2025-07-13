<?php 




		$data = $_POST;

//print_r($data);

if($data['code'] == '1' ){
	
	//print_r($data);
	
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
				,department
				,'PT. Nirwana Alabare Garment' perusahaan
				,'Jl. Raya Rancaekek – Majalaya No. 289 Desa Solokan Jeruk Kecamatan Solokan Jeruk, Kabupaten Bandung' alamat
				FROM hr_masteremployee WHERE nik = '$nik'";
		$stmt = mysql_query($q);
		$outp = '';
		$nik 		= array();
		$jabatan 	= array();
		$perusahaan = array();
		$alamat		 = array();
		$department		 = array();
		while($row=mysql_fetch_array($stmt)){
			array_push($nik,rawurlencode($row['nik']));
			array_push($jabatan,rawurlencode($row['jabatan']));
			array_push($perusahaan,rawurlencode($row['perusahaan']));
			array_push($alamat,rawurlencode($row['alamat']));			
			array_push($department,rawurlencode($row['department']));	
		}
		
		$records[] 			  = array();
		$records['nik'] 	  = $nik;
		$records['jabatan']   = $jabatan;
		$records['perusahaan']= $perusahaan;
		$records['alamat'] 	  = $alamat;
		$records['department']= $department;		
			$result = '{ "status":"ok", "message":"1", "records":'.json_encode($records).'}';
		return $result;
	}
}




?>




