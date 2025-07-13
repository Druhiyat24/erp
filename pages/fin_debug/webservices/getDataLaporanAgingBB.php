<?php
ini_set('memory_limit','2048M');
//ini_set('max_execution_time', '300'); //300 seconds = 5 minutes 
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
/*
print_r($_POST);
die();  */
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page

$searchValue = $_POST['search']['value']; // Search value
$table = "(
SELECT
 			Y.goods_code
 			,Y.itemdesc
 			,Y.no_ws
 			,Y.id_order
 			,Y.id_item
 			,Y.id_jo
 			,IFNULL(IF(Y.supplier_code='','N/A',Y.supplier_code),'N/A')supplier_code
			,Y.Supplier
			,Y.populasi_bpb_tgl no_dokumen
			,(Y.qty_po)qty_po
			,(Y.qty_masuk)qty_masuk
			,(Y.qty_keluar)keluar_qty
			,ROUND(Y.qty_masuk-Y.qty_keluar,2)qty_akhir
			,(Y.qty_po*Y.price)harga_qty_po
			,(Y.qty_masuk*Y.price)harga_masuk_qty
			,(Y.qty_keluar*Y.price)harga_keluar_qty
			,(Y.qty_akhir*Y.price)harga_qty_akhir
			,Y.u_awal
			,Y.u_masuk
			,Y.u_keluar
			,Y.u_akhir
			,(Y.qty_tkeluar_umur_0_30       )qty_umur_0_30
			,(Y.qty_tkeluar_umur_31_60      )qty_umur_31_60
			,(Y.qty_tkeluar_umur_61_90      )qty_umur_61_90
			,(Y.qty_tkeluar_umur_91_120     )qty_umur_91_120
			,(Y.qty_tkeluar_umur_121_150    )qty_umur_121_150
			,(Y.qty_tkeluar_umur_151_180    )qty_umur_151_180
			,(Y.qty_tkeluar_umur_181_360	   )qty_umur_181_360	
			,(Y.qty_tkeluar_umur_361        )qty_umur_361
			,Y.q_total
			,(Y.price_tkeluar_umur_0_30     )price_umur_0_30
			,(Y.price_tkeluar_umur_31_60    )price_umur_31_60
			,(Y.price_tkeluar_umur_61_90    )price_umur_61_90
			,(Y.price_tkeluar_umur_91_120   )price_umur_91_120
			,(Y.price_tkeluar_umur_121_150  )price_umur_121_150
			,(Y.price_tkeluar_umur_151_180  )price_umur_151_180
			,(Y.price_tkeluar_umur_181_360  )price_umur_181_360
			,(Y.price_tkeluar_umur_361      )price_umur_361
			,Y.r_total
			,Y.bpbno_int
			,Y.bppbno_int
			,Y.bpbdate
			,Y.bppbdate
			,Y.id
					FROM 	(

SELECT 
		 S.bpbdate
		,S.goods_code
		,S.itemdesc
		,S.no_ws
		,S.id_order
		,S.supplier_code
		,S.Supplier
		,CONCAT(S.bpbno_int,' (',S.bpbdate,')') AS populasi_bpb_tgl
		,(S.qty_po)qty_po
		,(S.qtym)qty_masuk
		,(S.qtyk)qty_keluar
		,(S.qtym-S.qtyk)qty_akhir
		,S.price
		,(S.qty_po*S.price)harga_qty_po
		,(S.qtym*S.price)harga_qty_masuk
		,(S.qtyk*S.price)harga_qty_keluar
		,(S.qtym-S.qtyk)harga_qty_akhir
		,S.u_awal
		,S.u_masuk
		,S.u_keluar
		,S.u_akhir
		,(S.qty_tkeluar_umur_0_30       )qty_tkeluar_umur_0_30
		,(S.qty_tkeluar_umur_31_60      )qty_tkeluar_umur_31_60
		,(S.qty_tkeluar_umur_61_90      )qty_tkeluar_umur_61_90
		,(S.qty_tkeluar_umur_91_120     )qty_tkeluar_umur_91_120
		,(S.qty_tkeluar_umur_121_150    )qty_tkeluar_umur_121_150
		,(S.qty_tkeluar_umur_151_180    )qty_tkeluar_umur_151_180
		,(S.qty_tkeluar_umur_181_360	   )qty_tkeluar_umur_181_360		
		,(S.qty_tkeluar_umur_361        )qty_tkeluar_umur_361
		,S.q_total
		,(S.price_tkeluar_umur_0_30     )price_tkeluar_umur_0_30
		,(S.price_tkeluar_umur_31_60    )price_tkeluar_umur_31_60
		,(S.price_tkeluar_umur_61_90    )price_tkeluar_umur_61_90
		,(S.price_tkeluar_umur_91_120   )price_tkeluar_umur_91_120
		,(S.price_tkeluar_umur_121_150  )price_tkeluar_umur_121_150
		,(S.price_tkeluar_umur_151_180  )price_tkeluar_umur_151_180
		,(S.price_tkeluar_umur_181_360  )price_tkeluar_umur_181_360
		,(S.price_tkeluar_umur_361      )price_tkeluar_umur_361
		,S.r_total
		,S.id_supplier
		,S.id
		,S.id_jo
		,S.id_item
		,S.pono
		,S.podate
		,S.bpbno_int
		,S.bppbno_int
		,S.bppbdate
			FROM(		
SELECT 
		 BM.bpbdate
		,MI.goods_code
		,MI.itemdesc
		,ACT.no_ws
		,'N/A' id_order
		,MS.supplier_code
		,MS.Supplier
		,ROUND(POI.qty,2)qty_po
		,BM.qtym
		,BK.qtyk
		,IF(BK.bppbdate>'{$d_from}',BM.qtym,(BM.qtym-BK.qtyk))qty_akhir
		,'0'u_awal
		,'0'u_masuk
		,'0'u_keluar
		,'0'u_akhir
		,IF(DATEDIFF('{$d_from}', BM.bpbdate) >=0 AND DATEDIFF('{$d_from}', BM.bpbdate) <=30, IF(BK.bppbdate>'{$d_from}',BM.qtym,(BM.qtym-BK.qtyk)),0) qty_tkeluar_umur_0_30
		,IF(DATEDIFF('{$d_from}', BM.bpbdate) >=31 AND DATEDIFF('{$d_from}', BM.bpbdate) <=60, IF(BK.bppbdate>'{$d_from}',BM.qtym,(BM.qtym-BK.qtyk)),0 ) qty_tkeluar_umur_31_60
		,IF(DATEDIFF('{$d_from}', BM.bpbdate) >=61 AND DATEDIFF('{$d_from}', BM.bpbdate) <=90, IF(BK.bppbdate>'{$d_from}',BM.qtym,(BM.qtym-BK.qtyk)),0 ) qty_tkeluar_umur_61_90
		,IF(DATEDIFF('{$d_from}', BM.bpbdate) >=91 AND DATEDIFF('{$d_from}', BM.bpbdate) <=120,IF(BK.bppbdate>'{$d_from}',BM.qtym,(BM.qtym-BK.qtyk)),0 ) qty_tkeluar_umur_91_120
		,IF(DATEDIFF('{$d_from}', BM.bpbdate) >=121 AND DATEDIFF('{$d_from}',BM.bpbdate) <=150,IF(BK.bppbdate>'{$d_from}',BM.qtym,(BM.qtym-BK.qtyk)),0 ) qty_tkeluar_umur_121_150
		,IF(DATEDIFF('{$d_from}', BM.bpbdate) >=151 AND DATEDIFF('{$d_from}',BM.bpbdate) <=180,IF(BK.bppbdate>'{$d_from}',BM.qtym,(BM.qtym-BK.qtyk)),0 ) qty_tkeluar_umur_151_180
		,IF(DATEDIFF('{$d_from}', BM.bpbdate) >=181 AND DATEDIFF('{$d_from}',BM.bpbdate) <=360,IF(BK.bppbdate>'{$d_from}',BM.qtym,(BM.qtym-BK.qtyk)),0 ) qty_tkeluar_umur_181_360		
		,IF(DATEDIFF('{$d_from}', BM.bpbdate) >=361,IF(BK.bppbdate>'{$d_from}',BM.qtym,(BM.qtym-BK.qtyk)),0 ) qty_tkeluar_umur_361
		,''q_total
		,IF(DATEDIFF('{$d_from}', BM.bpbdate) >=0 AND DATEDIFF('{$d_from}',  BM.bpbdate) <=30, (IF(BK.bppbdate>'{$d_from}',BM.qtym,(BM.qtym-BK.qtyk))*BM.price),0 ) price_tkeluar_umur_0_30
		,IF(DATEDIFF('{$d_from}', BM.bpbdate) >=31 AND DATEDIFF('{$d_from}', BM.bpbdate) <=60, (IF(BK.bppbdate>'{$d_from}',BM.qtym,(BM.qtym-BK.qtyk))*BM.price),0 ) price_tkeluar_umur_31_60
		,IF(DATEDIFF('{$d_from}', BM.bpbdate) >=61 AND DATEDIFF('{$d_from}', BM.bpbdate) <=90, (IF(BK.bppbdate>'{$d_from}',BM.qtym,(BM.qtym-BK.qtyk))*BM.price),0 ) price_tkeluar_umur_61_90
		,IF(DATEDIFF('{$d_from}', BM.bpbdate) >=91 AND DATEDIFF('{$d_from}', BM.bpbdate) <=120,(IF(BK.bppbdate>'{$d_from}',BM.qtym,(BM.qtym-BK.qtyk))*BM.price),0 ) price_tkeluar_umur_91_120
		,IF(DATEDIFF('{$d_from}', BM.bpbdate) >=121 AND DATEDIFF('{$d_from}',BM.bpbdate) <=150,(IF(BK.bppbdate>'{$d_from}',BM.qtym,(BM.qtym-BK.qtyk))*BM.price),0 ) price_tkeluar_umur_121_150
		,IF(DATEDIFF('{$d_from}', BM.bpbdate) >=151 AND DATEDIFF('{$d_from}',BM.bpbdate) <=180,(IF(BK.bppbdate>'{$d_from}',BM.qtym,(BM.qtym-BK.qtyk))*BM.price),0 ) price_tkeluar_umur_151_180
		,IF(DATEDIFF('{$d_from}', BM.bpbdate) >=181 AND DATEDIFF('{$d_from}',BM.bpbdate) <=360,(IF(BK.bppbdate>'{$d_from}',BM.qtym,(BM.qtym-BK.qtyk))*BM.price),0 ) price_tkeluar_umur_181_360	
		,IF(DATEDIFF('{$d_from}', BM.bpbdate) >=361,(IF(BK.bppbdate>'{$d_from}',BM.qtym,(BM.qtym-BK.qtyk))*BM.price),0 ) price_tkeluar_umur_361
		,''r_total
		,POH.id_supplier
		,BM.price
		,POH.id
		,BM.id_item
		,POH.pono
		,POH.podate
		,BM.bpbno_int
		,BK.bppbno_int
		,BM.id_jo
		,BK.bppbdate
		FROM po_header POH
		
LEFT JOIN (SELECT
		 id
		,id_po
		,id_jo
		,id_gen
		,qty
		,price
		FROM po_item )POI ON POH.id = POI.id_po
		
LEFT JOIN (SELECT
		 id
		,pono
		,id_supplier
		,bpbdate
		,bpbno_int
		,id_item
		,price
		,qty qtym
		,id_jo
		,bpbno
		FROM bpb 
		WHERE bpbno_int IS NOT NULL AND id_so_det IS NULL AND bppbno_ri ='' AND SUBSTR(bpbno,1,1) IN('A','F')
		
)BM ON POI.id=BM.id_jo



LEFT JOIN (SELECT
		 id
		,bppbdate 
		,bppbno_int
		,id_item
		,id_jo
		,qty qtyk
		,id_supplier
		FROM bppb   
			WHERE bppbno_int IS NOT NULL AND id_so_det IS NULL AND bpbno_ro ='' AND SUBSTR(bppbno,4,1) IN('A','F')
			)BK ON BM.id_jo=BK.id_jo AND BM.id_item = BK.id_item 
				
LEFT JOIN (SELECT 
		 id_item
		,id_gen
		,goods_code
		,itemdesc
		FROM masteritem) MI ON BM.id_item = MI.id_item
		
LEFT JOIN (SELECT
		 id
		,id_jo
		,id_so
		FROM jo_det )JOD ON JOD.id_jo=BM.id_jo
		
LEFT JOIN (SELECT
		 id
		,id_cost
		FROM so) SOR ON JOD.id_so=SOR.id
	
LEFT JOIN ( SELECT 
		 id
		,cost_no
		,kpno no_ws
		FROM act_costing ) ACT ON SOR.id_cost= ACT.id
		
LEFT JOIN ( SELECT
			Id_Supplier
	 	  ,Supplier
	 	  ,supplier_code
	 	  FROM mastersupplier) MS ON BM.id_supplier= MS.Id_Supplier
WHERE BM.bpbno_int IS NOT NULL
GROUP BY BM.id_item, BM.id_jo
ORDER BY BM.bpbdate DESC
)S
)Y
GROUP BY Y.bpbno_int,Y.id_item,Y.id_jo
	

)X";
## Search
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (


		     X.goods_code LIKE'%".$searchValue."%'
		    OR X.itemdesc LIKE'%".$searchValue."%'
		    OR X.no_ws LIKE'%".$searchValue."%'
		    OR X.id_order LIKE'%".$searchValue."%'
		    OR X.supplier_code LIKE'%".$searchValue."%'
		    OR X.Supplier LIKE'%".$searchValue."%'
		    OR X.no_dokumen LIKE'%".$searchValue."%'
			OR X.qty_po LIKE'%".$searchValue."%'
			OR X.qty_masuk LIKE'%".$searchValue."%'
			OR X.keluar_qty LIKE'%".$searchValue."%'
			OR X.qty_akhir LIKE'%".$searchValue."%'
			OR X.harga_qty_po LIKE'%".$searchValue."%'
			OR X.harga_masuk_qty LIKE'%".$searchValue."%'
			OR X.harga_keluar_qty LIKE'%".$searchValue."%'
			OR X.harga_qty_akhir LIKE'%".$searchValue."%'
			OR X.u_awal LIKE'%".$searchValue."%'
			OR X.u_masuk LIKE'%".$searchValue."%'
			OR X.u_keluar LIKE'%".$searchValue."%'
			OR X.u_akhir LIKE'%".$searchValue."%'
			OR X.qty_umur_0_30 LIKE'%".$searchValue."%'
			OR X.qty_umur_31_60 LIKE'%".$searchValue."%'
			OR X.qty_umur_61_90 LIKE'%".$searchValue."%'
			OR X.qty_umur_91_120 LIKE'%".$searchValue."%'
			OR X.qty_umur_121_150 LIKE'%".$searchValue."%'
			OR X.qty_umur_151_180 LIKE'%".$searchValue."%'
			OR X.qty_umur_181_360 LIKE'%".$searchValue."%'
			OR X.qty_umur_361 LIKE'%".$searchValue."%'
			OR X.q_total LIKE'%".$searchValue."%'
			OR X.price_umur_0_30 LIKE'%".$searchValue."%'
			OR X.price_umur_31_60 LIKE'%".$searchValue."%'
			OR X.price_umur_61_90 LIKE'%".$searchValue."%'
			OR X.price_umur_91_120 LIKE'%".$searchValue."%'
			OR X.price_umur_121_150 LIKE'%".$searchValue."%'
			OR X.price_umur_151_180 LIKE'%".$searchValue."%'
			OR X.price_umur_181_360 LIKE'%".$searchValue."%'
			OR X.price_umur_361 LIKE'%".$searchValue."%'
			OR X.r_total LIKE'%".$searchValue."%'

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
			
			 X.goods_code
			,X.itemdesc
			,X.no_ws
			,X.id_order
			,X.supplier_code
			,X.Supplier
			,X.no_dokumen
			,X.qty_po
			,X.qty_masuk
			,X.keluar_qty
			,X.qty_akhir
			,X.harga_qty_po
			,X.harga_masuk_qty
			,X.harga_keluar_qty
			,X.harga_qty_akhir
			,X.u_awal
			,X.u_masuk
			,X.u_keluar
			,X.u_akhir
			,X.qty_umur_0_30
			,X.qty_umur_31_60
			,X.qty_umur_61_90
			,X.qty_umur_91_120
			,X.qty_umur_121_150
			,X.qty_umur_151_180
			,X.qty_umur_181_360
			,X.qty_umur_361
			,X.q_total
			,X.price_umur_0_30
			,X.price_umur_31_60
			,X.price_umur_61_90
			,X.price_umur_91_120
			,X.price_umur_121_150
			,X.price_umur_151_180
			,X.price_umur_181_360
			,X.price_umur_361
			,X.r_total

			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by X.Supplier limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];

$total_qty=$row['qty_umur_0_30']+$row['qty_umur_31_60']+$row['qty_umur_61_90']+$row['qty_umur_91_120']+$row['qty_umur_121_150']+$row['qty_umur_151_180']+$row['qty_umur_181_360']+$row['qty_umur_361'];
$total_price=$row['price_umur_0_30']+$row['price_umur_31_60']+$row['price_umur_61_90']+$row['price_umur_91_120']+$row['price_umur_121_150']+$row['price_umur_151_180']+$row['price_umur_181_360']+$row['price_umur_361'];

   $data[] = array(
"goods_code"=>htmlspecialchars($row['goods_code']),   //ok             
"itemdesc"=>htmlspecialchars($row['itemdesc']), //ok
"no_ws"=>htmlspecialchars($row['no_ws']), //ok
"id_order"=>htmlspecialchars($row['id_order']), //ok
"supplier_code"=>htmlspecialchars($row['supplier_code']), //ok days_pterms
"Supplier"=>htmlspecialchars($row['Supplier']), //ok
"no_dokumen"=>htmlspecialchars($row['no_dokumen']),
"qty_po"=>htmlspecialchars(number_format((float)$row['qty_po'], 2, '.', ',')), //ok
"qty_masuk"=>htmlspecialchars(number_format((float)$row['qty_masuk'], 2, '.', ',')), //ok
"keluar_qty"=>htmlspecialchars(number_format((float)$row['keluar_qty'], 2, '.', ',')), //ok
"qty_akhir"=>htmlspecialchars(number_format((float)$row['qty_akhir'], 2, '.', ',')), //ok
"harga_qty_po"=>htmlspecialchars(number_format((float)$row['harga_qty_po'], 2, '.', ',')), //ok
"harga_masuk_qty"=>htmlspecialchars(number_format((float)$row['harga_masuk_qty'], 2, '.', ',')), //ok
"harga_keluar_qty"=>htmlspecialchars(number_format((float)$row['harga_keluar_qty'], 2, '.', ',')), //ok
"harga_qty_akhir"=>htmlspecialchars(number_format((float)$row['harga_qty_akhir'], 2, '.', ',')), //ok days_pterms
"u_awal"=>htmlspecialchars(number_format((float)$row['u_awal'], 2, '.', ',')), //ok
"u_masuk"=>htmlspecialchars(number_format((float)$row['u_masuk'], 2, '.', ',')), //ok
"u_keluar"=>htmlspecialchars(number_format((float)$row['u_keluar'], 2, '.', ',')), //ok
"u_akhir"=>htmlspecialchars(number_format((float)$row['u_akhir'], 2, '.', ',')), //ok days_pterms
"qty_umur_0_30"=>htmlspecialchars(number_format((float)$row['qty_umur_0_30'], 2, '.', ',')), //ok
"qty_umur_31_60"=>htmlspecialchars(number_format((float)$row['qty_umur_31_60'], 2, '.', ',')), //ok
"qty_umur_61_90"=>htmlspecialchars(number_format((float)$row['qty_umur_61_90'], 2, '.', ',')), //ok
"qty_umur_91_120"=>htmlspecialchars(number_format((float)$row['qty_umur_91_120'], 2, '.', ',')), //ok days_pterms
"qty_umur_121_150"=>htmlspecialchars(number_format((float)$row['qty_umur_121_150'], 2, '.', ',')), //ok
"qty_umur_151_180"=>htmlspecialchars(number_format((float)$row['qty_umur_151_180'], 2, '.', ',')), //ok
"qty_umur_181_360"=>htmlspecialchars(number_format((float)$row['qty_umur_181_360'], 2, '.', ',')), //ok
"qty_umur_361"=>htmlspecialchars(number_format((float)$row['qty_umur_361'], 2, '.', ',')), //ok days_pterms
"q_total"=>htmlspecialchars($total_qty), //ok days_pterms
"price_umur_0_30"=>htmlspecialchars(number_format((float)$row['price_umur_0_30'], 2, '.', ',')), //ok
"price_umur_31_60"=>htmlspecialchars(number_format((float)$row['price_umur_31_60'], 2, '.', ',')), //ok
"price_umur_61_90"=>htmlspecialchars(number_format((float)$row['price_umur_61_90'], 2, '.', ',')), //ok
"price_umur_91_120"=>htmlspecialchars(number_format((float)$row['price_umur_91_120'], 2, '.', ',')), //ok days_pterms
"price_umur_121_150"=>htmlspecialchars(number_format((float)$row['price_umur_121_150'], 2, '.', ',')), //ok
"price_umur_151_180"=>htmlspecialchars(number_format((float)$row['price_umur_151_180'], 2, '.', ',')), //ok
"price_umur_181_360"=>htmlspecialchars(number_format((float)$row['price_umur_181_360'], 2, '.', ',')), //ok
"price_umur_361"=>htmlspecialchars(number_format((float)$row['price_umur_361'], 2, '.', ',')), //ok days_pterms
"r_total"=>htmlspecialchars(number_format((float)$total_price, 2, '.', ',')), //ok days_pterms

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