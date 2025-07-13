<?php 

ini_set('max_execution_time', '300'); //300 seconds = 5 minutes

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
$table = "
(SELECT  IJ.id_coa coa
		,	Y.kode_sup
		,	Y.id_inv_det
		,	Y.j_p
		,	Y.id_inv_h
		,	Y.nama_sup
		,	Y.alias
		,	Y.nomor_so
		,	Y.nomor_po
		,	Y.nomor_ws
		,	Y.id_order
		,	Y.top
		,	Y.periode
		,	IF(Y.tgl_sj IS NULL , 'N/A', Y.tgl_sj)tgl_sj	
		,	Y.nomor_sj
		,	Y.tgl_inv
		,	Y.nomor_inv
		,	IF(Y.tgl_fp IS NULL , 'N/A', Y.tgl_fp)tgl_fp	
		,	Y.nomor_fp
		,	IF(Y.nomor_bc IS NULL , 'N/A', Y.nomor_bc)nomor_bc	
		,	Y.kode_barang
		,	Y.warna_barang
		,	Y.ukuran_barang
		,	Y.nama_style
		,	Y.satuan_barang
		,	Y.qty_barang
		,	Y.harga_barang
		,	Y.ppn
		,	Y.curr
		,	Y.rate
		,	Y.total_rupiah
		,	Y.keterangan
FROM (SELECT a.n_idcoa coa_old
,		c.supplier_code kode_sup
,		c.Supplier nama_sup
,		c.short_name alias
,		d.so_no nomor_so
,		e.pono nomor_po
,		d.kpno nomor_ws
,		if(d.buyerno='-','N/A',d.buyerno) id_order
,		g.id_journal j_p
,		f.kode_pterms top
,		g.period periode
,		a.bppbdate tgl_sj
,		b.bppbno nomor_sj
,		b.id id_inv_det
,		b.id_inv id_inv_h
,		a.invdate tgl_inv
,		a.invno	nomor_inv
,		if(a.tgl_fp='0000-00-00','N/A',IF(a.tgl_fp='','N/A',a.tgl_fp))tgl_fp
,		if(a.v_fakturpajak='-','N/A',IF(a.v_fakturpajak='','N/A',a.v_fakturpajak))nomor_fp
,		if(a.bcno='','N/A',a.bcno) nomor_bc
,		d.product_group kode_barang
,		d.color warna_barang
,		d.Size ukuran_barang
,		d.styleno nama_style
,		b.unit satuan_barang
,		b.qty qty_barang
,		b.price harga_barang
,		if(d.tax='0','',(0.1*b.qty*b.price)) ppn
,		d.curr curr
,		mr.rate rate
,		if(d.curr='IDR',((b.qty*b.price)+(d.tax/100*b.qty*b.price)),((b.qty*price)*mr.rate+(d.tax/100*b.qty*b.price*mr.rate)))total_rupiah
,		'' keterangan

FROM (SELECT ih.id id, ih.invno invno, ih.invdate invdate, ih.id_buyer id_buyer, ih.id_pterms id_pterms, ih.n_typeinvoice n_typeinvoice, ih.v_fakturpajak v_fakturpajak, ih.n_post n_post, ih.n_idcoa n_idcoa, ic.bpbno bpbno, h.bppbdate bppbdate, h.tgl_fp tgl_fp, h.bcno bcno, h.id_item_bppb id_item_bppb
FROM invoice_header ih 
LEFT JOIN invoice_commercial ic ON ic.n_idinvoiceheader=ih.id 
LEFT JOIN (SELECT MAX(bppbno_int) bppbno_int, MAX(bppbdate) bppbdate, MAX(id_item) id_item, MAX(bcno) bcno, MAX(bcdate) bcdate, MAX(invno) invno, MAX(jenis_dok) jenis_dok, MAX(confirm) confirm, MAX(no_fp) no_fp, MAX(tgl_fp) tgl_fp, MAX(id_item) id_item_bppb FROM bppb WHERE SUBSTR(bppbno,4,2)='FG' AND confirm='Y' GROUP BY bppbno )h ON h.bppbno_int=ic.bpbno
WHERE ih.n_typeinvoice='1' AND ih.n_post='2') a 
LEFT JOIN (SELECT id id, id_inv id_inv, id_so_det id_so_det, qty qty, unit unit, price price, bppbno bppbno FROM invoice_detail) b ON a.id=b.id_inv
LEFT JOIN masterrate mr ON mr.tanggal=a.invdate 
LEFT JOIN (SELECT Id_Supplier Id_Supplier, Supplier Supplier, tipe_sup tipe_sup, supplier_code supplier_code, short_name short_name FROM mastersupplier WHERE tipe_sup = 'C') c ON c.Id_Supplier=a.id_buyer
LEFT JOIN (
		SELECT s.buyerno buyerno
		, sod.id id
		, sod.id_so id_so
		, sod.qty qty
		, sod.color color
		, sod.size size
		, s.so_no so_no
		, s.so_date so_date
		, s.tax tax
		, s.curr curr
		, ac.kpno kpno
		, ac.styleno
		, mp.product_group 
		FROM so_det sod 
		LEFT JOIN so s ON s.id=sod.id_so 
		LEFT JOIN act_costing ac ON ac.id=s.id_cost 
		LEFT JOIN masterproduct mp ON mp.id=ac.id_product
		WHERE 1=1 )d ON d.id=b.id_so_det
LEFT JOIN (SELECT jod.id id, jod.id_so id_so, jod.id_jo id_jo, poi.id_po id_po, poi.id_gen id_gen, poi.cancel cancel, poh.pono pono FROM jo_det jod LEFT JOIN po_item poi ON poi.id_jo=jod.id_jo LEFT JOIN po_header poh ON poh.id=poi.id_po WHERE 1=1 AND poi.cancel!='Y' ) e ON e.id_so=d.id_so
LEFT JOIN (SELECT id id, kode_pterms kode_pterms, terms_pterms terms_pterms FROM masterpterms) f ON f.id=a.id_pterms
LEFT JOIN (SELECT id_journal id_journal, date_journal date_journal, type_journal type_journal, fg_post fg_post, period period, n_ppn n_ppn, reff_doc reff_doc FROM fin_journal_h WHERE fg_post='2' AND type_journal ='1' AND id_journal IS NOT NULL)g ON g.reff_doc=a.invno



#WHERE (a.invdate >= '2020-01-01' and a.invdate <= '2020-06-01') AND mr.v_codecurr='PAJAK'
WHERE (a.invdate <= '$d_to' and a.invdate >= '$d_from')
#GROUP BY a.invno
ORDER BY c.Supplier ASC, d.so_no ASC, e.pono ASC, d.kpno ASC)Y

LEFT JOIN(SELECT id_journal,id_coa,nm_coa FROm fin_journal_d WHERE id_journal LIKE '%IJ%' AND debit >0 GROUP BY id_journal)IJ ON IJ.id_journal = Y.j_p


 GROUP BY Y.id_inv_det ORDER BY Y.id_inv_h ASC 
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	   X.coa             	LIKE'%".$searchValue."%'
	OR X.kode_sup            LIKE'%".$searchValue."%'
	OR X.nama_sup            LIKE'%".$searchValue."%'
	OR X.alias                   LIKE'%".$searchValue."%'
	OR X.nomor_so                  LIKE'%".$searchValue."%'
	OR X.nomor_po               LIKE'%".$searchValue."%'
	OR X.nomor_ws             	LIKE'%".$searchValue."%'
	OR X.id_order             	LIKE'%".$searchValue."%'
	OR X.top            	LIKE'%".$searchValue."%'
	OR X.periode					LIKE'%".$searchValue."%'
	OR X.tgl_sj				  	LIKE'%".$searchValue."%'
	OR X.nomor_sj		LIKE'%".$searchValue."%'
	OR X.tgl_inv        LIKE'%".$searchValue."%'
	OR X.nomor_inv			LIKE'%".$searchValue."%'
	OR X.tgl_fp        LIKE'%".$searchValue."%'	
	OR X.nomor_fp            LIKE'%".$searchValue."%'	
	OR X.nomor_bc          LIKE'%".$searchValue."%'	
	OR X.kode_barang       LIKE'%".$searchValue."%'	
	OR X.warna_barang     LIKE'%".$searchValue."%'	
	OR X.nama_style            LIKE'%".$searchValue."%'	
	OR X.satuan_barang           LIKE'%".$searchValue."%'	
	OR X.qty_barang             LIKE'%".$searchValue."%'	
	OR X.harga_barang                	LIKE'%".$searchValue."%'	
	OR X.ppn                	LIKE'%".$searchValue."%'		
	OR X.total_rupiah           LIKE'%".$searchValue."%'	
	OR X.keterangan             LIKE'%".$searchValue."%'	
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
	   X.coa             
,	 X.kode_sup          
,	 X.nama_sup          
,	 X.alias                            
,	 X.nomor_so           
,	 X.nomor_po           
,	 X.nomor_ws           
,	 X.id_order           
,	 X.top			
,	 X.periode				
,	 X.tgl_sj	
,	 X.nomor_sj      
,	 X.tgl_inv		
,	 X.nomor_inv      
,	 X.tgl_fp          
,	 X.nomor_fp        
,	 X.nomor_bc                 
,	 X.kode_barang          
,	 X.warna_barang                  
,	 X.nama_style                	
,	 X.satuan_barang                	
,	 X.qty_barang                
,	 X.harga_barang                	
,	 X.ppn         
,	 X.total_rupiah         
,	 X.keterangan           
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
// echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];


   $data[] = array(
"coa"=>htmlspecialchars($row['coa']),
"kode_sup"=>htmlspecialchars($row['kode_sup']),
"nama_sup"=>htmlspecialchars($row['nama_sup']),
"alias"=>htmlspecialchars($row['alias']),
"nomor_so"=>htmlspecialchars($row['nomor_so']),
"id_order"=>htmlspecialchars($row['id_order']),
"nomor_ws"=>htmlspecialchars($row['nomor_ws']),
"nomor_po"=>htmlspecialchars($row['nomor_po']),
"top"=>htmlspecialchars($row['top']),
"periode"=>htmlspecialchars($row['periode']),
"tgl_sj"=>htmlspecialchars($row['tgl_sj']),
"nomor_sj"=>htmlspecialchars($row['nomor_sj']),
"tgl_inv"=>htmlspecialchars($row['tgl_inv']),
"nomor_inv"=>htmlspecialchars($row['nomor_inv']),
"tgl_fp"=>htmlspecialchars($row['tgl_fp']),
"nomor_fp"=>htmlspecialchars($row['nomor_fp']),
"nomor_bc"=>htmlspecialchars($row['nomor_bc']),
"kode_barang"=>htmlspecialchars($row['kode_barang']),
"warna_barang"=>htmlspecialchars($row['warna_barang']),
"nama_style"=>htmlspecialchars($row['nama_style']),
"satuan_barang"=>htmlspecialchars($row['satuan_barang']),
"qty_barang"=>htmlspecialchars(number_format((float)$row['qty_barang'], 2, '.', ',')),
"harga_barang"=>htmlspecialchars(number_format((float)$row['harga_barang'], 2, '.', ',')),
"ppn"=>htmlspecialchars(number_format((float)$row['ppn'], 2, '.', ',')),
"total_rupiah"=>htmlspecialchars(number_format((float)$row['total_rupiah'], 2, '.', ',')),
"keterangan"=>htmlspecialchars($row['keterangan'])

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