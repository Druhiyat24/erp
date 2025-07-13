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
$id_coa = $data['akun'];
/* 
print_r($data);
die();  */ 
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
/* $columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name */
//$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
SELECT tmp.* FROM(
SELECT a.so_no
,	a.so_date 
, 	b.deldate_det
,	a.revise
,	d.supplier_code buyer_code
,	d.Supplier Buyer
,	e.goods_code
,	e.itemname
,	e.Color
,	e.Styleno
,	a.unit
,	a.curr
,	a.qty
,	IF(a.curr='IDR',ROUND(b.price,2), ROUND((b.price*g.rate),2))price
,	IF(a.curr='IDR',ROUND(a.qty*b.price,2), ROUND((a.qty*(b.price*g.rate)),2))total_harga
, 	j.invno
,	j.bppbdate
,	i.invno
,	h.qty_sj
,	SUM(h.qty)total_qty
,	SUM(h.price)total_harga
,	(SUM(h.qty)-h.qty)outstanding_qty

FROM so a
LEFT JOIN so_det b ON b.id_so=a.id
LEFT JOIN act_costing c ON c.id=a.id_cost
LEFT JOIN mastersupplier d ON d.Id_Supplier=c.id_buyer
LEFT JOIN masterstyle e ON e.id_so_det=b.id
LEFT JOIN masteritem f ON f.id_item=e.id_item
LEFT JOIN masterrate g ON g.tanggal=a.so_date
LEFT JOIN invoice_detail h ON h.id_so_det=b.id
LEFT JOIN invoice_header i ON i.id=h.id_inv
LEFT JOIN bppb j ON j.id_so_det=b.id
WHERE 1=1 AND g.v_codecurr='PAJAK' )X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	   X.so_no             LIKE'%".$searchValue."%'
	OR X.so_date               LIKE'%".$searchValue."%'
	OR X.deldate_det              LIKE'%".$searchValue."%'
	OR X.revise                   LIKE'%".$searchValue."%'
	OR X.buyer_code                   LIKE'%".$searchValue."%'
	OR X.Buyer              LIKE'%".$searchValue."%'
	OR X.goods_code             LIKE'%".$searchValue."%'
	OR X.itemname             LIKE'%".$searchValue."%'
	OR X.Color            LIKE'%".$searchValue."%'
	OR X.Styleno					  LIKE'%".$searchValue."%'
	OR X.unit				  LIKE'%".$searchValue."%'
	OR X.curr			      LIKE'%".$searchValue."%'
	OR X.qty                LIKE'%".$searchValue."%'
	OR X.price			      LIKE'%".$searchValue."%'
	OR X.total_harga          LIKE'%".$searchValue."%'	
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
			 X.date_journal                   
			,X.id_journal            	
			,X.v_novoucher                 	
			,X.id_coa                  	
			,X.nm_coa    
			,X.no_bill_yet
			,X.id_coa_lawan
			,X.nm_coa_lawan                   	
			,X.v_fakturpajak
			,X.reff_doc		
			,X.rate
			,X.debit_idr	
			,X.debit_usd
			,X.credit_idr                    	
			,X.credit_usd	
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];


   $data[] = array(
"date_journal"=>htmlspecialchars($row['date_journal']),
"id_journal"=>htmlspecialchars($row['id_journal']),
"v_novoucher"=>htmlspecialchars($row['v_novoucher']),
"id_coa"=>htmlspecialchars($row['id_coa']),
"nm_coa"=>htmlspecialchars($row['nm_coa']),
"no_bill_yet"=>htmlspecialchars($row['no_bill_yet']),
"id_coa_lawan"=>htmlspecialchars($row['id_coa_lawan']),
"nm_coa_lawan"=>htmlspecialchars($row['nm_coa_lawan']),
"v_fakturpajak"=>htmlspecialchars($row['v_fakturpajak']),
"reff_doc"=>htmlspecialchars($row['reff_doc']),
"rate"=>htmlspecialchars(number_format((float)$row['rate'], 2, '.', ',')),
"debit_idr"=>htmlspecialchars(number_format((float)$row['debit_idr'], 2, '.', ',')),
"debit_usd"=>htmlspecialchars(number_format((float)$row['debit_usd'], 2, '.', ',')),
"credit_idr"=>htmlspecialchars(number_format((float)$row['credit_idr'], 2, '.', ',')),
"credit_usd"=>htmlspecialchars(number_format((float)$row['credit_usd'], 2, '.', ',')),

/* "price"=>htmlspecialchars(number_format((float)$row['price'], 2, ',', '.')),
"dpp"=>htmlspecialchars(number_format((float)$row['dpp'], 2, ',', '.')),
"ppn"=>htmlspecialchars(number_format((float)$row['ppn'], 2, ',', '.')),
"after_ppn"=>htmlspecialchars(number_format((float)$row['after_ppn'] 2, ',', '.'))
 */
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