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
		Y.kode_barang,
		Y.nama_barang,
		Y.jenis_barang,
		Y.worksheet,
		Y.style,
		Y.id_konsumen,
		Y.nama_konsumen,
		Y.id_order,
		Y.bpbno_int,
		Y.bpbdate,
		Y.bcno,
		Y.bcdate,
		Y.no_kontrak,
		Y.tgl_kontrak,
		Y.pono,
		Y.podate,
		Y.total_qty,
		Y.realisasi,
		Y.outfix outstanding,
		Y.so_no,
		Y.sj,
		Y.tgl_sj,
		Y.unit,
		Y.qty
	FROM (	
		SELECT 
			BP.id,
			BP.bpbno,
			BP.bpbno_int,
			BP.id_item AS item_BPB,
			BP.id_supplier sup_BPB,
			BP.bpbdate,
			if(BP.bcno = '', 'N/A', BP.bcno) AS bcno,
			if(BP.bcdate = '0000-00-00' or BP.bcdate is null or BP.bcdate = '1970-01-01', 'N/A', BP.bcdate) AS bcdate,
			BP.invno AS sj,
			BP.bpbdate AS tgl_sj,
			MI.id_item,
			MI.goods_code AS kode_barang,
			MI.itemdesc AS nama_barang,
			MI.matclass AS jenis_barang,
			AC.kpno AS worksheet,
			if(AC.styleno = '' or AC.styleno is null, 'N/A', AC.styleno) AS style,
			if(CUST.supplier_code = '' or CUST.supplier_code is null, 'N/A', CUST.supplier_code) AS id_konsumen,
			if(CUST.Supplier = '' or CUST.Supplier is null, 'N/A', CUST.Supplier) AS nama_konsumen,
			'N/A' AS id_order,
			'N/A' AS no_kontrak,
			'N/A' AS tgl_kontrak,
			#POH.id AS id_po,
			POH.pono,
			POH.podate,
			POI.qty AS total_qty,
			(ifnull(BP.qty,0) - ifnull(BP.qty_reject,0)) AS realisasi,
			(POI.qty - (ifnull(BP.qty,0) - ifnull(BP.qty_reject,0))) AS outstanding,
			if(POI.qty - (ifnull(BP.qty,0) - ifnull(BP.qty_reject,0)) < 0, concat('(',-1 * (POI.qty - (ifnull(BP.qty,0) - ifnull(BP.qty_reject,0))),')'), (POI.qty - (ifnull(BP.qty,0) - ifnull(BP.qty_reject,0)))) AS outfix,
			if(S.so_no = '' or S.so_no is null, 'N/A', S.so_no) so_no,
			POI.unit,
			(ifnull(BP.qty,0) - ifnull(BP.qty_reject,0)) AS qty
		FROM bpb AS BP
		
		LEFT JOIN (
			SELECT 
				mi.id_item,
				mi.goods_code,
				mi.itemdesc,
				mi.matclass 
			FROM masteritem AS mi
		) AS MI ON MI.id_item = BP.id_item
		
		LEFT JOIN (
			SELECT 
				j.id,
				j.jo_no 
			FROM jo AS j
		) AS J ON J.id = BP.id_jo
		
		LEFT JOIN (
			SELECT 
				jod.id,
				MAX(jod.id_so)id_so,
				MAX(jod.id_jo)id_jo 
			FROM jo_det AS jod
			
				WHERE jod.cancel ='N'
				GROUP BY jod.id_so
		) AS JOD ON JOD.id_jo = BP.id_jo
		
		LEFT JOIN (
			SELECT 
				s.id,
				s.id_cost,
				s.so_no 
			FROM so AS s
		) AS S ON S.id = JOD.id_so
		
		LEFT JOIN (
			SELECT 
				ac.id,
				ac.kpno,
				ac.id_buyer,
				ac.styleno 
			FROM act_costing AS ac
		) AS AC ON AC.id = S.id_cost
		
		LEFT JOIN (
			SELECT 
				cust.Id_Supplier,
				cust.supplier_code,
				cust.Supplier 
			FROM mastersupplier AS cust
		) AS CUST ON CUST.Id_Supplier = AC.id_buyer
		
		LEFT JOIN (
			SELECT 
				poi.id,
				poi.id_po,
				SUM(poi.qty) qty,
				poi.unit,
				poi.price,
				poi.curr,
				poi.cancel
			FROM po_item AS poi
			WHERE poi.cancel = 'N'
			GROUP BY poi.id_gen,poi.id_po
		) AS POI ON POI.id = BP.id_po_item
		
		LEFT JOIN (
			SELECT 
				poh.id,
				poh.pono,
				poh.podate,
				poh.ppn
			FROM po_header AS poh
		) AS POH ON POH.id = POI.id_po
		
		LEFT JOIN (
			SELECT 
				mr.tanggal,
				mr.curr,
				mr.rate
			FROM masterrate AS mr
		) AS MR ON MR.tanggal = BP.bpbdate
		
		WHERE BP.bpbno LIKE '%C%' AND BP.id_supplier != '432' AND BP.pono IS NOT NULL AND BP.id_po_item IS NOT NULL AND BP.confirm = 'Y'
		AND MI.matclass = 'CMT' AND POI.id IS NOT NULL AND POH.id IS NOT NULL
		GROUP BY BP.id_item,BP.bpbno_int
		ORDER BY POH.pono
		#GROUP BY POH.id

	) AS Y WHERE Y.bpbdate >= '{$d_from}' AND Y.bpbdate <= '{$d_to}'
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	   X.kode_barang     LIKE'%".$searchValue."%'
	OR X.nama_barang     LIKE'%".$searchValue."%'
	OR X.jenis_barang    LIKE'%".$searchValue."%'
	OR X.worksheet       LIKE'%".$searchValue."%'
	OR X.style           LIKE'%".$searchValue."%'
	OR X.id_konsumen     LIKE'%".$searchValue."%'
	OR X.nama_konsumen   LIKE'%".$searchValue."%'
	OR X.id_order        LIKE'%".$searchValue."%'
	OR X.bpbno_int       LIKE'%".$searchValue."%'
	OR X.bpbdate         LIKE'%".$searchValue."%'
	OR X.bcno            LIKE'%".$searchValue."%'
	OR X.bcdate          LIKE'%".$searchValue."%'
	OR X.no_kontrak      LIKE'%".$searchValue."%'
	OR X.tgl_kontrak     LIKE'%".$searchValue."%'
	OR X.pono            LIKE'%".$searchValue."%'
	OR X.podate          LIKE'%".$searchValue."%'
	OR X.total_qty       LIKE'%".$searchValue."%'
	OR X.realisasi       LIKE'%".$searchValue."%'
	OR X.outstanding     LIKE'%".$searchValue."%'
	OR X.so_no           LIKE'%".$searchValue."%'
	OR X.sj              LIKE'%".$searchValue."%'
	OR X.tgl_sj          LIKE'%".$searchValue."%'
	OR X.unit            LIKE'%".$searchValue."%'
	OR X.qty             LIKE'%".$searchValue."%'
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
	X.kode_barang                   
	,X.nama_barang            	
	,X.jenis_barang                 	
	,X.worksheet                  	
	,X.style    
	,X.id_konsumen                   	
	,X.nama_konsumen
	,X.id_order		
	,X.bpbno_int
	,X.bpbdate	
	,X.bcno
	,X.bcdate                    	
	,X.no_kontrak	
	,X.tgl_kontrak	
	,X.pono	
	,X.podate	
	,X.total_qty	
	,X.realisasi	
	#,X.outstanding	
	,X.so_no	
	,X.sj	
	,X.tgl_sj	
	,X.unit	
	,X.qty	
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
		"kode_barang"=>htmlspecialchars($row['kode_barang']),
		"nama_barang"=>htmlspecialchars($row['nama_barang']),
		"jenis_barang"=>htmlspecialchars($row['jenis_barang']),
		"worksheet"=>htmlspecialchars($row['worksheet']),
		"style"=>htmlspecialchars($row['style']),
		"id_konsumen"=>htmlspecialchars($row['id_konsumen']),
		"nama_konsumen"=>htmlspecialchars($row['nama_konsumen']),
		"id_order"=>htmlspecialchars($row['id_order']),
		"bpbno_int"=>htmlspecialchars($row['bpbno_int']),
		"bpbdate"=>htmlspecialchars($row['bpbdate']),
		"bcno"=>htmlspecialchars($row['bcno']),
		"bcdate"=>htmlspecialchars($row['bcdate']),
		"no_kontrak"=>htmlspecialchars($row['no_kontrak']),
		"tgl_kontrak"=>htmlspecialchars($row['tgl_kontrak']),
		"pono"=>htmlspecialchars($row['pono']),
		"podate"=>htmlspecialchars($row['podate']),
		"total_qty"=>htmlspecialchars(number_format((float)$row['total_qty'], 2, '.', ',')),
		"realisasi"=>htmlspecialchars(number_format((float)$row['realisasi'], 2, '.', ',')),
		// "outstanding"=>htmlspecialchars(number_format((float)$row['outstanding'], 2, '.', ',')),
		"so_no"=>htmlspecialchars($row['so_no']),
		"sj"=>htmlspecialchars($row['sj']),
		"tgl_sj"=>htmlspecialchars($row['tgl_sj']),
		"unit"=>htmlspecialchars($row['unit']),
		"qty"=>htmlspecialchars(number_format((float)$row['qty'], 2, '.', ','))
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