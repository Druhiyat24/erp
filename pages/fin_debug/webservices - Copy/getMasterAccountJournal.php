<?php 




		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);

if($data['code'] == '1' ){
	$AccountJournal = new AccountJournal();
$List = $AccountJournal->get();
print_r($List);
}
else{
	exit;
}



class AccountJournal {
	public function get(){
		include __DIR__ .'/../../../include/conn.php';
		$q = "	SELECT A.post_to,B.nm_coa,A.id_coa FROM mastercoa A
		INNER JOIN(
		SELECT id_coa,nm_coa,post_to FROM mastercoa
		) B ON A.post_to = B.id_coa
		GROUP BY A.post_to ";
				//echo $q;
		$stmt = mysql_query($q);		
		$accountjournal = array();
		$nama = array();
		while($row = mysql_fetch_array($stmt)){
			array_push($accountjournal,$row['post_to']);
			array_push($nama,$row['nm_coa']);
		}
		
		$records[] 				= array();
		$records['accountjournal'] = $accountjournal;
		$records['nama'] = $nama;		
			$result = '{ "status":"ok", "message":"1", "records":'.json_encode($records).'}';
		return $result;
	}
	

}




?>




