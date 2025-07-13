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
				a.id
				,a.kpno
				,ms.Supplier
				,a.styleno
				,a.qty
				,a.smv_min
				,a.smv_sec
				,c.buyerno
				,a.deldate
				,mp.nama_pilihan
				,a.username
				FROM act_costing a 
				LEFT JOIN mastersupplier ms ON a.id_buyer=ms.Id_Supplier 
				LEFT JOIN so c ON a.id=c.id_cost
				LEFT JOIN so_det d ON c.id=d.id_so
				LEFT JOIN masterpilihan mp ON d.id=mp.id
				WHERE kode_pilihan='LINE'
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
		$td .="<td>$row[kpno]</td>";
		$td .="<td>$row[Supplier]</td>";  
		$td .="<td>$row[styleno]</td>"; 
		$td .="<td>$row[qty]</td>"; 
		$td .="<td>$row[smv_min]</td>";
		$td .="<td>$row[smv_sec]</td>"; 
		$td .="<td>$row[deldate]</td>";
		$td .="<td>$row[nama_pilihan]</td>";
		$td .="<td>$row[username]</td>";

		$td .="<td><a onClick='edit(this.id)' class='btn btn-xs ' id='".$row['id']."' href='#' data-original-title='Ubah'><i class='fa fa-pencil'></i></a> <a onClick='deletes(this.id)' class='btn btn-sm ' id='".$row['id']."' href='#' data-original-title='Ubah'><i class='fa fa-trash'></i></a></td>";
		$td .= "</tr>";	
			

		}

		$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    } <-|->'.$td;

		return $result;
	}
}




?>




