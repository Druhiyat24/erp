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
	SELECT 
		M.id,
		'N/A' AS coa,
		M.id_so_det,
		M.supplier_code,
		M.Supplier,
		M.short_name,
		M.so_no,
		M.buyerno,
		M.kpno,
		'N/A' AS id_order,
		ifnull(M.top, 'N/A') top,
		M.periode,
		ifnull(M.invdate, 'N/A') AS inv_tgl,
		ifnull(M.invno, 'N/A') AS inv_no,
		'N/A' AS ex_tgl,
		'N/A' AS ex_no,
		'N/A' AS mkt_tgl,
		'N/A' AS mkt_no,
		M.bpbno_int,
		M.bppbno_ri,
		M.kode,
		M.warna,
		M.style, 
		ROUND((M.qty),2) AS qty,
		M.unit,
		ROUND((M.price_usd),2) AS price_usd,
		ROUND((M.kurs),2) AS kurs,
		ROUND((M.price_idr),2) AS price_idr,
		ROUND((M.qty*M.price_idr),2) AS total_rupiah,
		'-' AS keterangan,
		M.sj,
		M.BKBB,
		M.id_item,
		M.id_jo,
		M.id idd,
		M.id_bkb,
		M.from_to
	FROM (
		SELECT   
			BPB.id,
			BPB.bpbno_int,
			BPB.bppbno_ri,
			(BKB.bppbno_int) AS sj,
			IV.BKBB,
			(BPB.qty)qty,
			BPB.unit,
			BPB.price price_bpb,
			BPB.id_item,
			BPB.id_jo,
			CONCAT(SUBSTR(BPB.bpbdate, 6, 2), '/', SUBSTR(BPB.bpbdate, 1, 4)) AS periode,
			BPB.bpbdate AS from_to,
			BKB.id_bkb,
			BKB.id_so_det,
			BKB.bppbno_int,
			SD.goods_code AS kode,
			CONCAT(SD.Color,' (',SD.size,')') AS warna,
			SD.Styleno AS style,
			S.curr,
			IF(S.curr='USD' ,SD.price, '0') AS price_usd,
			MR.rate AS kurs,
			IF(S.curr='IDR', SD.price, SD.price*MR.rate) AS price_idr,
			BKB.supplier_code,
			BKB.Supplier,
			BKB.short_name,
			BKB.tipe_sup,
			BKB.area,
			S.so_no,
			S.buyerno,
			AC.kpno,
			MPT.kode_pterms AS top,
			IVH.invdate,
			IVH.invno
		FROM bpb AS BPB
		
		INNER JOIN (
			SELECT 
				id,
				bppbno,
				bppbno_int, 
				id_item,
				id_jo,
				id AS id_bkb,
				id_so_det,
				msup.Id_Supplier,
				msup.supplier_code,
				msup.Supplier,
				msup.short_name,
				msup.tipe_sup,
				msup.area
			FROM bppb
			INNER JOIN mastersupplier AS msup ON msup.Id_Supplier=bppb.id_supplier
			WHERE msup.tipe_sup = 'C'
		) AS BKB ON BPB.bppbno_ri = BKB.bppbno AND BPB.id_so_det = BKB.id_so_det
		
		LEFT JOIN (
			SELECT
				sd.id,
				sd.id_so,
				ms.goods_code,
				ms.Color,
				ms.Styleno,
				sd.price,
				sd.size
			FROM so_det AS sd
			INNER JOIN masterstyle AS ms ON ms.id_so_det = sd.id
		) AS SD ON SD.id = BKB.id_so_det
		
		LEFT JOIN (
			SELECT s.id,
				s.id_cost,
				s.so_no,
				s.buyerno,
				s.curr,
				s.id_terms
			FROM so AS s
		) AS S ON S.id=SD.id_so
		
		LEFT JOIN (
			SELECT ac.id,
				ac.kpno,
				ac.id_buyer
			FROM act_costing AS ac
		) AS AC ON AC.id = S.id_cost
		
		LEFT JOIN (
			SELECT 
				mr.v_codecurr,
				mr.tanggal,
				mr.rate 
			FROM masterrate AS mr
		) AS MR ON MR.tanggal = BPB.bpbdate
		
		LEFT JOIN (	
			SELECT 
				M.BKB AS BKBB,
				M.id_invoice
			FROM ( 
				SELECT 
					ivc.n_id AS id,
					ivc.n_idinvoiceheader AS id_invoice,
					ivc.bpbno AS BKB
				FROM invoice_commercial AS ivc
				WHERE ivc.bpbno IS NOT NULL OR ivc.bpbno != ''
		
				UNION ALL
		
				SELECT 
					ivd.id AS id,
					ivd.id_inv AS id_invoice,
					ivd.bppbno AS BKB 
				FROM invoice_detail AS ivd
				WHERE ivd.bppbno IS NOT NULL OR ivd.bppbno != ''
				GROUP BY ivd.id_inv
			) AS M WHERE M.BKB != ''
			
		) AS IV ON TRIM(IV.BKBB) =  TRIM(BKB.bppbno_int)
		
		LEFT JOIN (
			SELECT ivh.id,
				ivh.invdate,
				ivh.invno,
				ivh.n_post,
				ivh.id_pterms
			FROM invoice_header AS ivh
			WHERE n_post = '2'
		) AS IVH ON IVH.id = IV.id_invoice
		
		LEFT JOIN (
			SELECT 
				mpt.id,
				mpt.kode_pterms 
			FROM masterpterms AS mpt
		) AS MPT ON MPT.id = IVH.id_pterms
		
		WHERE BPB.bppbno_ri is not null AND BPB.bpbno_int IS NOT NULL AND BPB.bppbno_ri != ''
		GROUP BY BKB.id_so_det
		#GROUP BY BPB.id_jo,BPB.id_item
	)M WHERE M.from_to>='{$d_from}' AND M.from_to<='{$d_to}'
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	   X.from_to			LIKE'%".$searchValue."%'
	OR X.coa				LIKE'%".$searchValue."%'
	OR X.supplier_code		LIKE'%".$searchValue."%'
	OR X.Supplier			LIKE'%".$searchValue."%'
	OR X.short_name			LIKE'%".$searchValue."%'
	OR X.so_no				LIKE'%".$searchValue."%'
	OR X.buyerno			LIKE'%".$searchValue."%'
	OR X.kpno				LIKE'%".$searchValue."%'
	OR X.id_order			LIKE'%".$searchValue."%'
	OR X.top				LIKE'%".$searchValue."%'
	OR X.periode			LIKE'%".$searchValue."%'
	OR X.from_to			LIKE'%".$searchValue."%'
	OR X.bpbno_int			LIKE'%".$searchValue."%'
	OR X.sj					LIKE'%".$searchValue."%'
	OR X.inv_tgl			LIKE'%".$searchValue."%'
	OR X.inv_no				LIKE'%".$searchValue."%'
	OR X.ex_tgl				LIKE'%".$searchValue."%'
	OR X.ex_no				LIKE'%".$searchValue."%'
	OR X.mkt_tgl			LIKE'%".$searchValue."%'
	OR X.mkt_no				LIKE'%".$searchValue."%'
	OR X.kode				LIKE'%".$searchValue."%'
	OR X.warna				LIKE'%".$searchValue."%'
	OR X.style				LIKE'%".$searchValue."%'
	OR X.unit				LIKE'%".$searchValue."%'
	OR X.qty				LIKE'%".$searchValue."%'
	OR X.price_usd			LIKE'%".$searchValue."%'
	OR X.kurs				LIKE'%".$searchValue."%'
	OR X.price_idr			LIKE'%".$searchValue."%'
	OR X.total_rupiah		LIKE'%".$searchValue."%'
	OR X.keterangan			LIKE'%".$searchValue."%'
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
	X.from_to
	,X.coa
	,X.supplier_code
	,X.Supplier
	,X.short_name
	,X.so_no
	,X.buyerno
	,X.kpno
	,X.id_order
	,X.top
	,X.periode
	,X.from_to
	,X.bpbno_int
	,X.sj
	,X.inv_tgl
	,X.inv_no
	,X.ex_tgl
	,X.ex_no
	,X.mkt_tgl
	,X.mkt_no
	,X.kode
	,X.warna
	,X.style
	,X.unit
	,X.qty
	,X.price_usd
	,X.kurs
	,X.price_idr
	,X.total_rupiah
	,X.keterangan
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
		"coa"=>htmlspecialchars($row['coa']),
		"id_konsumen"=>htmlspecialchars($row['supplier_code']),
		"nama"=>htmlspecialchars($row['Supplier']),
		"alias"=>htmlspecialchars($row['short_name']),
		"so_no"=>htmlspecialchars($row['so_no']),
		"buyerno"=>htmlspecialchars($row['buyerno']),
		"kpno"=>htmlspecialchars($row['kpno']),
		"id_order"=>htmlspecialchars($row['id_order']),
		"top"=>htmlspecialchars($row['top']),
		"period"=>htmlspecialchars($row['periode']),
		"bppbdate"=>htmlspecialchars($row['from_to']),
		"bppbno"=>htmlspecialchars($row['bpbno_int']),
		"invdate"=>htmlspecialchars($row['inv_tgl']),
		"invno"=>htmlspecialchars($row['inv_no']),
		"tgl_pajak"=>htmlspecialchars($row['ex_tgl']),
		"no_pajak"=>htmlspecialchars($row['ex_no']),
		"retur_mk"=>htmlspecialchars($row['mkt_tgl']),
		"no_retur"=>htmlspecialchars($row['mkt_no']),
		"kode"=>htmlspecialchars($row['kode']),
		"warna"=>htmlspecialchars($row['warna']),
		"style"=>htmlspecialchars($row['style']),
		"satuan"=>htmlspecialchars($row['unit']),
		"qty"=>htmlspecialchars($row['qty']),
		"usd"=>htmlspecialchars(number_format((float)$row['price_usd'], 2, '.', ',')),
		"rate"=>htmlspecialchars(number_format((float)$row['kurs'], 2, '.', ',')),
		"idr"=>htmlspecialchars(number_format((float)$row['price_idr'], 2, '.', ',')),
		"total_rupiah"=>htmlspecialchars(number_format((float)$row['total_rupiah'], 2, '.', ',')),
		"keterangan"=>htmlspecialchars($row['keterangan'])
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