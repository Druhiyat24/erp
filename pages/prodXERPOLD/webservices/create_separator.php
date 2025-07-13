<?php
session_start();

/*
format =  1 : INSERT
format =  2 : UPDATE
format =  3 : DELETE
*/
//$angka =(object)$_POST['data'];
//$detail=$_POST['detail'];
/*    print_r($detail);
 die();  */ 
$code = $_POST['code'];
$id = $_POST['id'];
//$format = $_POST['format'];
// $data->dtpicker	  	= date("Y-m-d", strtotime($data->dtpicker)); 
if($code == '1'){
	$hasil = str_replace(",","",$_POST['angka']);
	$hasil = number_format((float)$hasil, 2, '.', ',');
	$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"'.$hasil.'", "id" : "'.$id.'"}';
	echo $result;
}
else{
	exit;
}


?>