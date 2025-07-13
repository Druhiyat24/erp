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
/*
print_r($_POST);
die();  */
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
SELECT

 Y.id
,Y.product_group
,Y.product_item
,Y.no_ws
,Y.id_order
,Y.supplier_code
,Y.Supplier
,GROUP_CONCAT(Y.populasi_bpb_tgl)no_dokumen
,Y.bpbno_int
,Y.bppbno_int
,Y.bpbdate
,Y.bppbdate
,Y.id_item
,Y.id_so_det
,Y.u_awal
,Y.u_masuk
,Y.u_keluar
,Y.u_akhir
,Y.r_total
,Y.q_total
,SUM(Y.qty_awal)qty_so
,SUM(Y.qty_masuk)qty_masuk
,SUM(Y.qty_keluar)keluar_qty
,SUM(Y.qty_akhir)qty_akhir
,SUM(Y.qty_awal*Y.price)harga_qty_so
,SUM(Y.qty_masuk*Y.price)harga_masuk_qty
,SUM(Y.qty_keluar*Y.price)harga_keluar_qty
,SUM(Y.qty_akhir*Y.price)harga_qty_akhir
,Y.curr
,SUM(Y.qty_tkeluar_umur_0_30       )qty_umur_0_30
,SUM(Y.qty_tkeluar_umur_31_60      )qty_umur_31_60
,SUM(Y.qty_tkeluar_umur_61_90      )qty_umur_61_90
,SUM(Y.qty_tkeluar_umur_91_120     )qty_umur_91_120
,SUM(Y.qty_tkeluar_umur_121_150    )qty_umur_121_150
,SUM(Y.qty_tkeluar_umur_151_180    )qty_umur_151_180
,SUM(Y.qty_tkeluar_umur_181_360	  )qty_umur_181_360	
,SUM(Y.qty_tkeluar_umur_361        )qty_umur_361
,SUM(Y.price_tkeluar_umur_0_30     )price_umur_0_30
,SUM(Y.price_tkeluar_umur_31_60    )price_umur_31_60
,SUM(Y.price_tkeluar_umur_61_90    )price_umur_61_90
,SUM(Y.price_tkeluar_umur_91_120   )price_umur_91_120
,SUM(Y.price_tkeluar_umur_121_150  )price_umur_121_150
,SUM(Y.price_tkeluar_umur_151_180  )price_umur_151_180
,SUM(Y.price_tkeluar_umur_181_360  )price_umur_181_360
,SUM(Y.price_tkeluar_umur_361      )price_umur_361
FROM 	(
	
	SELECT 
		 P.id
		,P.product_group
		,P.product_item
		,P.no_ws
		,P.Supplier
		,P.supplier_code
		,CONCAT(P.bpbno_int,' (',P.bpbdate,')') AS populasi_bpb_tgl
		,P.bpbno_int
		,P.id_order
		,P.bppbno_int
		,P.bpbdate
		,P.bppbdate
		,P.id_item
		,P.u_awal
		,P.u_masuk
		,P.u_keluar
		,P.u_akhir
		,P.r_total
		,P.q_total
		,P.id_so_det
		,SUM(P.qty_so)qty_awal
		,SUM(P.qtyb)qty_masuk
		,SUM(P.qty)qty_keluar
		,SUM(P.qty_akhir)qty_akhir
		,P.price
		,(P.qty_so*price)harga_qty_awal
		,(P.qtyb*price)harga_qty_masuk
		,(P.qty*price)harga_qty_keluar
		,(P.qty_akhir*price)harga_qty_akhir
		,P.curr
		,SUM(P.qty_tkeluar_umur_0_30       )qty_tkeluar_umur_0_30
		,SUM(P.qty_tkeluar_umur_31_60      )qty_tkeluar_umur_31_60
		,SUM(P.qty_tkeluar_umur_61_90      )qty_tkeluar_umur_61_90
		,SUM(P.qty_tkeluar_umur_91_120     )qty_tkeluar_umur_91_120
		,SUM(P.qty_tkeluar_umur_121_150    )qty_tkeluar_umur_121_150
		,SUM(P.qty_tkeluar_umur_151_180    )qty_tkeluar_umur_151_180
		,SUM(P.qty_tkeluar_umur_181_360	   )qty_tkeluar_umur_181_360		
		,SUM(P.qty_tkeluar_umur_361        )qty_tkeluar_umur_361
		,SUM(P.price_tkeluar_umur_0_30     )price_tkeluar_umur_0_30
		,SUM(P.price_tkeluar_umur_31_60    )price_tkeluar_umur_31_60
		,SUM(P.price_tkeluar_umur_61_90    )price_tkeluar_umur_61_90
		,SUM(P.price_tkeluar_umur_91_120   )price_tkeluar_umur_91_120
		,SUM(P.price_tkeluar_umur_121_150  )price_tkeluar_umur_121_150
		,SUM(P.price_tkeluar_umur_151_180  )price_tkeluar_umur_151_180
		,SUM(P.price_tkeluar_umur_181_360  )price_tkeluar_umur_181_360
		,SUM(P.price_tkeluar_umur_361      )price_tkeluar_umur_361
		FROM 
	
	(SELECT
		 C.id
		,mp.product_group
		,mp.product_item
		,'N/A' id_order
		,act.kpno no_ws
		,ms.supplier_code
		,ms.Supplier
		,D.bpbno_int
		,C.bppbno_int
		,D.bpbdate
		,C.bppbdate
		,C.id_item
		,'0'u_awal
		,'0'u_masuk
		,'0'u_keluar
		,'0'u_akhir
		,''q_total
		,''r_total
		,C.id_so_det
		,SO.qty AS qty_so
		,C.qty
		,D.qtyb
		,IF(C.bppbdate>'$d_from',D.qtyb,(D.qtyb-C.qty))qty_akhir
		,SOD.price
		,C.curr
		,IF(DATEDIFF('$d_from', D.bpbdate) >=0 AND DATEDIFF('$d_from', D.bpbdate) <=30, IF(C.bppbdate>'$d_from',D.qtyb,(D.qtyb-C.qty)),0) qty_tkeluar_umur_0_30
		,IF(DATEDIFF('$d_from', D.bpbdate) >=31 AND DATEDIFF('$d_from', D.bpbdate) <=60, IF(C.bppbdate>'$d_from',D.qtyb,(D.qtyb-C.qty)),0 ) qty_tkeluar_umur_31_60
		,IF(DATEDIFF('$d_from', D.bpbdate) >=61 AND DATEDIFF('$d_from', D.bpbdate) <=90, IF(C.bppbdate>'$d_from',D.qtyb,(D.qtyb-C.qty)),0 ) qty_tkeluar_umur_61_90
		,IF(DATEDIFF('$d_from', D.bpbdate) >=91 AND DATEDIFF('$d_from', D.bpbdate) <=120,IF(C.bppbdate>'$d_from',D.qtyb,(D.qtyb-C.qty)),0 ) qty_tkeluar_umur_91_120
		,IF(DATEDIFF('$d_from', D.bpbdate) >=121 AND DATEDIFF('$d_from',D.bpbdate) <=150,IF(C.bppbdate>'$d_from',D.qtyb,(D.qtyb-C.qty)),0 ) qty_tkeluar_umur_121_150
		,IF(DATEDIFF('$d_from', D.bpbdate) >=151 AND DATEDIFF('$d_from',D.bpbdate) <=180,IF(C.bppbdate>'$d_from',D.qtyb,(D.qtyb-C.qty)),0 ) qty_tkeluar_umur_151_180
		,IF(DATEDIFF('$d_from', D.bpbdate) >=181 AND DATEDIFF('$d_from',D.bpbdate) <=360,IF(C.bppbdate>'$d_from',D.qtyb,(D.qtyb-C.qty)),0 ) qty_tkeluar_umur_181_360		
		,IF(DATEDIFF('$d_from', D.bpbdate) >=361,IF(C.bppbdate>'$d_from',D.qtyb,(D.qtyb-C.qty)),0 ) qty_tkeluar_umur_361

		,IF(DATEDIFF('$d_from', D.bpbdate) >=0 AND DATEDIFF('$d_from',  D.bpbdate) <=30, (IF(C.bppbdate>'$d_from',D.qtyb,(D.qtyb-C.qty))*SOD.price),0 ) price_tkeluar_umur_0_30
		,IF(DATEDIFF('$d_from', D.bpbdate) >=31 AND DATEDIFF('$d_from', D.bpbdate) <=60, (IF(C.bppbdate>'$d_from',D.qtyb,(D.qtyb-C.qty))*SOD.price),0 ) price_tkeluar_umur_31_60
		,IF(DATEDIFF('$d_from', D.bpbdate) >=61 AND DATEDIFF('$d_from', D.bpbdate) <=90, (IF(C.bppbdate>'$d_from',D.qtyb,(D.qtyb-C.qty))*SOD.price),0 ) price_tkeluar_umur_61_90
		,IF(DATEDIFF('$d_from', D.bpbdate) >=91 AND DATEDIFF('$d_from', D.bpbdate) <=120,(IF(C.bppbdate>'$d_from',D.qtyb,(D.qtyb-C.qty))*SOD.price),0 ) price_tkeluar_umur_91_120
		,IF(DATEDIFF('$d_from', D.bpbdate) >=121 AND DATEDIFF('$d_from',D.bpbdate) <=150,(IF(C.bppbdate>'$d_from',D.qtyb,(D.qtyb-C.qty))*SOD.price),0 ) price_tkeluar_umur_121_150
		,IF(DATEDIFF('$d_from', D.bpbdate) >=151 AND DATEDIFF('$d_from',D.bpbdate) <=180,(IF(C.bppbdate>'$d_from',D.qtyb,(D.qtyb-C.qty))*SOD.price),0 ) price_tkeluar_umur_151_180
		,IF(DATEDIFF('$d_from', D.bpbdate) >=181 AND DATEDIFF('$d_from',D.bpbdate) <=360,(IF(C.bppbdate>'$d_from',D.qtyb,(D.qtyb-C.qty))*SOD.price),0 ) price_tkeluar_umur_181_360	
		,IF(DATEDIFF('$d_from', D.bpbdate) >=361,(IF(C.bppbdate>'$d_from',D.qtyb,(D.qtyb-C.qty))*SOD.price),0 ) price_tkeluar_umur_361
		
	FROM bppb C

LEFT JOIN (SELECT price,(id_so)id_so,(id) FROM so_det WHERE 1=1)SOD ON SOD.id = C.id_so_det
	LEFT JOIN so SO ON SO.id = SOD.id_so
LEFT JOIN ( SELECT 
		 id
		,id_buyer
		,kpno
		,id_product
		
		FROM act_costing ) act ON SO.id_cost = act.id
		LEFT JOIN ( SELECT
			Id_Supplier
	 	  ,Supplier
	 	  ,supplier_code
	 	  FROM mastersupplier) ms ON act.id_buyer = ms.Id_Supplier

LEFT JOIN ( SELECT
		 id
		,product_group
		,product_item
	FROM masterproduct ) mp ON mp.id =  act.id_product
	LEFT JOIN (SELECT id,bpbno_int,id_so_det,qty qtyb,price,bpbdate FROM bpb WHERE 1=1 )D ON D.id_so_det = C.id_so_det
		WHERE C.id_so_det IS NOT NULL AND bppbno_int LIKE '%FG/%'
		
		) P 
		GROUP BY P.bpbno_int
		
		)Y 
		
GROUP BY Y.bppbno_int
		


	
	
	
	

)X";
## Search
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (


		   X.product_group
		OR X.product_item
		OR X.no_ws
		OR X.id_order
		OR X.supplier_code
		OR X.Supplier
		OR X.no_dokumen
		OR X.q_awal
		OR X.q_masuk
		OR X.q_keluar
		OR X.q_akhir
		OR X.r_awal
		OR X.r_masuk
		OR X.r_keluar
		OR X.r_akhir
		OR X.u_awal
		OR X.u_masuk
		OR X.u_keluar
		OR X.u_akhir
		OR X.qty_umur_0_30
		OR X.qty_umur_31_60
		OR X.qty_umur_61_90
		OR X.qty_umur_91_120
		OR X.qty_umur_121_150
		OR X.qty_umur_151_180
		OR X.qty_umur_181_360
		OR X.qty_umur_361
		OR X.q_total
		OR X.price_umur_0_30
		OR X.price_umur_31_60
		OR X.price_umur_61_90
		OR X.price_umur_91_120
		OR X.price_umur_121_150
		OR X.price_umur_151_180
		OR X.price_umur_181_360
		OR X.price_umur_361
		OR X.r_total

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
			
			 X.product_group
			,X.product_item
			,X.no_ws
			,X.id_order
			,X.supplier_code
			,X.Supplier
			,X.no_dokumen
			,X.qty_so
			,X.qty_masuk
			,X.keluar_qty
			,X.qty_akhir
			,X.harga_qty_so
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

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];

$total_qty=$row['qty_umur_0_30']+$row['qty_umur_31_60']+$row['qty_umur_61_90']+$row['qty_umur_91_120']+$row['qty_umur_121_150']+$row['qty_umur_151_180']+$row['qty_umur_181_360']+$row['qty_umur_361'];
$total_price=$row['price_umur_0_30']+$row['price_umur_31_60']+$row['price_umur_61_90']+$row['price_umur_91_120']+$row['price_umur_121_150']+$row['price_umur_151_180']+$row['price_umur_181_360']+$row['price_umur_361'];

   $data[] = array(
"product_group"=>htmlspecialchars($row['product_group']),   //ok             
"product_item"=>htmlspecialchars($row['product_item']), //ok
"no_ws"=>htmlspecialchars($row['no_ws']), //ok
"id_order"=>htmlspecialchars($row['id_order']), //ok
"supplier_code"=>htmlspecialchars($row['supplier_code']), //ok days_pterms
"Supplier"=>htmlspecialchars($row['Supplier']), //ok
"no_dokumen"=>htmlspecialchars($row['no_dokumen']), //ok
"qty_so"=>htmlspecialchars($row['qty_so']), //ok
"qty_masuk"=>htmlspecialchars($row['qty_masuk']), //ok
"keluar_qty"=>htmlspecialchars($row['keluar_qty']), //ok
"qty_akhir"=>htmlspecialchars($row['qty_akhir']), //ok days_pterms
"harga_qty_so"=>htmlspecialchars(number_format((float)$row['harga_qty_so'], 2, '.', ',')), //ok
"harga_masuk_qty"=>htmlspecialchars(number_format((float)$row['harga_masuk_qty'], 2, '.', ',')), //ok
"harga_keluar_qty"=>htmlspecialchars(number_format((float)$row['harga_keluar_qty'], 2, '.', ',')), //ok
"harga_qty_akhir"=>htmlspecialchars(number_format((float)$row['harga_qty_akhir'], 2, '.', ',')), //ok days_pterms
"u_awal"=>htmlspecialchars($row['u_awal']), //ok
"u_masuk"=>htmlspecialchars($row['u_masuk']), //ok
"u_keluar"=>htmlspecialchars($row['u_keluar']), //ok
"u_akhir"=>htmlspecialchars($row['u_akhir']), //ok days_pterms
"qty_umur_0_30"=>htmlspecialchars($row['qty_umur_0_30']), //ok
"qty_umur_31_60"=>htmlspecialchars($row['qty_umur_31_60']), //ok
"qty_umur_61_90"=>htmlspecialchars($row['qty_umur_61_90']), //ok
"qty_umur_91_120"=>htmlspecialchars($row['qty_umur_91_120']), //ok days_pterms
"qty_umur_121_150"=>htmlspecialchars($row['qty_umur_121_150']), //ok
"qty_umur_151_180"=>htmlspecialchars($row['qty_umur_151_180']), //ok
"qty_umur_181_360"=>htmlspecialchars($row['qty_umur_181_360']), //ok
"qty_umur_361"=>htmlspecialchars($row['qty_umur_361']), //ok days_pterms
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