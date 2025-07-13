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
						,A.v_nomorsurat
						,A.v_nik
						,A.v_nik2
						,A.d_create
						,A.v_periodekontrak
						,department
						,B.alamat_karyawan 
						,B.jabatan 
						,B.mulai_kerja
						,B.nama
						,C.namaPT
						,C.jabatanPT
						,C.namaPPT
						FROM hr_perjanjiankerja A
					LEFT JOIN (SELECT nik,jabatan,alamat_karyawan,mulai_kerja,nama,department FROM hr_masteremployee) B
					ON A.v_nik2 = B.nik
					LEFT JOIN (SELECT nik
						,'PT. Nirwana Alabare Garment' perusahaan,
							'Jl. Raya Rancaekek – Majalaya No. 289 Desa Solokan Jeruk Kecamatan Solokan Jeruk, Kabupaten Bandung' alamatPT
						,'PT. Nirwana Alabare Garment' namaPT
						,department departmentPT
						,nama namaPPT
						,bagian bagianPT
						,jabatan jabatanPT
							 FROM hr_masteremployee) C
					ON A.v_nik = C.nik					
					
					
					WHERE A.n_id = '$id'";
				//echo "$q";
		$stmt = mysql_query($q);
		$outp = '';
		$nomorsurat       = array();
		$nik       = array();
		$d_create       = array();
		$nik2      = array();
		$jabatan   = array();
		$alamat    = array();
		$department= array();
		$periodekontrak = array();
		$nama      = array(); 
		$namaPPT      = array();   
		$jabatanPT      = array();  
		$rowCount=count(mysql_num_rows($stmt));
		//echo "COYUNT: $rowCount";
		if($rowCount > 0){
		while($row=mysql_fetch_array($stmt)){
			array_push($periodekontrak,rawurlencode($row['v_periodekontrak']));
			array_push($d_create,rawurlencode($row['d_create']));
			array_push($nomorsurat,rawurlencode($row['nomorsurat']));
			array_push($nik,rawurlencode($row['v_nik']));
			array_push($nik2,rawurlencode($row['v_nik2']));
			array_push($jabatan,rawurlencode($row['jabatan']));
			array_push($alamat,rawurlencode($row['alamat_karyawan']));	
			array_push($department,rawurlencode($row['department']));
			array_push($nama,rawurlencode($row['nama']));	
			array_push($namaPPT,rawurlencode($row['namaPPT']));	
			array_push($jabatanPT,rawurlencode($row['jabatanPT']));	
array_push($lamakerja,rawurlencode($row['v_lamakerja']));				
		}			
		}
		else{


			
		}
		$records[]                = array();
		$records['periodekontrak']= $periodekontrak;
		$records['jabatanPT']     = $jabatanPT;
		$records['namaPPT']       = $namaPPT;
		$records['nik']           = $nik;
		$records['d_create']      = $d_create;
		$records['nomorsurat']    = $nomorsurat;
		$records['nik2']          = $nik2;
		$records['jabatan']       = $jabatan;
		$records['alamat']        = $alamat; 
		$records['department']    = $department; 
		$records['nama']          = $nama; 
		$records['lamakerja']     = $lamakerja; 
		$records['reason']        = $reason; 
			$result = '{ "status":"ok", "message":"1", "records":'.json_encode($records).'}';
		return $result;
	}
	

	
}




?>




