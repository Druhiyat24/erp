<?php 
session_start();


$data = $_POST;
//print_r($data);
$getListData = new getListData();
$List = $getListData->get($_POST['no_list'],$_POST['po'],$_POST['cari']);
print_r($List);

class getListData {
	public function get($no_list,$po,$cari){
		//echo $no_list;
		if($cari == "PO"){

$q = "SELECT X.*,
		if(X.ppn ='' OR X.ppn = '0' OR X.ppn IS NULL,X.netprice,X.netprice + ((X.ppn/100)*X.netprice) )netprice_after_ppn
		FROM(
SELECT AP.v_nojournal
	,AP.v_listcode
	,AP.v_status
	,'' id_journal
	,'' reff_doc_d
	,'' row_id
	,'' id_bpb
	,(MI.mattype)mattype
	,(MI.id_item)id_item
	,(MI.matclass)matclass
	,(MI.itemdesc)itemdesc
	,(MI.color)color
	,(MI.size)size
	,(POI.qty)qty
	,(POI.price)price
	,(POH.podate)podate
	,(POH.pono)pono
	,(POI.curr)curr
	,(POH.id)id_poi
	,(POH.ppn)ppn
	,'' bpbnoint
	,ifnull(POI.qty*POI.price,0) netprice
	FROM fin_status_journal_ap AP
	LEFT JOIN 
		po_header POH
	ON AP.v_nojournal = POH.pono
	LEFT JOIN 
		po_item POI
	ON POH.id = POI.id_po
	LEFT JOIN
		masteritem MI
	ON POI.id_gen = MI.id_item
	WHERE AP.v_listcode = '$no_list' AND AP.v_nojournal = '$po' AND MI.itemdesc != '')X 
	UNION ALL
SELECT X.*,
		if(X.ppn ='' OR X.ppn = '0' OR X.ppn IS NULL,X.netprice,X.netprice + ((X.ppn/100)*X.netprice) )netprice_after_ppn FROM(
SELECT AP.v_nojournal
	,AP.v_listcode
	,AP.v_status
	,JH.id_journal
	,JD.reff_doc_d
	,JD.row_id
	,BPB.id id_bpb
	,ifnull(MI.mattype,MI_WIP.mattype)mattype
	,ifnull(MI.id_item,MI_WIP.id_item)id_item
	,ifnull(MI.matclass,MI_WIP.matclass)matclass
	,ifnull(MI.itemdesc,MI_WIP.itemdesc)itemdesc
	,ifnull(MI.color,MI_WIP.color)color
	,ifnull(MI.size,MI_WIP.size)size
	,ifnull(POI.qty,POI_WIP.qty)qty
	,ifnull(POI.price,POI_WIP.price)price
	,ifnull(POH.podate,POH_WIP.podate)podate
	,ifnull(POH.pono,POH_WIP.pono)pono
	,ifnull(POI.curr,POI_WIP.curr)curr
	,ifnull(POH.id,POH_WIP.id)id_poi
	,ifnull(POH.ppn,POH_WIP.ppn)ppn
	,BPB.bpbno_int
	,if(SUBSTRING(BPB.bpbno_int, 1, 3) = 'WIP',ifnull(BPB.qty*POI_WIP.price,0),ifnull(BPB.qty*BPB.price,0) )netprice
	FROM fin_status_journal_ap AP
	LEFT JOIN 
		fin_journal_h JH
	ON JH.id_journal = AP.v_nojournal
	LEFT JOIN 
		(SELECT id_journal,MAX(reff_doc) reff_doc_d,row_id FROM fin_journal_d WHERE reff_doc IS NOT NULL AND debit > 0  GROUP BY reff_doc)JD
	ON JH.id_journal = JD.id_journal	
LEFT JOIN   
		bpb BPB
			ON BPB.bpbno_int = JH.reff_doc OR BPB.bpbno_int = JD.reff_doc_d
	LEFT JOIN  
		po_header POH
	ON BPB.pono = POH.pono
	LEFT JOIN 
		po_item POI
	ON POH.id = POI.id_po
	LEFT JOIN
		masteritem MI
	ON POI.id_gen = MI.id_gen
	LEFT JOIN po_item POI_WIP ON BPB.id_jo = POI_WIP.id_jo AND BPB.id_item = POI_WIP.id_gen
	LEFT JOIN po_header POH_WIP ON POH_WIP.id = POI_WIP.id_po
	LEFT JOIN(SELECT id, days_pterms,kode_pterms FROM masterpterms )PT_WIP ON POH_WIP.id_terms = PT_WIP.id
	LEFT JOIN
		masteritem MI_WIP
	ON POI_WIP.id_gen = MI_WIP.id_item	
WHERE AP.v_listcode = '$no_list'  AND (POH.pono = '$po' OR POH_WIP.pono = '$po') GROUP BY BPB.id )X
	";			
			
		}
	else if($cari == "KB"){
		$q = "


SELECT X.*,
		if(X.ppn ='' OR X.ppn = '0' OR X.ppn IS NULL,X.netprice,X.netprice + ((X.ppn/100)*X.netprice) )netprice_after_ppn FROM(
SELECT AP.v_nojournal
	,AP.v_listcode
	,AP.v_status
	,JH.id_journal
	,JD.reff_doc_d
	,JD.row_id
	,BPB.id id_bpb
	,ifnull(MI.mattype,MI_WIP.mattype)mattype
	,ifnull(MI.id_item,MI_WIP.id_item)id_item
	,ifnull(MI.matclass,MI_WIP.matclass)matclass
	,ifnull(MI.itemdesc,MI_WIP.itemdesc)itemdesc
	,ifnull(MI.color,MI_WIP.color)color
	,ifnull(MI.size,MI_WIP.size)size
	,ifnull(POI.qty,POI_WIP.qty)qty
	,ifnull(POI.price,POI_WIP.price)price
	,ifnull(POH.podate,POH_WIP.podate)podate
	,ifnull(POH.pono,POH_WIP.pono)pono
	,ifnull(POI.curr,POI_WIP.curr)curr
	,ifnull(POH.id,POH_WIP.id)id_poi
	,ifnull(POH.ppn,POH_WIP.ppn)ppn
	,BPB.bpbno_int
	,if(SUBSTRING(BPB.bpbno_int, 1, 3) = 'WIP',ifnull(BPB.qty*POI_WIP.price,0),ifnull(BPB.qty*BPB.price,0) )netprice
	FROM fin_status_journal_ap AP
	LEFT JOIN 
		fin_journal_h JH
	ON JH.id_journal = AP.v_nojournal
	LEFT JOIN 
		(SELECT id_journal,MAX(reff_doc) reff_doc_d,row_id FROM fin_journal_d WHERE reff_doc IS NOT NULL AND debit > 0  GROUP BY reff_doc)JD
	ON JH.id_journal = JD.id_journal	
LEFT JOIN   
		bpb BPB
			ON BPB.bpbno_int = JH.reff_doc OR BPB.bpbno_int = JD.reff_doc_d
	LEFT JOIN  
		po_header POH
	ON BPB.pono = POH.pono
	LEFT JOIN 
		po_item POI
	ON POH.id = POI.id_po
	LEFT JOIN
		masteritem MI
	ON POI.id_gen = MI.id_gen
	LEFT JOIN po_item POI_WIP ON BPB.id_jo = POI_WIP.id_jo AND BPB.id_item = POI_WIP.id_gen
	LEFT JOIN po_header POH_WIP ON POH_WIP.id = POI_WIP.id_po
	LEFT JOIN(SELECT id, days_pterms,kode_pterms FROM masterpterms )PT_WIP ON POH_WIP.id_terms = PT_WIP.id
	LEFT JOIN
		masteritem MI_WIP
	ON POI_WIP.id_gen = MI_WIP.id_item	
WHERE AP.v_listcode = '$no_list' AND AP.v_nojournal = '$po'  GROUP BY BPB.id )X
";				
	}	
		include __DIR__ .'/../../../include/conn.php';
 

		//echo "$q";
		$stmt = mysqli_query($conn_li,$q);
		$price_nya = 0;			
		if(!$stmt){
	
	$result = '{ "status":"ok", "message":"'.mysqli_error($conn_li).'"}';
	return $result;
		}
		if(!$stmt){
			mysqli_error($conn_li);
			$result = '{ "status":"ok", "message":"'.mysqli_error($conn_li).'","lastDate":"", "records":"" }';
			return $result;
		}
		$outp = '';
		$qty_nya= 0;
		while($row = mysqli_fetch_array($stmt)){ 
		$price_nya = $price_nya + $row["netprice_after_ppn"];
		$qty_nya = $qty_nya + $row["qty"];
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"po":"'.rawurlencode($row['pono']).'",';
			$outp .= '"color":"'. rawurlencode($row["color"]).'",';
			$outp .= '"size":"'. rawurlencode($row["size"]).'",';
			$outp .= '"qty":"'. rawurlencode(number_format($row["qty"],2,',','.')).'",';
			$outp .= '"price":"'. rawurlencode(number_format($row["price"],2,',','.')).'",';
			$outp .= '"podate":"'. rawurlencode($row["podate"]).'",';		
			$outp .= '"netprice":"'. rawurlencode(number_format($row["netprice"],2,',','.')).'",';
			$outp .= '"netprice_after_ppn":"'.rawurlencode($row["curr"]." ".number_format($row["netprice_after_ppn"],2,',','.')).'",';
			$outp .= '"ppn":"'. rawurlencode(number_format($row["ppn"])).'",';
			$outp .= '"curr":"'. rawurlencode($row["curr"]).'",';			
			$outp .= '"item":"'. rawurlencode($row["itemdesc"]).'"}';
		
	}
	$result = '{ "status":"ok", "message":"1", "records":['.$outp.'],"total_price":"'.number_format($price_nya,2,',','.').'" ,"total_qty":"'.number_format($qty_nya,2,',','.').'"   }';
	return $result;
	}
}
?>




