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
$table = "(SELECT Y.short_name
,	Y.nama_supplier
,	Y.kode_supplier
,	Y.kode_pterms
,	Y.periode
,	Y.unit
,	Y.qty
,	Y.price
,	Y.keterangan

FROM (SELECT 
		b.short_name short_name
	,	b.Supplier nama_supplier
	,	b.supplier_code kode_supplier
	,	a.kode_pterms kode_pterms
		,	IF(SUBSTR(a.period,6,1) IS NULL
			,CONCAT(SUBSTR(a.period,1,3),SUBSTR(a.period,6,2)),a.period)periode
	,	a.unit unit
	,	a.qty qty
	,	ROUND(a.price*a.qty,2) price
	,	'' keterangan

FROM 
(SELECT ih.invno, ih.invdate, ih.id_buyer, ih.id_pterms, ih.n_typeinvoice, ih.n_post, id.unit, id.qty, id.price, mp.kode_pterms, fjh.period, mr.rate  
FROM invoice_header ih 
LEFT JOIN invoice_detail id ON id.id_inv=ih.id 
LEFT JOIN masterpterms mp ON mp.id=ih.id_pterms 
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=ih.invno 
LEFT JOIN masterrate mr ON mr.tanggal=ih.invdate WHERE ih.n_typeinvoice='1' AND ih.n_post='2' AND fjh.fg_post='2' )a
LEFT JOIN 
(SELECT ms.Id_Supplier, ms.Supplier, ms.tipe_sup, ms.supplier_code, ms.short_name FROM mastersupplier ms WHERE ms.Id_Supplier!='')b ON b.Id_Supplier=a.id_buyer
WHERE a.invdate <= '$d_to' AND a.invdate >= '$d_from'
#WHERE a.invdate <= '2020-04-01' AND a.invdate >= '2020-01-01' #untuk testing data
GROUP BY a.invno ORDER BY b.Supplier, a.period ASC)Y  )X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	   X.short_name            LIKE'%".$searchValue."%'
	OR X.nama_supplier            LIKE'%".$searchValue."%'
	OR X.kode_supplier      LIKE'%".$searchValue."%'
	OR X.kode_pterms             LIKE'%".$searchValue."%'
	OR X.periode         LIKE'%".$searchValue."%'
	OR X.unit      LIKE'%".$searchValue."%'
	OR X.qty             LIKE'%".$searchValue."%'
	OR X.price          LIKE'%".$searchValue."%'
	OR X.keterangan      LIKE'%".$searchValue."%'

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
	   X.short_name            
,	   X.nama_supplier            
,	   X.kode_supplier      
,	   X.kode_pterms             
,	   X.periode         
,	   X.unit      
,	   X.qty             
,	   X.price          
,	   X.keterangan      
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
// echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];



   $data[] = array(
"short_name"=>htmlspecialchars($row['short_name']),
"nama_supplier"=>htmlspecialchars($row['nama_supplier']),
"kode_supplier"=>htmlspecialchars($row['kode_supplier']),
"kode_pterms"=>htmlspecialchars($row['kode_pterms']),
"periode"=>htmlspecialchars($row['periode']),
"unit"=>htmlspecialchars($row['unit']),
"qty"=>htmlspecialchars(number_format((float)$row['qty'], 2, '.', ',')),
"price"=>htmlspecialchars(number_format((float)$row['price'], 2, '.', ',')),
"keterangan"=>htmlspecialchars($row['keterangan']),

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