<?php class GetListData {
	public function data($price,$date){
		include "../../include/conn.php";
		$q = "SELECT tanggal, rate_jual,rate_beli,($price * rate_jual) hasiljual,($price / rate_beli) hasilbeli from masterrate WHERE tanggal = '$date' AND v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12') LIMIT 1;
						";
						
		$stmt = mysql_query($q);
		
		//echo $q;
		$outp = '';

		$hasiljual = array();
		$hasilbeli = array();
		while($row=mysql_fetch_array($stmt)){

			array_push($hasiljual,number_format($row['hasiljual'],2,',','.'));
			array_push($hasilbeli,number_format($row['hasilbeli'],2,',','.'));
		}
		$records[] = array();

		$records['hasiljual'] = $hasiljual;
		$records['hasilbeli'] = $hasilbeli;
			$result = '{ "status":"ok", "message":"", "records":'.json_encode($records).'}';
		return $result;
	}
}


	//print_r($data);
$data = (object)($_POST);
//print_r($data->data['price']);

$date = date('Y-m-d',strtotime($data->data['date']));
//print_r($date);
$GetListData = new GetListData();
$List = $GetListData->data($data->data['price'],$date);
//$data = $List ;
print_r($List);
//echo $data;
?>




