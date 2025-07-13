<?php 
$data = (object)$_POST['data'];
//print_r($data);


	//echo "123";
$Delete = new Delete();
$result = $Delete->Hapus($data);	
print_r($result);
	



//print_r($List);


class Delete {
	public function Hapus($data){
		include_once __DIR__ .'/../../../include/conn.php';
		//echo "ID :$id";
		$q = "DELETE FROM hr_pengundurandiri WHERE n_id = $data->id";
		//echo $q;
		$stmt = mysql_query($q);
			$result = '{ "status":"ok", "message":"1", "records":" " }';
		return $result;
	}
	
}
?>
