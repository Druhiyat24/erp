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
	$Save = new Save();
$result = $Save->Update($data);	
print_r($result);
	
	
}


//print_r($List);


class Save {
	 protected function genarateNoSurat($myKontrak){
		//include __DIR__ .'/../../../include/conn.php';
		include_once __DIR__ .'/../../../include/conn.php';
		$q = "SELECT  count(*) jlh FROM hr_kontrakkerja";
		$stmt = mysql_query($q);
		while($row=mysql_fetch_array($stmt)){
			$x=$row['jlh'] + 1;
		}
			//No. 428/HRD-NAG/P2WT-2/I/2018
		$id ="No. ".sprintf("%03d", $x).'/HRD-NAG/PKWT-"'.sprintf("%02d", $myKontrak).'"/'.date('Y');
		//echo $id;
		return $id;	
	}
	 protected function generateDate($date,$month,$years){
		
		$month = intval($month) + 1;
		$month = sprintf("%02d", $month);
		$dates =$years.'-'.$month.'-'.$date;
		
		return $dates;
	}
	 protected function generateDate2($date,$month,$years){
		
		$month = $month + 1;
		
		$month = sprintf("%02d", $month);
		$dates =$years.'-'.$month.'-'.$years;
		
		return $dates;
	}	
	 protected function generateDate3($date,$month,$years){
		
		$month = $month + 1;
		
		$month = sprintf("%02d", $month);
		$dates =$years.'-'.$month.'-'.$years;
		
		return $dates;
	}	
	protected function getFieldStartKontrak($myKontrak){
			$kontrak = $myKontrak;
			$arrayField = array();
			if($kontrak == '1' ){
				$fieldStart = 'mulai_kontrak1';
				$fieldFinish= 'selesai_kotrak1';   
			
				}
			if($kontrak == '2' ){
				$fieldStart = 'mulai_kontrak2';
				$fieldFinish = 'selesai_kontrak2';	
				
				}				
				
		array_push($arrayField,$fieldStart);
		array_push($arrayField,$fieldFinish);
		return $arrayField;
	}
	

	public function Insert($data){
		
		include_once __DIR__ .'/../../../include/conn.php';
		//include __DIR__ .'/../../../include/conn.php';
		//echo "$data->content_tglday1 ||";
		$id             = $this->genarateNoSurat($data->content_kontrak_ke);
		$createdate     = $this->generateDate($data->content_tglday1,$data->content_tglmonth1,$data->content_tglyears1);
		//echo $createdate;
		$mulaikontrak   = $this->generateDate($data->content_tglday2,$data->content_tglmonth2,$data->content_years2);
		$selesaikontrak = $this->generateDate($data->content_tglday3,$data->content_tglmonth3,$data->content_years3);
		//echo $selesaikontrak;
		$getFieldKontrak= $this->getFieldStartKontrak($data->content_kontrak_ke);
		$startkontrak   = $getFieldKontrak[0];
		$finishkontrak  = $getFieldKontrak[1];
		$kontraks		= $getFieldKontrak[2];
		//echo "ID :$id";
		$myDate = date('Y-m-d H:s:i');
		$q = "INSERT INTO hr_kontrakkerja (n_id, v_nik1,v_nik2,d_create,n_kontrak,d_insert) values('$id','$data->nik','$data->nik2','$createdate','$kontraks','$myDate')";
		$stmt = mysql_query($q);
		
		$q	= "UPDATE  hr_masteremployee SET jabatan = '$data->bagian2_skk'
						,department = '$data->department2_skk'
						,tempat_lahir = '$data->tempatlahir2_skk'
						,alamat_karyawan =  '$data->alamat2_skk'
						,tgl_lahir = '$data->tgllahir2_skk'
						,mulai_kerja = '$mulaikontrak'
						,selesai_kerja = '$selesaikontrak'
						,$startkontrak = '$mulaikontrak'
						,$finishkontrak ='$selesaikontrak'
						WHERE nik = '$data->nama2_skk'
		";
		//echo "$q";
		$stmt = mysql_query($q);
			$result = '{ "status":"ok", "message":"1", "records":" " }';
		return $result;
	}
	public function Update($data){
		include_once __DIR__ .'/../../../include/conn.php';
		//include __DIR__ .'/../../../include/conn.php';
		//echo "$data->content_tglday1 ||";
		$id_surat             = $data->n_id;
		$id			= $data->id;
		$createdate     = $this->generateDate($data->content_tglday1,$data->content_tglmonth1,$data->content_tglyears1);
		//echo $createdate;
		$mulaikontrak   = $this->generateDate($data->content_tglday2,$data->content_tglmonth2,$data->content_years2);
		//print_r($data->content_tglday2);
		$selesaikontrak = $this->generateDate($data->content_tglday3,$data->content_tglmonth3,$data->content_years3);
		//echo $data->content_kontrak_ke;
		$getFieldKontrak= $this->getFieldStartKontrak($data->content_kontrak_ke);
		//echo "MASUK";
		//print_r($getFieldKontrak);
		$startkontrak   = $getFieldKontrak[0];
		$finishkontrak  = $getFieldKontrak[1];
		$kontraks		= $getFieldKontrak[2];
		$nomor_surat	= $data->content_part_no.$data->content_kontrak_ke.$data->content_part_no_footer;
		//print_r($nomor_surat);
		$myDate = date('Y-m-d H:s:i');
		//$q = "INSERT INTO hr_kontrakkerja (v_nik1,v_nik2,d_create,n_kontrak,d_insert) //values('$id','$data->nik','$data->nik2','$createdate','$kontraks','$myDate')";
		
$q = "UPDATE hr_kontrakkerja SET 	v_nik1='$data->nik',
									v_nik2 = '$data->nik2',
									d_create = '$createdate',
									n_kontrak = '$data->content_kontrak_ke',
									v_gaji = '$data->content_gaji',
									n_id = '$nomor_surat'
									WHERE id = '$id'
		";		
		//print_r($q);
		$stmt = mysql_query($q);
		
		$q	= "UPDATE  hr_masteremployee SET bagian = 
						'$data->bagian2_skk'
						
						,department = '$data->department2_skk'
						,tempat_lahir = '$data->tempatlahir2_skk'
						,alamat_karyawan =  '$data->alamat2_skk'
						,tgl_lahir = '$data->tgllahir2_skk'
						,mulai_kerja = '$mulaikontrak'
						,selesai_kerja = '$selesaikontrak'
						,$startkontrak = '$mulaikontrak'
						,$finishkontrak ='$selesaikontrak'
						WHERE nik = '$data->nama2_skk'
		";
		//echo "$q";
		$stmt = mysql_query($q);
			$result = '{ "status":"ok", "message":"1", "records":" " }';
		return $result;
	}	
	
}
?>
