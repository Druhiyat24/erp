<?php 
ini_set('max_execution_time', '300');
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
		$q = "SELECT AP.v_nojournal
	,AP.v_status
	,MI.mattype
	,MI.matclass
	,MI.itemdesc
	,MI.color
	,MI.size
	,POI.qty
	,POI.price
	,POH.podate
	,POH.pono
	,POI.curr
	,POI.id
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
	ON POI.id_gen = MI.id_gen
	WHERE AP.v_listcode = '$no_list' AND AP.v_nojournal = '$po' AND MI.itemdesc != ''
	
	UNION ALL
SELECT AP.v_nojournal
	,AP.v_status
	,MI.mattype
	,MI.matclass
	,MI.itemdesc
	,MI.color
	,MI.size
	,POI.qty
	,POI.price
	,POH.podate
	,POH.pono
	,POI.curr
	,POI.id
	,ifnull(POI.qty*POI.price,0) netprice
	FROM fin_status_journal_ap AP
	LEFT JOIN fin_journal_h JH
		ON JH.id_journal = AP.v_nojournal
	LEFT JOIN (
		SELECT id_journal,reff_doc reff_doc_d,row_id FROM fin_journal_d WHERE reff_doc IS NOT NULL AND debit > 0 GROUP BY id_journal
	)JD ON JD.id_journal = AP.v_nojournal
	LEFT JOIN
		 bpb BPB
	ON BPB.bpbno_int = JD.reff_doc_d OR BPB.bpbno_int = JH.reff_doc
	LEFT JOIN 
		po_header POH
	ON BPB.pono = POH.pono
	LEFT JOIN 
		po_item POI
	ON POH.id = POI.id_po
	LEFT JOIN
		masteritem MI
	ON POI.id_gen = MI.id_gen
	WHERE AP.v_listcode = '$no_list' AND POH.pono = '$po' AND MI.itemdesc != '' GROUP BY POI.id
	

";			
			
		}
	else if($cari == "KB"){
		$q = "

SELECT AP.v_nojournal
	,AP.v_status
	,MI.mattype
	,MI.id_item
	,MI.matclass
	,MI.itemdesc
	,MI.color
	,MI.size
	,POI.qty
	,POI.price
	,POH.podate
	,POH.pono
	,POI.curr
	,POI.id
	,BPB.bpbno_int
	,ifnull(POI.qty*POI.price,0) netprice
	FROM fin_status_journal_ap AP
	LEFT JOIN 
		fin_journal_h JH
	ON JH.id_journal = AP.v_nojournal
	LEFT JOIN 
		(SELECT id_journal,reff_doc reff_doc_d,row_id FROM fin_journal_d WHERE reff_doc IS NOT NULL AND debit > 0 GROUP BY id_journal)JD
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
	ON POI.id_gen = MI.id_item
WHERE AP.v_listcode = '$no_list' AND AP.v_nojournal = '$po'  AND MI.itemdesc != '' GROUP BY POI.id
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
		while($row = mysqli_fetch_array($stmt)){ 
		$price_nya = $price_nya + $row["netprice"];
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"po":"'.rawurlencode($row['pono']).'",';
			$outp .= '"color":"'. rawurlencode($row["color"]).'",';
			$outp .= '"size":"'. rawurlencode($row["size"]).'",';
			$outp .= '"qty":"'. rawurlencode(number_format($row["qty"])).'",';
			$outp .= '"price":"'. rawurlencode(number_format($row["price"])).'",';
			$outp .= '"podate":"'. rawurlencode($row["podate"]).'",';		
			$outp .= '"netprice":"'. rawurlencode(number_format($row["netprice"])).'",';
			$outp .= '"curr":"'. rawurlencode($row["curr"]).'",';			
			$outp .= '"item":"'. rawurlencode($row["itemdesc"]).'"}';
		
	}
	$result = '{ "status":"ok", "message":"1", "records":['.$outp.'],"total_price":"'.number_format($price_nya).'"    }';
	return $result;
	}
}
?>




