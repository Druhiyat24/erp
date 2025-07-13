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
		$q = "SELECT 
			a.buyerno
			,b.qty
			,c.deldate
			,mp.nama_pilihan
			 FROM masterpilihan mp
			 LEFT JOIN so a ON mp.id=a.id
			 LEFT JOIN so_det b ON a.id=b.id_so
			 LEFT JOIN act_costing c ON b.id_so=c.id
			 -- where kpno='DGR/1218/00001'
			 WHERE kode_pilihan='Line'
			 group by nama_pilihan
				";
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		$td = '';
		$no = 0;
		
		while($row = mysql_fetch_array($stmt)){
		$no++;	

			/*if ($outp != "") {$outp .= ",";}
			$outp .= '[{"id":"'.rawurlencode($row['id']).'",';
			$outp .= '"tanggal":"'. rawurlencode($row["tanggal"]). '",'; 
			$outp .= '"curr":"'. rawurlencode($row["curr"]). '",'; 
			$outp .= '"rate":"'. rawurlencode($row["rate"]). '",'; 
			$outp .= '"ratejual":"'. rawurlencode($row["rate_jual"]). '",'; 
			$outp .= '"ratebeli":"'. rawurlencode($row["rate_beli"]). '"}]'; 	
			*/
			
		$td .="<tr>"; 
		// $td .="<td style='display:none'>$no</td>";  
		$td .="<td>$row[buyerno]</td>";
		$td .="<td>$row[qty]</td>";  
		$td .="<td>$row[deldate]</td>"; 
		$td .="<td>$row[nama_pilihan]</td>"; 

		// $td .="<td><a onClick='edit(this.id)' class='btn btn-xs ' id='".$row['id']."' href='#' data-original-title='Ubah'><i class='fa fa-pencil'></i></a> <a onClick='deletes(this.id)' class='btn btn-sm ' id='".$row['id']."' href='#' data-original-title='Ubah'><i class='fa fa-trash'></i></a></td>";
		$td .= "</tr>";	
			

		}

		$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    } <-|->'.$td;

		return $result;
	}
}




?>




