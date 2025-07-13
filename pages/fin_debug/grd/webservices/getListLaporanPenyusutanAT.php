<?php 
		$data = $_POST;
//$data = (object)$_POST['data'];

if($data['code'] == '1' ){
	$getListData = new getListData();
	$List = $getListData->get($_POST['from'],$_POST['to'], $_POST['kd_tipe_activa']);
	print_r($List);
}
//else{
//	exit;
//}
class getListData {
	public function get($from,$to, $kd_tipe_activa){
		//echo $from;
		$explode = explode("/",$from);
		$from = $explode[1]."-".$explode[0]."-01";
		$explode = explode("/",$to);
		$to = $explode[1]."-".$explode[0]."-31";

        $kd_tipe_activa = ($kd_tipe_activa == '') ? '%' : $kd_tipe_activa;

//print_r($to);
		include __DIR__ .'/../../../include/conn.php';
		$q = "
		select 
		m.tipe_item
		,mat.nm_tipe_aktiva gol_at
		,left(m.goods_code,3) kode_at
		,m.itemdesc nama_aktiva
		,concat(m.`size`,' ',m.color,' ',m.brand) spesifikasi
		,bpb.bpbdate tgl_perolehan
		,sum(bpb.qty) jumlah_unit 
		,bpb.bpbno_int ref_dokumen
		,mat.n_pernyusutan_perbulanbydate tarif_penyusutan
		,SUM(poi.price ) nilai_perolehan
		,SUM(bpb.qty * poi.price ) nilai_perolehan_total
		,SUM(mat.n_pernyusutan_perbulanbydate * poi.price / 100) biaya_penyusutan
		,SUM(mat.n_pernyusutan_perbulanbydate * bpb.qty * poi.price / 100) biaya_penyusutan_total
	from masteritem m
	inner join masteractivatype mat on left(m.goods_code,3) = mat.kd_tipe_aktiva 
	left join bpb on m.id_item = bpb.id_item 
	left join po_item poi on m.id_item = poi.id_gen 
	inner join po_header poh on poi.id_po = poh.id 
	where 1=1
		-- and m.tipe_item = 'ASSET'
		and bpb.bpbdate >= '$from'
		and bpb.bpbdate <= '$to'
		and mat.kd_tipe_aktiva LIKE '$kd_tipe_activa'
	group by 
		mat.nm_tipe_aktiva 
		,left(m.goods_code,3) 
		,m.itemdesc
		,concat(m.`size`,' ',m.color,' ',m.brand) 
		,bpb.bpbdate 
		,bpb.bpbno_int 
		,mat.n_pernyusutan_perbulanbydate 
	order by 
		mat.nm_tipe_aktiva
		,m.itemdesc
		,bpb.bpbdate 
--	limit 0,20
";

// echo $q;
		$stmt = mysql_query($q);		
		$numberjournal = array();
		$id = array();
		$outp = '';
		$td = '';
		$no = 0;
		
		while($row = mysql_fetch_array($stmt)){
			$tgl_perolehan = date_create($row['tgl_perolehan']);
			$tgl_sekarang = date_create(date('Y-m-d', time()));
	
			$interval = date_diff($tgl_perolehan, $tgl_sekarang);
			$periode = $interval->format('%m');
			$akumulasi_penyusutan = $row['biaya_penyusutan_total'] * $periode;
			$nilai_buku = $row['nilai_perolehan_total'] - $akumulasi_penyusutan;
			if($nilai_buku <= 0){
				$nilai_buku = 0;
			}
/*
			<th>NO</th>
			<th>GOL AT</th>
			<th>KODE AT</th>
			<th>NAMA AT</th>
			<th>SPESIFIKASI</th>   
			<th>TGL PEROLEHAN</th>
			<th>JUMLAH UNIT</th>  
			<th>REF DOKUMEN</th>  
			<th>TARIF PENYUSUTAN</th>
			<th>NILAI PEROLEHAN</th>
			<th>BIAYA PENYUSUTAN</th>  
			<th>AKUMULASI PENYUSUTAN</th>  
			<th>NILAI BUKU</th>  
*/			
			$no++;
			$td .="<tr>"; 
			$td .="<td align='right'>{$no}</td>";
			$td .="<td align='left'>$row[gol_at]</td>";
			$td .="<td align='left'>$row[kode_at]</td>";
			$td .="<td align='left'>$row[nama_aktiva]</td>";		
			$td .="<td align='left'>$row[spesifikasi]</td>"; 
			$td .="<td align='center'>".date('d-m-Y', strtotime($row['tgl_perolehan']))."</td>";
			$td .="<td align='right'>$row[jumlah_unit]</td>"; 
			$td .="<td align='left'>$row[ref_dokumen]</td>"; 
			$td .="<td align='right'>".number_format($row['tarif_penyusutan']*12)."%</td>";
			$td .="<td align='right'>".number_format($row['nilai_perolehan_total'])."</td>"; 
			$td .="<td align='right'>".number_format($row['biaya_penyusutan_total'])."</td>"; 
			$td .="<td align='right'>".number_format($akumulasi_penyusutan)."</td>"; 
			$td .="<td align='right'>".number_format($nilai_buku)."</td>"; 
			$td .= "</tr>";	
		}
		
		$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    } <-|->'.$td;
		return $result;
	}
}




?>




