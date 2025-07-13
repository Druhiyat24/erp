<?php class GetNama {
	public function listNama(){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT  nik,nama FROM hr_masteremployee";
		$stmt = mysql_query($q);

		$outp = '';
	
		$nik = array();
		$nama = array();
		while($row=mysql_fetch_array($stmt)){
			array_push($nik,rawurlencode($row['nik']));
			array_push($nama,rawurlencode($row['nama']));
			
			
		}
		
		$records[] = array();
		$records['nik'] = $nik;
		$records['nama'] = $nama;
		//array_push($records,json_encode($nik));
		//array_push($records,json_encode($nama));
	//print_r($nik);
			$result = '{ "status":"ok", "message":"", "records":'.json_encode($records).'}';
		return $result;
	}
}


	//print_r($data);
$GetNama = new GetNama();
$List = $GetNama->listNama();
//$data = $List ;
print_r($List);
//echo $data;
?>




