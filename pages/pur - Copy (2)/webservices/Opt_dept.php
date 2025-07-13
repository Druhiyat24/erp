<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get();
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get(){
		include __DIR__ .'/../../../include/conn.php';
		$q = "select kode_mkt id,kode_mkt tampil from userpassword

                where kode_mkt!='' group by kode_mkt order by kode_mkt";
			//echo $q;
		$outp = "";
		$stmt = mysql_query($q);
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"id":"'.rawurlencode($row['id']).'",';
			$outp .= '"isi":"'. rawurlencode($row["tampil"]). '"}'; 	
		}	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		return $result;
	}
}




?>




