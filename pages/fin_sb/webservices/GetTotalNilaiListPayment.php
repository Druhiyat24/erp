<?php 
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


		$data = $_POST;
$data = (object)$_POST;
//print_r($data);
if($data->code == '1' ){
	$getListData = new getListData($data);
	
	if($data->operation == 'PLUS'){
		$List = $getListData->Plus($data);
	}else{
		$List = $getListData->Minus($data);
	}
print_r($List);
}
else{
	exit;
}
class getListData {
	
	public function Plus($data){
		//print_r($data);
		$total_nilai = str_replace(",","", $data->n_nilai);
		$nilai_pengurang   = str_replace(",","", $data->new_nilai);
		$total_nilai = $total_nilai + $nilai_pengurang;
		
		$result = '{ "status":"ok", "message":"1","total_nilai": "'.$total_nilai.'"}';
		return $result;
	}	
	public function Minus($data){
		//print_r($data);
		$total_nilai = str_replace(",","", $data->total_nilai);
		$nilai_pengurang   = str_replace(",","", $data->nilai_pengurang);
		$nilai_input = str_replace(",","", $data->nilai_input);
		$sisa = str_replace(",","", $data->sisa);
		$total_nilai = $total_nilai - $nilai_pengurang;
		$total_nilai_by_sisa = $sisa - $nilai_pengurang;
/* 		echo $total_nilai;
		if(($total_nilai) < ($nilai_pengurang)){
			$result = '{ "status":"ok", "message":"2" ,"total_nilai": "'.$total_nilai.'"}';
		}else{ */
			//echo "123";
			if(($sisa) < ($nilai_pengurang)){
				$result = '{ "status":"ok", "message":"3" ,"total_nilai": "'.$total_nilai_by_sisa.'"}';
			}
			
			$total_nilai_by_sisa = $sisa - $nilai_pengurang;
			$result = '{ "status":"ok", "message":"1" ,"total_nilai": "'.$total_nilai.'","sisa":"'.$total_nilai_by_sisa.'"}';
		//}
		
		return $result;
	}		

}
?>





