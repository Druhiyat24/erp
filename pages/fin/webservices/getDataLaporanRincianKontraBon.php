<?php 
	function d_from($from){
		$d_from = explode("/",$from."/01");
		$d_from = $d_from[1]."-".$d_from[0]."-".$d_from[2];
		return $d_from;
	}
	function d_to($to){
		$d_to = explode("/",$to."/01");
		$d_to = $d_to[1]."-".$d_to[0]."-".$d_to[2];
		$d_to = date("Y-m-d", strtotime("1 month",strtotime($d_to)));
		$d_to = date("Y-m-d", strtotime("-1 days",strtotime($d_to)));
		return $d_to;
	}

include __DIR__ .'/../../../include/conn.php';
## Read value
$data = $_GET;
$d_from = $d_from =date("Y-m-d", strtotime($data['from']));
$d_to = $d_to =date("Y-m-d", strtotime($data['to']));


$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(

SELECT   
		POP_SUP.id_coa
		,MS.supplier_code
		,MS.Supplier
		,ifnull(if(MS.short_name = '','-',MS.short_name),'-')short_name
		,MP.nama_pterms
		,FH.period
		,FH.date_journal
		,POP_SUP.id_journal
		,ifnull(if(FH.d_invoice = '1970-01-01','N/A',FH.d_invoice),'-')d_invoice_INV
		,ifnull(if(FH.inv_supplier = '','-',FH.inv_supplier),'-')inv_supplier_INV
		,ifnull(if(FH.d_invoice = '1970-01-01','N/A',FH.d_invoice),'-')d_invoice_SJ
		,ifnull(if(FH.inv_supplier = '','-',FH.inv_supplier),'-')inv_supplier_SJ
		,if(KB.curr ='USD',(KB.amount*ifnull(MR.rate,0)),KB.amount)total_rupiah
		,POP_SUP.description
		,KB.curr
		,MR.rate
		,KB.amount
		,POP_SUP.id_po
		,FH.fg_post

		
		FROM (
	SELECT   A.id_journal
			,A.id_coa
			,A.nm_coa
			,MAX(A.id_supplier)id_supplier
			,MAX(A.id_po)id_po
			,MAX(A.description)description
			FROM fin_journal_d A
		LEFT JOIN mastercoa MC ON A.id_coa = MC.id_coa	
		
		WHERE 1=1 AND A.id_journal LIKE '%PK%'
		AND A.credit > 0 AND A.id_coa IN(SELECT id_coa FROM mapping_utang WHERE 1=1)
	GROUP BY A.id_journal
	ORDER BY A.id_supplier,A.id_coa ASC)POP_SUP
	
	LEFT JOIN(
	SELECT     
			MASTER.id_journal
			,MAX(MASTER.curr)curr
			,MAX(MASTER.id_po)id_po
			,MAX(MASTER.id_bpb)id_bpb
			,MAX(MASTER.id_bppb)id_bppb
			,MAX(MASTER.id_item)id_item	
			,MAX(MASTER.id_supplier)id_supplier
			,SUM(MASTER.amount)amount
			,SUM(MASTER.ppn)ppn
			,SUM(MASTER.pph)pph
			,SUM(MASTER.nilai)nilai
			FROM ( 
				SELECT
					M.id_journal 
					,M.date_journal
					,MAX(M.curr)curr
					,MAX(M.id_po)id_po
					,MAX(M.id_bpb)id_bpb
					,MAX(M.id_bppb)id_bppb
					,MAX(M.id_item)id_item	
					,MAX(M.id_supplier)id_supplier
					,SUM(M.nilai)nilai
					,(SUM(IFNULL(M.amount_original,M.nilai)) + SUM(M.val_ppn) - SUM(M.val_pph) )amount
					,SUM(M.val_ppn)ppn
					,SUM(M.val_pph)pph
					FROM( 
							SELECT ifnull(KB.amount_original,KB.credit) nilai
							,(ifnull(KB.amount_original,0) * (ifnull(KB.n_ppn,0)/100))val_ppn
							,(ifnull(KB.amount_original,0) * (IFNULL(IFNULL(MT_H.percentage,MT.percentage),0)/100))val_pph
							,FH.date_journal
							,KB.* FROM fin_journal_d KB

							LEFT JOIN mtax MT ON MT.idtax = KB.n_pph
							LEFT JOIN(SELECT id_journal,n_pph,date_journal FROM fin_journal_h WHERE type_journal = '14')FH ON KB.id_journal=FH.id_journal
							LEFT JOIN mtax MT_H ON MT_H.idtax = FH.n_pph
								WHERE 1=1 AND  KB.credit > 0 AND id_coa NOT IN ('15204','15207') AND id_coa IN(SELECT id_coa FROM mapping_utang WHERE 1=1)
								GROUP BY KB.id_journal,KB.row_id
					)M GROUP BY M.id_journal 
			
					UNION ALL
			
					SELECT
						M.id_journal 
						,M.date_journal
						,MAX(M.curr)curr
						,MAX(M.id_po)id_po
						,MAX(M.id_bpb)id_bpb
						,MAX(M.id_bppb)id_bppb
						,MAX(M.id_item)id_item	
						,MAX(M.id_supplier)id_supplier
						,SUM(M.nilai)nilai
						,( ( SUM(IFNULL(M.amount_original,M.nilai)) + SUM(M.val_ppn) - SUM(M.val_pph) ) *-1 )amount
						, ( SUM(M.val_ppn) *-1 )ppn
						, ( SUM(M.val_pph) *-1 )pph
						FROM( 
								SELECT ifnull(KB.amount_original,KB.debit) nilai
								,(ifnull(KB.amount_original,0) * (ifnull(KB.n_ppn,0)/100))val_ppn
								,(ifnull(KB.amount_original,0) * (IFNULL(IFNULL(MT_H.percentage,MT.percentage),0)/100))val_pph
								,FH.date_journal
								,KB.* FROM fin_journal_d KB

								LEFT JOIN mtax MT ON MT.idtax = KB.n_pph
								LEFT JOIN(SELECT id_journal,n_pph,date_journal FROM fin_journal_h WHERE type_journal = '14')FH ON KB.id_journal=FH.id_journal
								LEFT JOIN mtax MT_H ON MT_H.idtax = FH.n_pph
							WHERE 1=1 AND  KB.debit > 0 AND id_coa NOT IN ('15204','15207') AND id_coa IN(SELECT id_coa FROM mapping_utang WHERE 1=1)
							GROUP BY KB.id_journal,KB.row_id
					)M GROUP BY M.id_journal 
					
					

					UNION ALL
					/* ROW UNTUK COA YG TIDAK LINK */
				SELECT
					M.id_journal 
					,M.date_journal
					,MAX(M.curr)curr
					,MAX(M.id_po)id_po
					,MAX(M.id_bpb)id_bpb
					,MAX(M.id_bppb)id_bppb
					,MAX(M.id_item)id_item	
					,MAX(M.id_supplier)id_supplier
					,SUM(M.nilai)nilai
					,(SUM(IFNULL(M.amount_original,M.nilai)) + SUM(M.val_ppn) - SUM(M.val_pph) )amount
					,SUM(M.val_ppn)ppn
					,SUM(M.val_pph)pph
					FROM( 
							SELECT ifnull(KB.amount_original,KB.credit) nilai
							,(ifnull(KB.amount_original,0) * (ifnull(KB.n_ppn,0)/100))val_ppn
							,(ifnull(KB.amount_original,0) * (IFNULL(IFNULL(MT_H.percentage,MT.percentage),0)/100))val_pph
							,FH.date_journal
							,KB.* FROM fin_journal_d KB
							LEFT JOIN mtax MT ON MT.idtax = KB.n_pph
							LEFT JOIN(SELECT id_journal,n_pph,date_journal FROM fin_journal_h WHERE type_journal = '14')FH ON KB.id_journal=FH.id_journal
							LEFT JOIN mtax MT_H ON MT_H.idtax = FH.n_pph
								WHERE 1=1 AND  KB.credit > 0 AND id_coa NOT IN ('15204','15207')  AND type_bpb IN ('1','2') AND id_coa = ''
								GROUP BY KB.id_journal,KB.row_id
					)M GROUP BY M.id_journal 	
				)MASTER 		
					WHERE MASTER.amount IS NOT NULL AND MASTER.id_journal LIKE '%PK%' GROUP BY MASTER.id_journal
	)KB ON POP_SUP.id_journal = KB.id_journal
	
	LEFT JOIN fin_journal_h FH ON FH.id_journal = POP_SUP.id_journal
	LEFT JOIN (SELECT * FROM masterrate WHERE v_codecurr = 'PAJAK')MR ON MR.tanggal = FH.date_journal
	LEFT JOIN mastersupplier MS ON POP_SUP.id_supplier = MS.Id_Supplier
	LEFT JOIN po_header POH ON POH.id = POP_SUP.id_supplier
	LEFT JOIN masterpterms MP ON MP.id = POH.id_terms
	WHERE  
	FH.fg_post='2' 
	#1=1 FH
	AND FH.type_journal='14' 
	AND POP_SUP.id_supplier IS NOT NULL 
	AND FH.date_journal >= '$d_from' 
	and FH.date_journal <= '$d_to'
	GROUP BY POP_SUP.id_supplier,POP_SUP.id_journal
	ORDER BY MS.Supplier ASC

#            WHERE fjh.type_journal = '14' AND fjh.fg_post = '2' AND ms.tipe_sup='S' AND 
 #           fjh.date_journal 
  #          >= '$d_from' 
   #         and fjh.date_journal 
    #        <= '$d_to'         

         

)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	X.id_coa                   	    LIKE'%".$searchValue."%'
	OR X.supplier_code              LIKE'%".$searchValue."%'
	OR X.Supplier                   LIKE'%".$searchValue."%'
	OR X.short_name                   LIKE'%".$searchValue."%'
	OR X.nama_pterms                    LIKE'%".$searchValue."%'
	OR X.period                LIKE'%".$searchValue."%'
	OR X.date_journal                      LIKE'%".$searchValue."%'
	OR X.id_journal                     LIKE'%".$searchValue."%'
	OR X.d_invoice_INV                   LIKE'%".$searchValue."%'
	OR X.inv_supplier_INV			    LIKE'%".$searchValue."%'
	OR X.d_invoice_SJ                   LIKE'%".$searchValue."%'
	OR X.inv_supplier_SJ			    LIKE'%".$searchValue."%'
	OR X.total_rupiah                   LIKE'%".$searchValue."%'
	OR X.description                LIKE'%".$searchValue."%'
)
";
}

## Total number of records without filtering
$sel = mysqli_query($conn_li,"select count(*) allcount from $table");
$records = mysqli_fetch_assoc($sel); 

$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($conn_li,"select count(*)  allcount from $table WHERE 1 ".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records
$colomn = "		 
			 X.id_coa                   
			,X.supplier_code            	
			,X.Supplier                 	
			,X.short_name                  	
			,X.nama_pterms              	
			,X.period                    	
			,X.date_journal                    	
			,X.id_journal                 	
			,X.d_invoice_INV           	
			,X.inv_supplier_INV 
			,X.d_invoice_SJ              	
			,X.inv_supplier_SJ           	               	
			,X.total_rupiah                	
			,X.description                    	 
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by X.Supplier,X.date_journal  limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];


   $data[] = array(
"id_coa"=>htmlspecialchars($row['id_coa']),               
"supplier_code"=>htmlspecialchars($row['supplier_code']),
"Supplier"=>htmlspecialchars($row['Supplier']),
"short_name"=>htmlspecialchars($row['short_name']),
"nama_pterms"=>htmlspecialchars($row['nama_pterms']),
"period"=>htmlspecialchars($row['period']),
"date_journal"=>htmlspecialchars($row['date_journal']),
"id_journal"=>htmlspecialchars($row['id_journal']),
"d_invoice_INV"=>htmlspecialchars($row['d_invoice_INV']),
"inv_supplier_INV"=>htmlspecialchars($row['inv_supplier_INV']),
"d_invoice_SJ"=>htmlspecialchars($row['d_invoice_SJ']),
"inv_supplier_SJ"=>htmlspecialchars($row['inv_supplier_SJ']),
"total_rupiah"=>htmlspecialchars(number_format((float)$row['total_rupiah'], 2, '.', ',')),
"description"=>htmlspecialchars($row['description']),
   );
}
## Response
$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecordwithFilter,
  "iTotalDisplayRecords" => $totalRecords,
  "aaData" => $data
);
echo json_encode($response);
?>