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
		$id_rekap = $getListData ->get_rekap($data->id_rekap);

		$List = $getListData->Compare($data,$id_rekap);
	
print_r($List);
}
else{
	exit;
}
class getListData {
	function CompileQuery($query,$mode){
		
		include __DIR__ .'/../../../include/conn.php';
		$stmt = mysqli_query($conn_li,$query);	
		if(mysqli_error($conn_li)){
		
			$result = mysqli_error($conn_li)."__ERRTRUE";
			return $result;
		}	
		else{
			if($mode == "CRUD"){
				print_r($query);
				$result = '{ "status":"ok", "message":"1"}';
				return $result;
			}
			else{
				
				if(mysqli_num_rows($stmt) == '0' ){
					$result = '{ "status":"ok", "message":"2"}';
					return '0';
				}
				else{
					return $stmt;
				}
			}
		} 
	}	
	
	public function get_rekap($id_rekap){
		$rekap[] = array();
		if($id_rekap == "NA"){
			$rekap['id_rekap']   = "NA";
			$rekap['is_header']  = "NA";
			$rekap['total_nilai']= 0;
		}else{
			$q = "SELECT A.is_header,A.v_listcode,B.n_total_amount FROM fin_status_journal_ap A
					INNER JOIN fin_payment_header B ON A.v_listcode = B.v_list_src
					WHERE A.v_listcode = '$id_rekap'
				";
			
			$MyList = $this->CompileQuery($q,'SELECT');
			if($MyList == '0'){
				$result = '{ "status":"ok", "message":"2", "records":"0"}';
			}
			else{
				$EXP = explode("__ERR",$MyList);
				if($EXP[1]){
					$result = '{ "status":"no", "message":"'.$EXP[0].'", "records":"0"}';
				}
				else{
					while($row = mysqli_fetch_array($MyList)){
						$rekap['id_rekap']   	=$row['v_listcode'];   
						$rekap['is_header']   	=$row['is_header']; 
						$rekap['total_nilai']   =$row['n_total_amount'];						
						
					}

				}		
			}
			

			return $rekap;






			
		}
		
	}
	
	public function Compare($data,$rekap){
		$outstanding_nilai = (str_replace(",","", $data->outstanding_nilai));
		$nilai_input   = (str_replace(",","", $data->nilai_input));
		$sisa   = (str_replace(",","", $data->sisa));	

		if($data->nilai_input == "0.00"){
			$data->nilai_input = 0;
				$key = 0;
				$result = '{ "status":"ok", "message":"2","key": "'.$key.'","description":"Nilai Tidak Boleh Kosong!"}';
				return $result;			
		}
						if($outstanding_nilai < $nilai_input){
							$key = 0;
							$result = '{ "status":"ok", "message":"2","key": "'.$key.'","description":"Nilai Input Lebih Besar Dari Total Nilai"}';
							return $result;
						}
		
		if($rekap['id_rekap'] == "NA" ){
				$key = 1;
				$result = '{ "status":"ok", "message":"1","key": "'.$key.'","description":"PASS"}';
				return $result;				
			
			
			
		}else{
			if($rekap['is_header'] == "Y" ){
				$key = 1;
				$result = '{ "status":"ok", "message":"1","key": "'.$key.'","description":"PASS"}';
				return $result;							
			}else{
					//print_r($outstanding_nilai - $nilai_input);
					if((is_numeric($outstanding_nilai)==TRUE) && (is_numeric($nilai_input)==TRUE)){
						if($sisa < $nilai_input){
							$key = 0;
							$result = '{ "status":"ok", "message":"2","key": "'.$key.'","description":"Nilai Input Lebih Besar Dari Sisa yang harus di bayar"}';
							return $result;
						}			
						else{
							
							$key = 1;
							$result = '{ "status":"ok", "message":"1","key": "'.$key.'","description":"PASS"}';
							return $result;
						}
			
						
					}else{
						$key = 0;
						$result = '{ "status":"ok", "message":"2","key": "'.$key.'","description":"Inputan Salah! Reload Page!"}';
						return $result;
					}				
				
			}
			
		}

		return $result;
	}	
		

}
?>





