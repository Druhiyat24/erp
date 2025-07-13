<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];

if($data['code'] == '1' ){
	$getListData = new getListData();
$List = $getListData->get($_POST['from'],$_POST['to']);
print_r($List);
}
//else{
//	exit;
//}
class getListData {
	public function get($from,$to){
		//echo $from;
		$explode = explode("/",$from);
		$from = $explode[1]."-".$explode[0]."-01";
		$explode = explode("/",$to);
		$to = $explode[1]."-".$explode[0]."-31";		
//print_r($to);
		include __DIR__ .'/../../../include/conn.php';
		$q = "
SELECT activa.*,jurnal.*,typeaktiva.*,department.nm_cost_dept,subdepartment.nm_cost_sub_dept
	FROM masteractiva activa
		INNER JOIN (SELECT jurnalheader.*,sum(jurnaldetail.credit) credit,sum(jurnaldetail.debit) debit FROM fin_journal_h jurnalheader
			LEFT JOIN (SELECT * FROM fin_journal_d )jurnaldetail
			ON jurnalheader.id_journal = jurnaldetail.id_journal 
                   GROUP BY jurnalheader.id_journal
		) jurnal ON activa.id_journal = jurnal.id_journal
		LEFT JOIN (SELECT 	v_metodepenyusutan,kd_tipe_aktiva,n_pernyusutanbydate,nm_tipe_aktiva,n_monthestimasiumur FROM masteractivatype) typeaktiva 
			ON activa.v_tipeactiva = typeaktiva.kd_tipe_aktiva
        LEFT JOIn (SELECT id_cost_dept,nm_cost_dept FROM mastercostdept)department 
        	ON activa.n_iddept = department.id_cost_dept
        LEFT JOIn (SELECT id_cost_sub_dept,nm_cost_sub_dept FROM mastercostsubdept)subdepartment 
        	ON activa.n_idsupdept = subdepartment.id_cost_sub_dept            
		WHERE  activa.d_tanggalbeli >= '$from' AND activa.d_tanggalbeli <= '$to' AND jurnal.date_post != ''
";
//echo $q;
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		$td = '';
		while($row = mysql_fetch_array($stmt)){
			if ($outp != "") {$outp .= ",";}
	
/*
	    	<th>Kode Aktiva</th>
            <th>Keterangan</th>   
            <th>Tipe Aktiva</th>
            <th>Akun Aktiva</th>
            <th>Biaya Aktiva</th>  
			<th>Tanggal Pakai</th>  
             <th>Tanggal Beli</th>
            <th>Qty</th>
            <th>Umur Bulan Aktiva</th>  
			<th>%Penyusutan/tahun</th>  
			<th>Metode Penyusutan</th>  
			<th>Department</th>  
			<th>Tidak Berwujud</th>  

*/			
			$total = $row['n_qty'] * $row['n_nilai'];
		$td .="<tr>"; 
		$td .="<td align='center'>$row[kd_aktiva]</td>";
		$td .="<td align='center'>$row[v_tipeactiva]</td>";
		$td .="<td align='center'>$row[nm_tipe_aktiva]</td>";		
		$td .="<td align='center'>$row[v_akunactiva]</td>"; 
		$td .="<td align='center'>".number_format($total,2,',','.')."</td>";
		$td .="<td align='center'>".date('d-m-Y', strtotime($row['d_tanggalpakai']))."</td>";
		$td .="<td align='center'>".date('d-m-Y', strtotime($row['d_tanggalbeli']))."</td>";
		$td .="<td align='center'>$row[n_qty]</td>";
		$td .="<td align='center'>$row[n_monthestimasiumur]</td>";
		$td .="<td align='center'>$row[n_pernyusutanbydate]</td>";
		$td .="<td align='center'>$row[v_metodepenyusutan]</td>";
		$td .="<td align='center'>$row[nm_cost_dept]</td>";
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




