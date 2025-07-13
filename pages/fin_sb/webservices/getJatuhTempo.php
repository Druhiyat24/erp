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
		$q = "SELECT id,tanggal,curr,rate,rate_jual,rate_beli
				FROM fin_mscurrency ORDER BY tanggal DESC";
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		$td = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
			$outp .= '[{"id":"'.rawurlencode($row['id']).'",';
			$outp .= '"tanggal":"'. rawurlencode($row["tanggal"]). '",'; 
			$outp .= '"curr":"'. rawurlencode($row["curr"]). '",'; 
			$outp .= '"rate":"'. rawurlencode($row["rate"]). '",'; 
			$outp .= '"ratejual":"'. rawurlencode($row["rate_jual"]). '",'; 
			$outp .= '"ratebeli":"'. rawurlencode($row["rate_beli"]). '"}]'; 	
			
			
		$td .="<tr>"; 
		$td .="<td>$row[id]</td>";
		$td .="<td>$row[curr]</td>";
		$td .="<td>$row[tanggal]</td>";  
		$td .="<td>$row[rate]</td>"; 
		$td .="<td>$row[rate_jual]</td>";
		$td .="<td>$row[rate_beli]</td>";
		$td .="<td><a onClick='edit(this.id)' class='btn btn-primary ' id='".$row['id']."' href='#' data-original-title='Ubah'><i class='fa fa-pencil'></i></a></td>";
		$td .= "</tr>";	
			

		}
		$records[] 				= array();
		$records['numberjournal'] = $numberjournal;
		$records['id'] = $id;		
			$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    } <-|->'.$td;
		return $result;
	}
}




?>




