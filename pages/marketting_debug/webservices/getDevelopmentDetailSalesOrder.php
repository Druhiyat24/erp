<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data['idd']);
print_r($List);
}
else{
	exit;
}
class getListData {
	public function get($id_det){
		include __DIR__ .'/../../../include/conn.php';
		$q = "select * from so_dev where id='$id_det'";
		//echo $q;
		$stmt = mysql_query($q);		
		$outp = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			
/*
  $deldate=fd_view($rs['deldate_det']);
  $dest=$rs['dest'];
  $color=$rs['color'];
  $sku=$rs['sku'];
  $notes=$rs['notes'];
  $barcode=$rs['barcode'];
  $qty=$rs['qty'];
  $price=$rs['price'];
*/			
			
			$outp .= '[{"deldate":"'.rawurlencode(date('d M Y',strtotime($row['deldate_det']))).'",';
			//$outp .= '[{"deldate":"'.rawurlencode($row['deldate_det']).'",';
			$outp .= '"dest":"'.rawurlencode($row['dest']).'",';
			$outp .= '"color":"'.rawurlencode($row['color']).'",';
			$outp .= '"sku":"'.rawurlencode($row['sku']).'",';
			$outp .= '"notes":"'.rawurlencode($row['notes']).'",';
			$outp .= '"barcode":"'.rawurlencode($row['barcode']).'",';
			$outp .= '"qty":"'.rawurlencode($row['qty']).'",';
			$outp .= '"price":"'. rawurlencode($row["price"]). '"}]'; 	
		}	
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    }';
		return $result;
	}
}
?>




