<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data['id']);
print_r($List);
}
else{
	exit;
}
class getListData {
	public function get($id){
		$id ="$id";
		include __DIR__ .'/../../../include/conn.php';
		$q = " SELECt A.id_so_det,A.bppbno,SOD.id_so,SO.buyerno,ifnull(SUM(A.qty)*SO.fob,0) amount FROM bppb A 
	LEFT JOIN so_det SOD
	ON A.id_so_det = SOD.id
	LEFT JOIN so SO
	ON SOD.id_so = SO.id
	WHERE A.bppbno = '$id' GROUP BY A.bppbno
";
//echo $q;
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		$td = '';
		$x = 0;
		while($row = mysql_fetch_array($stmt)){
			$x= $x+1;
			if ($outp != "") {$outp .= ",";}


			$outp .= '{"buyerno":"'.rawurlencode($row['buyerno']).'",';
				$outp .= '"n_amount":"'. rawurlencode($row["amount"]). '"}'; 	
		}
		if($x == '0'){
			
			$result = '{ "status":"ok", "message":"0", "records":['.$outp.']}';
			
		}
		else{
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']}';
		}
	
		return $result;
	}
}




?>




