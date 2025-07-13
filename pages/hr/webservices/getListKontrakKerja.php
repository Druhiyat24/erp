<?php class GetListData {
	public function data(){
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT  A.id
					,A.n_id
					,A.v_nik2
					,A.d_create 
					,A.n_kontrak 
					,B.nama
						FROM hr_kontrakkerja A
					LEFT JOIN(SELECT nik,nama FROM hr_masteremployee) B
					ON A.v_nik2 = B.nik
						";
		$stmt = mysql_query($q);

		$outp = '';
		$id = array();
		$n_id = array();
		$nik2 = array();
		$nama = array();
		$tglmasuk = array();
		while($row=mysql_fetch_array($stmt)){
			array_push($id,rawurlencode($row['id']));
			array_push($n_id,rawurlencode($row['n_id']));
			array_push($nama,rawurlencode($row['nama']));
			array_push($nik2,rawurlencode($row['v_nik2']));
			array_push($tglmasuk,rawurlencode($row['d_create']));
		}
		$records[] = array();
		$records['n_id'] = $n_id;
		$records['id'] = $id;
		$records['nik2'] = $nik2;
		$records['nama'] = $nama;
		$records['tglmasuk'] = $tglmasuk;
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




