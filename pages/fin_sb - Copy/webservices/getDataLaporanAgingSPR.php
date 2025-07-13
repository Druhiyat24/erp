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
,Y.id_bpb
,Y.product_group
,Y.product_item
,Y.bpbno_int
,Y.bppbno_int
,Y.bpbdate
,Y.bppbdate
,Y.u_awal
,Y.u_masuk
,Y.u_keluar
,Y.u_akhir
,Y.r_total
,Y.q_total
,Y.qty_awal qty_po
,Y.qty_masuk qty_masuk
,Y.qty_keluar keluar_qty
,Y.qty_akhir qty_akhir
,(Y.qty_awal*Y.price)harga_qty_po
,(Y.qty_masuk*Y.price)harga_masuk_qty
,(Y.qty_keluar*Y.price)harga_keluar_qty
,(Y.qty_akhir*Y.price)harga_qty_akhir
,Y.curr
,(Y.qty_tkeluar_umur_0_30       )qty_umur_0_30
,(Y.qty_tkeluar_umur_31_60      )qty_umur_31_60
,(Y.qty_tkeluar_umur_61_90      )qty_umur_61_90
,(Y.qty_tkeluar_umur_91_120     )qty_umur_91_120
,(Y.qty_tkeluar_umur_121_150    )qty_umur_121_150
,(Y.qty_tkeluar_umur_151_180    )qty_umur_151_180
,(Y.qty_tkeluar_umur_181_360	  )qty_umur_181_360	
,(Y.qty_tkeluar_umur_361        )qty_umur_361
,(Y.price_tkeluar_umur_0_30     )price_umur_0_30
,(Y.price_tkeluar_umur_31_60    )price_umur_31_60
,(Y.price_tkeluar_umur_61_90    )price_umur_61_90
,(Y.price_tkeluar_umur_91_120   )price_umur_91_120
,(Y.price_tkeluar_umur_121_150  )price_umur_121_150
,(Y.price_tkeluar_umur_151_180  )price_umur_151_180
,(Y.price_tkeluar_umur_181_360  )price_umur_181_360
,(Y.price_tkeluar_umur_361      )price_umur_361
FROM 	(


SELECT
	 
		 P.id
		,P.id_bpb
		,P.product_group
		,P.product_item
		,P.bpbno_int
		,P.bppbno_int
		,P.bpbdate
		,P.bppbdate
		,P.u_awal
		,P.u_masuk
		,P.u_keluar
		,P.u_akhir
		,P.r_total
		,P.q_total
		,P.qty_po qty_awal
		,P.qtyb qty_masuk
		,P.qty qty_keluar
		,P.qty_akhir qty_akhir
		,P.price
		,(P.qty_po*price)harga_qty_awal
		,(P.qtyb*price)harga_qty_masuk
		,(P.qty*price)harga_qty_keluar
		,(P.qty_akhir*price)harga_qty_akhir
		,P.curr
		,(P.qty_tkeluar_umur_0_30       )qty_tkeluar_umur_0_30
		,(P.qty_tkeluar_umur_31_60      )qty_tkeluar_umur_31_60
		,(P.qty_tkeluar_umur_61_90      )qty_tkeluar_umur_61_90
		,(P.qty_tkeluar_umur_91_120     )qty_tkeluar_umur_91_120
		,(P.qty_tkeluar_umur_121_150    )qty_tkeluar_umur_121_150
		,(P.qty_tkeluar_umur_151_180    )qty_tkeluar_umur_151_180
		,(P.qty_tkeluar_umur_181_360	   )qty_tkeluar_umur_181_360		
		,(P.qty_tkeluar_umur_361        )qty_tkeluar_umur_361
		,(P.price_tkeluar_umur_0_30     )price_tkeluar_umur_0_30
		,(P.price_tkeluar_umur_31_60    )price_tkeluar_umur_31_60
		,(P.price_tkeluar_umur_61_90    )price_tkeluar_umur_61_90
		,(P.price_tkeluar_umur_91_120   )price_tkeluar_umur_91_120
		,(P.price_tkeluar_umur_121_150  )price_tkeluar_umur_121_150
		,(P.price_tkeluar_umur_151_180  )price_tkeluar_umur_151_180
		,(P.price_tkeluar_umur_181_360  )price_tkeluar_umur_181_360
		,(P.price_tkeluar_umur_361      )price_tkeluar_umur_361

FROM
(SELECT 
			c.goods_code product_group
		,	c.itemdesc product_item
		,	f.qty qty_po
		,	f.unit unit_po
		,	f.curr 
		,	f.price
		,	d.id id_bpb
		, d.bpbno_int
		, d.bpbdate
		, d.qty qtyb
		,	d.unit unit_bpb
		,	d.curr curr_bpb
		,	d.price price_bpb
		, e.id
		, e.bppbno
		, e.bppbno_int
		, e.bppbdate
		, e.qty
		, e.unit unit_bppb
		, e.curr curr_bppb
		, e.price price_bppb
		, '0'u_awal
		, '0'u_masuk
		, '0'u_keluar
		, '0'u_akhir
		, ''q_total
		, ''r_total
		,IF(e.bppbdate>'$d_from',d.qty,(d.qty-e.qty))qty_akhir
		,IF(DATEDIFF('$d_from', d.bpbdate) >=0 AND DATEDIFF('$d_from', d.bpbdate) <=30, IF(e.bppbdate>'$d_from',d.qty,(d.qty-e.qty)),0) qty_tkeluar_umur_0_30
		,IF(DATEDIFF('$d_from', d.bpbdate) >=31 AND DATEDIFF('$d_from', d.bpbdate) <=60, IF(e.bppbdate>'$d_from',d.qty,(d.qty-e.qty)),0 ) qty_tkeluar_umur_31_60
		,IF(DATEDIFF('$d_from', d.bpbdate) >=61 AND DATEDIFF('$d_from', d.bpbdate) <=90, IF(e.bppbdate>'$d_from',d.qty,(d.qty-e.qty)),0 ) qty_tkeluar_umur_61_90
		,IF(DATEDIFF('$d_from', d.bpbdate) >=91 AND DATEDIFF('$d_from', d.bpbdate) <=120,IF(e.bppbdate>'$d_from',d.qty,(d.qty-e.qty)),0 ) qty_tkeluar_umur_91_120
		,IF(DATEDIFF('$d_from', d.bpbdate) >=121 AND DATEDIFF('$d_from',d.bpbdate) <=150,IF(e.bppbdate>'$d_from',d.qty,(d.qty-e.qty)),0 ) qty_tkeluar_umur_121_150
		,IF(DATEDIFF('$d_from', d.bpbdate) >=151 AND DATEDIFF('$d_from',d.bpbdate) <=180,IF(e.bppbdate>'$d_from',d.qty,(d.qty-e.qty)),0 ) qty_tkeluar_umur_151_180
		,IF(DATEDIFF('$d_from', d.bpbdate) >=181 AND DATEDIFF('$d_from',d.bpbdate) <=360,IF(e.bppbdate>'$d_from',d.qty,(d.qty-e.qty)),0 ) qty_tkeluar_umur_181_360		
		,IF(DATEDIFF('$d_from', d.bpbdate) >=361,IF(e.bppbdate>'$d_from',d.qty,(d.qty-e.qty)),0 ) qty_tkeluar_umur_361

		,IF(DATEDIFF('$d_from', d.bpbdate) >=0 AND DATEDIFF('$d_from',  d.bpbdate) <=30, (IF(e.bppbdate>'$d_from',d.qty,(d.qty-e.qty))*b.price),0 ) price_tkeluar_umur_0_30
		,IF(DATEDIFF('$d_from', d.bpbdate) >=31 AND DATEDIFF('$d_from', d.bpbdate) <=60, (IF(e.bppbdate>'$d_from',d.qty,(d.qty-e.qty))*b.price),0 ) price_tkeluar_umur_31_60
		,IF(DATEDIFF('$d_from', d.bpbdate) >=61 AND DATEDIFF('$d_from', d.bpbdate) <=90, (IF(e.bppbdate>'$d_from',d.qty,(d.qty-e.qty))*b.price),0 ) price_tkeluar_umur_61_90
		,IF(DATEDIFF('$d_from', d.bpbdate) >=91 AND DATEDIFF('$d_from', d.bpbdate) <=120,(IF(e.bppbdate>'$d_from',d.qty,(d.qty-e.qty))*b.price),0 ) price_tkeluar_umur_91_120
		,IF(DATEDIFF('$d_from', d.bpbdate) >=121 AND DATEDIFF('$d_from',d.bpbdate) <=150,(IF(e.bppbdate>'$d_from',d.qty,(d.qty-e.qty))*b.price),0 ) price_tkeluar_umur_121_150
		,IF(DATEDIFF('$d_from', d.bpbdate) >=151 AND DATEDIFF('$d_from',d.bpbdate) <=180,(IF(e.bppbdate>'$d_from',d.qty,(d.qty-e.qty))*b.price),0 ) price_tkeluar_umur_151_180
		,IF(DATEDIFF('$d_from', d.bpbdate) >=181 AND DATEDIFF('$d_from',d.bpbdate) <=360,(IF(e.bppbdate>'$d_from',d.qty,(d.qty-e.qty))*b.price),0 ) price_tkeluar_umur_181_360	
		,IF(DATEDIFF('$d_from', d.bpbdate) >=361,(IF(e.bppbdate>'$d_from',d.qty,(d.qty-e.qty))*b.price),0 ) price_tkeluar_umur_361

FROM reqnon_header a
LEFT JOIN reqnon_item b ON b.id_reqno=a.id
LEFT JOIN masteritem c on c.id_item=b.id_item
LEFT JOIN bpb d ON d.id_item=b.id_item
LEFT JOIN bppb e ON e.id_item=d.id_item
LEFT JOIN po_header g ON g.pono=d.pono
LEFT JOIN po_item f ON f.id_po=g.id


WHERE LEFT(c.goods_code,3)='SPR' AND a.app='A' AND a.app2='A' AND b.cancel='N'
AND d.bpbno_int LIKE '%GEN/%' AND d.bpbno_int !=''
AND e.bppbno_int LIKE '%GEN/%' AND e.bppbno_int !=''
AND g.pono LIKE '%PO/%'

GROUP BY d.bpbno, e.bppbno
ORDER BY d.id ASC)P
		GROUP BY P.bpbno_int
)Y
GROUP BY Y.bppbno_int
ORDER BY Y.bpbdate ASC 

)X";
## Search
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (


		   X.product_group
		OR X.product_item
		OR X.bpbno_int
		OR X.bpbdate
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
			,X.bpbno_int
			,X.bpbdate
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

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
// echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];

$total_qty=$row['qty_umur_0_30']+$row['qty_umur_31_60']+$row['qty_umur_61_90']+$row['qty_umur_91_120']+$row['qty_umur_121_150']+$row['qty_umur_151_180']+$row['qty_umur_181_360']+$row['qty_umur_361'];
$total_price=$row['price_umur_0_30']+$row['price_umur_31_60']+$row['price_umur_61_90']+$row['price_umur_91_120']+$row['price_umur_121_150']+$row['price_umur_151_180']+$row['price_umur_181_360']+$row['price_umur_361'];

   $data[] = array(
"product_group"=>htmlspecialchars($row['product_group']),   //ok             
"product_item"=>htmlspecialchars($row['product_item']), //ok
"bpbno_int"=>htmlspecialchars($row['bpbno_int']), //ok
"bpbdate"=>htmlspecialchars($row['bpbdate']), //ok
"qty_po"=>htmlspecialchars($row['qty_po']), //ok
"qty_masuk"=>htmlspecialchars($row['qty_masuk']), //ok
"keluar_qty"=>htmlspecialchars($row['keluar_qty']), //ok
"qty_akhir"=>htmlspecialchars($row['qty_akhir']), //ok days_pterms
"harga_qty_po"=>htmlspecialchars(number_format((float)$row['harga_qty_po'], 2, '.', ',')), //ok
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