<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];
//print_r($data);
//if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($data);
print_r($List);
//}
//else{
//	exit;
//}
class getListData {
	
	
	
	/*
	function getDay($day){
		if($day == "Mon"){
			$hari = "Senin";
		}
		else if($day == "Tue"){
			$hari = "Selasa";
		}
		else if($day == "Wed"){
			$hari = "Rabu";
		}
		else if($day == "Thu"){
			$hari = "Kamis";
		}
		else if($day == "Fri"){
			$hari = "Jumat";
		}
		else if($day == "Sat"){
			$hari = "Sabtu";
		}
		else if($day == "Sun"){
			$hari = "Minggu";
		}		
	}
	*/
	
	protected function getLastDate($module){
		include __DIR__ .'/../../../include/conn.php';
		if($module =='COSTING3' || $module =='COSTING6' || $module =='COSTING8' || $module =='COSTING12' || $module =='HARIAN' ){
			$where = "WHERE v_codecurr IN ('COSTING3','COSTING6','COSTING8','COSTING12','HARIAN')";

		}
		else if($module =='PAJAK'){
			$where = "WHERE v_codecurr IN ('PAJAK')";
			
		}
		else{
			$where = "WHERE v_codecurr IN ('COSTING3','COSTING6','COSTING8','COSTING12','HARIAN')";
			
		}
		$q = "SELECT v_idgroup,id,curr,(max(tanggal) + INTERVAL 1 DAY )tos,min(tanggal)froms, rate,rate_jual,rate_beli,v_codecurr FROM 	masterrate  $where";
		$stmt = mysqli_query($conn_li,$q);
		
$stmt = mysqli_query($conn_li,$q);	
 
		if(mysqli_error($conn_li)){
			$result = '{ "status":"ok", "message":"'.mysqli_error($conn_li).'","lastDate":"", "records":"" }';
			return $result;
		}
		$lastDate  = '';
		while($row = mysqli_fetch_array($stmt)){	
		
			$lastDate = $row['tos'];
		//	echo $lastDate;
		}
		if($lastDate == ''){  
			$lastDate = date("Y-m-d");
		}
		return $lastDate;
	}
	public function get($data){
		$codeCurr = '';
		$codeCurr = $data['content'];
		
		include __DIR__ .'/../../../include/conn.php';
		if($codeCurr =="COSTINGALL" ){
		$q = "SELECT v_idgroup
				,id
				,curr
				,max(tanggal)tos
				,min(tanggal) froms
				,rate
				,rate_jual
				,rate_beli
				,v_codecurr 
				,(if(v_codecurr ='COSTING3','3 Bulan',
					if(v_codecurr ='COSTING6','6 Bulan',
						if(v_codecurr ='COSTING8','8 Bulan',
							if(v_codecurr ='COSTING12','12 Bulan',
								if(v_codecurr = 'HARIAN','Harian','X')
							
							)
						)
					)
				 
				))description
				FROM 
				masterrate 
					GROUP BY v_idgroup HAVING v_codecurr !='PAJAK' ORDER BY 
				froms ASC";			
			
		}
		else{
			if($codeCurr =="COSTING3"){
				$union = " UNION ALL SELECT v_idgroup
							,id,curr
							,tanggal tos
							,tanggal froms
							,rate
							,rate_jual
							,rate_beli
							,v_codecurr 
							,'Y' is_old
							FROM 
							masterrate  
							WHERE v_codecurr = 'COSTING3' AND v_idgroup = 'X' 
							GROUP BY id";
			}else{
				$union = "";
				
			}
			$q = "SELECT v_idgroup,id,curr,max(tanggal)tos,min(tanggal)froms, rate,rate_jual,rate_beli,v_codecurr,'N' is_old FROM masterrate GROUP BY v_idgroup having v_codecurr = '$codeCurr' AND v_idgroup != 'X' $union";
		}
		//echo $q;
		$stmt = mysqli_query($conn_li,$q);	
		if(!$stmt){
			mysqli_error($conn_li);
			$result = '{ "status":"ok", "message":"'.mysqli_error($conn_li).'","lastDate":"", "records":"" }';
			return $result;
		}
		$id = array();
		$outp = '';
		$td = '';
		$tdx = '';
		$rowtd = '';
		$listDay = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
		//print_r($listDay);
		$myLastDate = $this->getLastDate($codeCurr);
		//echo $myLastDate;
		

		
		$x= 0;
		while($row = mysqli_fetch_array($stmt)){
//echo $x."<br/>";			
			if ($outp != "") {$outp .= ",";}
			$outp .= '[{"id":"'.rawurlencode($row['id']).'",';
			$outp .= '"tanggal":"'. rawurlencode($row["tos"]). '",'; 
			$outp .= '"curr":"'. rawurlencode($row["curr"]). '",'; 
			$outp .= '"rate":"'. rawurlencode($row["rate"]). '",'; 
			$outp .= '"ratejual":"'. rawurlencode($row["rate_jual"]). '",'; 
			$outp .= '"lastDate":"'. rawurlencode($myLastDate). '",'; 
			$outp .= '"ratebeli":"'. rawurlencode($row["rate_beli"]). '"}]'; 	
			$no = 1;
			
			if($codeCurr == "COSTINGALL"){
				$tanggal_berjalan =
					$tdx .="<tr>"; 
					$tdx .="<td style='display:none'>$row[v_idgroup]</td>";  
					$tdx .="<td>Start Date:".$row['froms']."</td>";
					$tdx .="<td>".$row['description']." : ".date("d M Y", strtotime($row['froms'])).' - '.date("d M Y", strtotime($row['tos']))."</td>";  
					$tdx .= "</tr>";								
			}
			else{
					$tdx .="<tr>"; 
					$tdx .="<td style='display:none'>$row[v_idgroup]</td>";  
						if($row['is_old'] == 'Y' ){
							$tdx .="<td>".date("d M Y", strtotime($row['froms']))."</td>";
						}else{
							$tdx .="<td>".date("d M Y", strtotime($row['froms'])).' - '.date("d M Y", strtotime($row['tos']))."</td>";
						}
					$tdx .="<td>$row[curr]</td>";  
					$tdx .="<td>$row[rate]</td>"; 
			//		$tdx .="<td>$row[rate_jual]</td>";
		    //		$tdx.="<td>$row[rate_beli]</td>";
					$tdx.="<td><a onClick='edit(this.id)' class='btn btn-primary ' id='".$row['v_idgroup']."' href='#' data-original-title='Ubah'><i class='fa fa-pencil'></i></a> <a onClick='deletes(this.id)' class='btn btn-danger ' id='".$row['v_idgroup']."' href='#' data-original-title='Ubah'><i class='fa fa-trash'></i></a></td>";
					$tdx .= "</tr>";				
			}
			
	
		//$rowtd .= $tdx;	

		$x++;

		}
		$rowtd .= $tdx;	
		if($codeCurr == "COSTINGALL"){
		$td .="<table id='MasterKursPajak' class='display responsive' style='width:100%;font-size:12px;'>";
		$td .="<thead>                                                                                   ";
		$td .="	<tr>                                                                                     ";
		$td .="		<th style='display:none'>Id</th>                                                     ";
		$td .="		<th>Start Date</th>                                                           ";
		$td .="		<th>Periode</th>                                                                     ";
		$td .="	</tr>                                                                                    ";
		$td .="</thead>                                                                                  ";
		$td .="                                                                                          ";
		$td .="<tbody >                                                              ";
		$td .="$rowtd      																				 ";
		$td .="</tbody>                                                                                  ";
		$td .="</table>		                                                                             ";			
			
			
		}
		else{
		//echo $tdx;
		$td .="<table id='MasterKursPajak' class='display responsive' >";
		$td .="<thead>                                                                                   ";
		$td .="	<tr>                                                                                     ";
		$td .="		<th style='display:none'>Id</th>                                                     ";
		$td .="		<!--<th>Dari Tanggal</th>                                                            ";
		$td .="		<th>Sampai Tanggal</th> -->                                                          ";
		$td .="		<th>Periode</th>                                                                     ";
		$td .="		<th>Currency</th>                                                                    ";
		$td .="		<th>Rate</th>                                                                   ";
		//$td .="		<th>Rate Tengah</th>                                                                 ";
		//$td .="		<th>Rate Beli</th>                                                                   ";
		$td .="		<th width='14%'>Action</th>                                                          ";
		$td .="	</tr>                                                                                    ";
		$td .="</thead>                                                                                  ";
		$td .="                                                                                          ";
		$td .="<tbody >                                                              ";
		$td .="$rowtd      																				 ";
		$td .="</tbody>                                                                                  ";
		$td .="</table>		                                                                             ";
		}
	$result = '{ "status":"ok", "message":"1","lastDate":"'.$myLastDate.'", "records":['.$outp.']    } <-|->'.$td;
		return $result;
	}
}




?>




