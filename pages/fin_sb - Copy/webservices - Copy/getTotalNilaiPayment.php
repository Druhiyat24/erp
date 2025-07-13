<?php 
		$data = $_POST;
		//$arr = $_POST['payment_array'];
//$data = (object)$_POST['data'];
//print_r($data);
if($data['code'] == '1' ){
//	print_r($data);

if(ISSET($_POST['payment_array'])){
$array_nilai = $_POST['payment_array'];
$saldo_berjalan =0;	
if(count($array_nilai) == '0' ){
	$saldo_berjalan = 0;
}else{
	for($i=0;$i<count($array_nilai);$i++){
		$saldo_berjalan = $saldo_berjalan + $array_nilai[$i]['nilai'];
	}
}
}else{
	$saldo_berjalan = 0;
}

$result = '{ "status":"ok", "message":"1","total_nilai" : "'.$saldo_berjalan.'"}';	


print_r($result);
}
else{
	exit;
}



?>




