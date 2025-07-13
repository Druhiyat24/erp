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
						,A.v_nik2
						,A.d_insert
						,A.v_lamakerja
						,department
						,DATE(A.d_create) d_create
						,A.v_reason
						,B.alamat_karyawan 
						,B.jabatan 
						,B.mulai_kerja
						,B.nama
						,C.namaPT
						FROM hr_keterangankerja A
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
		$nik       = array();
		$nik2      = array();
		$jabatan   = array();
		$alamat    = array();
		$department= array();
		$day       = array();
		$month     = array();
		$years     = array();
		$nama      = array(); 
		$lamakerja      = array();  
		$reason		= array();
		$rowCount=count(mysql_num_rows($stmt));
		//echo "COYUNT: $rowCount";
		if($rowCount > 0){
		while($row=mysql_fetch_array($stmt)){
			$date = $this->decodeDate($row['d_create']);

			array_push($nik,rawurlencode($row['v_nik']));
			array_push($nik2,rawurlencode($row['v_nik2']));
			array_push($jabatan,rawurlencode($row['jabatan']));
			array_push($alamat,rawurlencode($row['alamat_karyawan']));	
			array_push($department,rawurlencode($row['department']));
			array_push($day,rawurlencode(intval($date[2])));
			array_push($month,rawurlencode(intval($date[1])-1));
			array_push($years,rawurlencode(intval($date[0])));
			array_push($nama,rawurlencode($row['nama']));	
			array_push($reason,rawurlencode($row['v_reason']));
array_push($lamakerja,rawurlencode($row['v_lamakerja']));				
		}			
		}
		else{

			array_push($nik,rawurlencode(''));
			array_push($nik2,rawurlencode(''));
			array_push($jabatan,rawurlencode(''));
			array_push($alamat,rawurlencode(''));	
			array_push($department,rawurlencode(''));
			array_push($day,rawurlencode(''));
			array_push($month,rawurlencode(''));
			array_push($years,rawurlencode(''));
			array_push($nama,rawurlencode(''));	
			array_push($lamakerja,rawurlencode(''));				
			
			
		}
		$records[]            = array();
		$records['nik']       = $nik;
		$records['nik2']      = $nik2;
		$records['jabatan']   = $jabatan;
		$records['alamat']    = $alamat; 
		$records['department']= $department; 
		//print_r($records['department']);
		$records['day']       = $day;
		$records['month']     = $month;
		$records['years']     = $years;
		$records['nama']      = $nama; 
		$records['lamakerja']      = $lamakerja; 
		$records['reason']      = $reason; 
			$result = '{ "status":"ok", "message":"1", "records":'.json_encode($records).'}';
		return $result;
	}
	

	
}




?>




