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
		$q = "SELECT 
						 A.id
						,A.n_id
						,A.v_nik1
						,A.v_nik2
						,A.d_insert
						,A.n_kontrak
						,A.v_gaji
						,DATE(A.d_create) d_create
						,B.alamat_karyawan 
						,B.jabatan 
						,B.mulai_kerja
						,B.selesai_kerja
						,B.nama
						,B.department
						,B.bagian
						,B.tempat_lahir
						,B.tgl_lahir
						,C.alamatPT
						,C.departmentPT
						,C.bagianPT
						,C.perusahaan
						,C.jabatanPT
						FROM hr_kontrakkerja A
					LEFT JOIN (SELECT nik,jabatan,alamat_karyawan,mulai_kerja,selesai_kerja,nama,department,bagian,tempat_lahir,tgl_lahir FROM hr_masteremployee) B
					ON A.v_nik2 = B.nik
					LEFT JOIN (SELECT nik
						,'PT. Nirwana Alabare Garment' perusahaan,
							'Jl. Raya Rancaekek – Majalaya No. 289 Desa Solokan Jeruk Kecamatan Solokan Jeruk, Kabupaten Bandung' alamatPT
						,'PT. Nirwana Alabare Garment' namaPT
						,department departmentPT
						,bagian bagianPT
						,jabatan jabatanPT
							 FROM hr_masteremployee) C
					ON A.v_nik1 = C.nik					
					WHERE A.id = $id";
				//echo "$q";
		$stmt = mysql_query($q);
		$outp = '';
		$n_id          = array();
		$nik1          = array();
		$nik2          = array();
		$gaji          = array();
		$kontrakke     = array();
		$partheader    = array();
		$partfooter    = array();
		$jabatan       = array();
		$jabatanPT     = array();
		$nama          = array(); 
		$department    = array(); 
		$alamat        = array();
		$bagian        = array(); 
		$alamatPT      = array();
		$perusahaan    = array();
		$d_insert_day  = array();
		$d_insert_month= array();
		$d_insert_years= array();
		$day           = array();
		$month         = array();
		$years         = array();
		$dayheader     = array();
		$monthheader   = array();
		$yearsheader   = array();    
		$dayfooter     = array();
		$monthfooter   = array();
		$yearsfooter   = array();  
		$departmentPT  = array();  
		$tempatlahir   = array(); 
		$tanggallahir  = array(); 
		$bagianPT      = array();  
		$rowCount=count(mysql_num_rows($stmt));
		//echo "COYUNT: $rowCount";
		if($rowCount > 0){
		while($row=mysql_fetch_array($stmt)){
			
			$nosurat = $row['n_id'];
			$kontrakkee = substr($nosurat,22,1);
			$partheaderr = substr($nosurat,0,22);
			$partfooterr = substr($nosurat,23);
		
			
			$date = $this->decodeDate($row['d_create']);
		//	print_r($date); 
			$dateNow = $this->decodeDate($row['d_insert']);
			$dateHeader = $this->decodeDate($row['mulai_kerja']);
			$dateFooter = $this->decodeDate($row['selesai_kerja']);
			array_push($gaji,rawurlencode($row['v_gaji']));
			array_push($kontrakke,rawurlencode($kontrakkee));
			array_push($partheader,rawurlencode($partheaderr));
			array_push($partfooter,rawurlencode($partfooterr));			
			array_push($n_id,rawurlencode($row['n_id']));
			array_push($nik1,rawurlencode($row['v_nik1']));
			array_push($nik2,rawurlencode($row['v_nik2']));
			array_push($kontrak,rawurlencode($row['n_kontrak']));
			array_push($jabatan,rawurlencode($row['jabatan']));
			array_push($jabatanPT,rawurlencode($row['jabatanPT']));
			array_push($nama,rawurlencode($row['nama']));	
			array_push($department,rawurlencode($row['department']));	
			array_push($bagian,rawurlencode($row['bagian']));	
			array_push($alamat,rawurlencode($row['alamat_karyawan']));	\
			array_push($alamatPT,rawurlencode($row['alamatPT']));	
			array_push($perusahaan,rawurlencode($row['perusahaan']));
			array_push($day,rawurlencode(intval($date[2])));
			array_push($month,rawurlencode(intval($date[1])-1));
			array_push($years,rawurlencode(intval($date[0])));
			array_push($d_insert_day,rawurlencode(intval($date[2])));
			array_push($d_insert_month,rawurlencode(intval($date[1])-1));
			array_push($d_insert_years,rawurlencode(intval($date[0])));			
			array_push($dayheader,rawurlencode(intval($dateHeader[2])));
			array_push($monthheader,rawurlencode(intval($dateHeader[1])-1));
			array_push($yearsheader,rawurlencode(intval($dateHeader[0])));				
			array_push($dayfooter,rawurlencode(intval($dateFooter[2])));
			array_push($monthfooter,rawurlencode(intval($dateFooter[1])-1));
			array_push($yearsfooter,rawurlencode(intval($dateFooter[0])));	
			array_push($departmentPT,rawurlencode($row['departmentPT']));	
			array_push($bagianPT,rawurlencode($row['bagianPT']));	
			array_push($tempatlahir,rawurlencode($row['tempat_lahir']));	
			array_push($tanggallahir,rawurlencode($row['tgl_lahir']));				
				
		}			
		}
		else{
			array_push($n_id,rawurlencode(''));
			array_push($nik1,rawurlencode(''));
			array_push($nik2,rawurlencode(''));
			array_push($jabatan,rawurlencode(''));
			array_push($jabatanPT,rawurlencode(''));
			array_push($nama,rawurlencode(''));	
			array_push($department,rawurlencode(''));	
			array_push($bagian,rawurlencode(''));	
			array_push($alamat,rawurlencode(''));	
			array_push($alamatPT,rawurlencode(''));	
			array_push($perusahaan,rawurlencode(''));
			
			array_push($d_insert_day,rawurlencode(''));
			array_push($d_insert_month,rawurlencode(''));
			array_push($d_insert_years,rawurlencode(''));	
	
			array_push($day,rawurlencode(''));
			array_push($month,rawurlencode(''));
			array_push($years,rawurlencode(''));
			array_push($dayheader,rawurlencode(''));
			array_push($monthheader,rawurlencode(''));
			array_push($yearsheader,rawurlencode(''));				
			array_push($dayfooter,rawurlencode(''));
			array_push($monthfooter,rawurlencode(''));
			array_push($yearsfooter,rawurlencode(''));			
			
			
		}
		$records[]                = array();
		
		
		$records['gaji']     =$gaji;
		$records['kontrakke']     =$kontrakke;
		$records['partheader']    =$partheader;
		$records['partfooter']    =$partfooter;
		$records['n_id']          = $n_id;
		$records['nik1']          = $nik1;
		$records['nik2']          = $nik2; 
		$records['kontrak']       = $kontrak;
		$records['jabatan']       = $jabatan;
		$records['jabatanPT']     = $jabatanPT;
		$records['nama']          = $nama;
		$records['department']    = $department;
		$records['bagian']        = $bagian;
		$records['alamat']        = $alamat;
		$records['alamatPT']      = $alamatPT;
		$records['perusahaan']    = $perusahaan;
		$records['d_insert_day']  = $d_insert_day;
		$records['d_insert_month']= $d_insert_month;  
		$records['d_insert_years']= $d_insert_years;   
		$records['day']           = $day;
		$records['month']         = $month;  
		$records['years']         = $years; 
		$records['dayheader']     = $dayheader;
		$records['monthheader']   = $monthheader;  
		$records['yearsheader']   = $yearsheader; 
		$records['dayfooter']     = $dayfooter;
		$records['monthfooter']   = $monthfooter;  
		$records['yearsfooter']   = $yearsfooter;  
		$records['departmentPT']  = $departmentPT; 
		$records['bagianPT']      = $bagianPT; 
		$records['tempatlahir']   = $tempatlahir; 
		$records['tanggallahir']  = $tanggallahir;  
		
			$result = '{ "status":"ok", "message":"1", "records":'.json_encode($records).'}';
		return $result;
	}
	
	
}
?>
