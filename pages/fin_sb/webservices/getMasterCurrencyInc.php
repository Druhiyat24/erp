<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){
	$getListData = new getListData();
	if(ISSET($data['id'])){
		$List = $getListData->get($data['id']);
	}else{
		$outp = '';
		if ($outp != "") {$outp .= ",";}
		$outp .= '{"rate_beli":"'.rawurlencode(" ").'"}'; 
		$List = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
	}
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	public function get($id){
		$tgl = date("Y-m-d");
		include __DIR__ .'/../../../include/conn.php';
		$q = "SELECT rate_beli from masterrate where tanggal - '$tgl'"; 
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"rate_beli":"'.rawurlencode($row['rate_beli']).'"}'; 	
		}
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}




?>




