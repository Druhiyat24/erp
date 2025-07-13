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
// $id_coa = $data['akun'];
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
	SELECT
		M.id,
		M.no_in_out,
		M.tanggal,
		M.satuan,
		M.qty,
		'N/A' price,
		M.id_jo,
		AC.kpno,
		SUP.supplier_code AS buyerno,
		SUP.nama_konsumen,
		'N/A' id_proses,
		'N/A' id_order,
		'N/A' up,
		'N/A' bagian,
		'N/A' no_dokumen
	FROM (
		
		SELECT 
			B1.id AS id,
			B1.bpbno AS no_proses,
			B1.bpbno_int AS no_in_out,
			B1.bpbdate AS tanggal,
			B1.unit AS satuan,
			SUM(ifnull(B1.qty,0)) AS qty,
			B1.id_jo AS id_jo
		FROM bpb AS B1 WHERE id_supplier = '432' AND bpbno LIKE '%C%'
		GROUP BY B1.bpbno_int
		
		UNION ALL
		
		SELECT 
			BP1.id AS id,
			BP1.bppbno AS no_proses,
			BP1.bppbno_int AS no_in_out,
			BP1.bppbdate AS tanggal,
			BP1.unit AS satuan,
			SUM(ifnull(BP1.qty,0)) AS qty,
			BP1.id_jo AS id_jo
		FROM bppb AS BP1 WHERE bppbno_int LIKE '%WIP%' AND id_supplier != '432'
		GROUP BY BP1.bppbno_int
		
		UNION ALL
		
		SELECT 
			B2.id AS id,
			B2.bpbno AS no_proses,
			B2.bpbno_int AS no_in_out,
			B2.bpbdate AS tanggal,
			B2.unit AS satuan,
			SUM(ifnull(B2.qty,0)) AS qty,
			B2.id_jo AS id_jo
		FROM bpb AS B2 WHERE id_supplier != '432' AND bpbno LIKE '%C%'
		
		UNION ALL
		
		SELECT 
			BP2.id AS id,
			BP2.bppbno AS no_proses,
			BP2.bppbno_int AS no_in_out,
			BP2.bppbdate AS tanggal,
			BP2.unit AS satuan,
			SUM(ifnull(BP2.qty,0)) AS qty,
			BP2.id_jo AS id_jo
		FROM bppb AS BP2 WHERE bppbno_int LIKE '%WIP%' AND id_supplier = '432'

	) M

	LEFT JOIN (
		SELECT 
			j.id,
			j.jo_no 
		FROM jo AS j
	) AS J ON J.id = M.id_jo

	LEFT JOIN (
		SELECT 
			jd.id,
			jd.id_so,
			jd.id_jo 
		FROM jo_det AS jd
	) AS JD ON JD.id_jo = J.id

	LEFT JOIN (
		SELECT 
			s.id,
			s.id_cost,
			s.buyerno 
		FROM so AS s
	) AS S ON S.id = JD.id_so

	LEFT JOIN (
		SELECT 
			ac.id,
			ac.kpno,
			ac.id_buyer
		FROM act_costing AS ac
	) AS AC ON AC.id = S.id_cost

	LEFT JOIN (
		SELECT 
			MS.Id_Supplier,
			MS.supplier_code,
			MS.Supplier AS nama_konsumen,
			MS.short_name,
			MS.tipe_sup,
			MS.area 
		FROM mastersupplier AS MS
	) AS SUP ON SUP.Id_Supplier = AC.id_buyer

	WHERE M.tanggal >= '$d_from' AND M.tanggal <= '$d_to'
	ORDER BY AC.kpno ASC
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	   X.kpno				LIKE'%".$searchValue."%'
	OR X.id_proses			LIKE'%".$searchValue."%'
	OR X.buyerno			LIKE'%".$searchValue."%'
	OR X.nama_konsumen		LIKE'%".$searchValue."%'
	OR X.id_order			LIKE'%".$searchValue."%'
	OR X.no_in_out			LIKE'%".$searchValue."%'
	OR X.bagian				LIKE'%".$searchValue."%'
	OR X.tanggal			LIKE'%".$searchValue."%'
	OR X.no_dokumen			LIKE'%".$searchValue."%'
	OR X.satuan				LIKE'%".$searchValue."%'
	OR X.qty				LIKE'%".$searchValue."%'
	OR X.up					LIKE'%".$searchValue."%'
	OR X.price				LIKE'%".$searchValue."%'
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
	X.kpno
	,X.id_proses
	,X.buyerno
	,X.nama_konsumen
	,X.id_order
	,X.no_in_out
	,X.bagian
	,X.tanggal
	,X.no_dokumen
	,X.satuan
	,X.qty
	,X.up
	,X.price
";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();

// echo $empQuery;
$no = 1;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];

	$data[] = array(
		"no"=>$no,
		"worksheet"=>htmlspecialchars($row['kpno']),
		"id_proses"=>htmlspecialchars($row['id_proses']),
		"id_konsumen"=>htmlspecialchars($row['buyerno']),
		"nama_konsumen"=>htmlspecialchars($row['nama_konsumen']),
		"id_order"=>htmlspecialchars($row['id_order']),
		"in/out"=>htmlspecialchars($row['no_in_out']),
		"bagian"=>htmlspecialchars($row['bagian']),
		"tgl"=>htmlspecialchars($row['tanggal']),
		"no_dokumen"=>htmlspecialchars($row['no_dokumen']),
		"satuan"=>htmlspecialchars($row['satuan']),
		"qty"=>htmlspecialchars($row['qty']),
		"up"=>htmlspecialchars($row['up']),
		"rupiah"=>htmlspecialchars($row['price'])
	);
	$no++;
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