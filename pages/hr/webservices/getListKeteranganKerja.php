<?php class GetListData {
	public function data(){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT  A.n_id
					,A.v_nik2
					,A.d_create 
					,v_lamakerja
					,B.nama
						FROM hr_keterangankerja A
					LEFT JOIN(SELECT nik,nama FROM hr_masteremployee) B
					ON A.v_nik2 = B.nik
						";
		$stmt = mysql_query($q);

		$outp = '';
		$id = array();
		$nik2 = array();
		$nama = array();
		$lamakerja = array();
		while($row=mysql_fetch_array($stmt)){
			array_push($id,rawurlencode($row['n_id']));
			array_push($nama,rawurlencode($row['nama']));
			array_push($nik2,rawurlencode($row['v_nik2']));
			array_push($lamakerja,rawurlencode($row['v_lamakerja']));
		}
		
		$records[] = array();
		$records['id'] = $id;
		$records['nik2'] = $nik2;
		$records['nama'] = $nama;
		$records['lamakerja'] = $lamakerja;
		//array_push($records,json_encode($nik));
		//array_push($records,json_encode($nama));
	//print_r($nik);
			$result = '{ "status":"ok", "message":"", "records":'.json_encode($records).'}';
		return $result;
	}
}


	//print_r($data);
$GetListData = new GetListData();
$List = $GetListData->data();
//$data = $List ;
print_r($List);
//echo $data;
?>




