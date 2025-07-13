<?php class GetListData {
	public function data(){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT  A.n_id
					,A.v_nik
					,A.d_create 
					,B.nama
						FROM hr_pengundurandiri A
					LEFT JOIN(SELECT nik,nama FROM hr_masteremployee) B
					ON A.v_nik = B.nik
						";
		$stmt = mysql_query($q);

		$outp = '';
		$id = array();
		$nik = array();
		$nama = array();
		$tglkeluar = array();
		while($row=mysql_fetch_array($stmt)){
			array_push($id,rawurlencode($row['n_id']));
			array_push($nama,rawurlencode($row['nama']));
			array_push($nik,rawurlencode($row['v_nik']));
			array_push($tglkeluar,rawurlencode($row['d_create']));
		}
		
		$records[] = array();
		$records['id'] = $id;
		$records['nik'] = $nik;
		$records['nama'] = $nama;
		$records['tglkeluar'] = $tglkeluar;
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




