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
$table = "SELECT
	X.kode
,	X.nama
,	X.nama_alias
,	X.TOP
,	X.periode
,	X.satuan_qty
,	X.qty
,	X.rupiah
,	X.keterangan
FROM
(SELECT
		c.supplier_code kode
,		c.Supplier nama
,		c.short_name nama_alias
,		d.kode_pterms TOP
,		e.period periode
,		b.unit satuan_qty
,		b.qty qty
#,		IF(a.curr='IDR',ROUND(b.price,2), ROUND((b.price*f.rate),2))rupiah
,		b.price rupiah
,		'' keterangan


FROM invoice_header a
LEFT JOIN invoice_detail b ON b.id_inv=a.id
LEFT JOIN (SELECT id_Supplier, Supplier, supplier_code, short_name, id_coa, AREA FROM mastersupplier WHERE AREA='L' AND Supplier IS NOT NULL)c ON c.id_Supplier=a.id_buyer
LEFT JOIN masterpterms d ON d.id=a.id_pterms
LEFT JOIN (SELECT * FROM fin_journal_h WHERE type_journal='1' AND fg_post='2')e ON e.reff_doc=a.invno
LEFT JOIN masterrate f ON f.tanggal=a.invdate
WHERE a.invdate <= '$d_to' AND a.invdate >= '$d_from'
#WHERE a.invdate <= '2019-12-31' AND a.invdate >= '2019-10-31' #untuk testing data
AND f.v_codecurr='PAJAK')X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	   X.kode            LIKE'%".$searchValue."%'
	OR X.nama            LIKE'%".$searchValue."%'
	OR X.nama_alias      LIKE'%".$searchValue."%'
	OR X.TOP             LIKE'%".$searchValue."%'
	OR X.periode         LIKE'%".$searchValue."%'
	OR X.satuan_qty      LIKE'%".$searchValue."%'
	OR X.qty             LIKE'%".$searchValue."%'
	OR X.rupiah          LIKE'%".$searchValue."%'
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
	   X.kode            
	   X.nama            
	   X.nama_alias      
	   X.TOP             
	   X.periode         
	   X.satuan_qty      
	   X.qty             
	   X.rupiah          
	   X.keterangan      
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];


   $data[] = array(
"kode"=>htmlspecialchars($row['kode']),
"nama"=>htmlspecialchars($row['nama']),
"nama_alias"=>htmlspecialchars($row['nama_alias']),
"TOP"=>htmlspecialchars($row['TOP']),
"periode"=>htmlspecialchars($row['periode']),
"satuan_qty"=>htmlspecialchars($row['satuan_qty']),
"qty"=>htmlspecialchars($row['qty']),
"rupiah"=>htmlspecialchars(number_format((float)$row['rupiah'], 2, '.', ',')),
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