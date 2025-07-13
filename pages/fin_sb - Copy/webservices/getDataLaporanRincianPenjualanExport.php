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
$table = "(SELECT Y.coa
		,	Y.kode_sup
		,	Y.nama_sup
		,	Y.negara
		,	Y.nomor_so
		,	Y.nomor_po
		,	Y.nomor_ws
		,	Y.id_order
		,	Y.top
		,	Y.periode
		,	Y.tgl_inv
		,	Y.nomor_inv
		,	Y.tgl_packlist
		,	Y.nomor_packlist
		,	Y.tgl_peb
		,	Y.nomor_peb
		,	Y.tgl_billoflading
		,	Y.nomor_billoflading
		,	Y.tgl_sj
		,	Y.nomor_sj
		,	Y.nomor_bc
		,	Y.kode_barang
		,	Y.warna_barang
		,	Y.nama_style
		,	Y.satuan_barang
		,	Y.qty_barang
		,	Y.original_currency
		,	Y.rate
		,	Y.harga_barang
		,	Y.total_rupiah
		,	Y.keterangan
FROM (SELECT c.id_coa coa
,		c.supplier_code kode_sup
,		c.Supplier nama_sup
,		c.country negara
,		d.so_no nomor_so
,		e.pono nomor_po
,		d.kpno nomor_ws
,		g.id_journal id_order
,		f.kode_pterms top
,		g.period periode

,		a.invdate tgl_inv
,		a.invno	nomor_inv
,		a.date_paclist tgl_packlist
,		a.v_codepaclist nomor_packlist
,		'' tgl_peb
,		'' nomor_peb
,		'' tgl_billoflading
,		'' nomor_billoflading
,		h.bppbdate tgl_sj
,		h.invno nomor_sj
,		h.bcno nomor_bc
,		i.goods_code kode_barang
,		i.Color warna_barang
,		i.Styleno nama_style
,		b.unit satuan_barang
,		b.qty qty_barang
,		d.curr original_currency
,		d.rate rate
,		b.price harga_barang
,		if(d.curr='IDR',(b.qty*b.price),((b.qty*price)*d.rate))total_rupiah
,		'' keterangan

FROM (SELECT ih.id, ih.invno, ih.invdate, ih.id_buyer, ih.id_pterms, ih.n_typeinvoice, ih.n_post, ih.n_idcoa, ic.bpbno, ih.v_codepaclist, ih.date_paclist FROM invoice_header ih LEFT JOIN invoice_commercial ic ON ic.n_idinvoiceheader=ih.id WHERE ih.n_typeinvoice='2' AND ih.n_post='2') a 
INNER JOIN (SELECT id, id_inv, id_so_det, qty, unit, price, bppbno FROM invoice_detail) b ON a.id=b.id_inv
INNER JOIN (SELECT Id_Supplier, Supplier, tipe_sup, supplier_code, short_name, id_coa, country FROM mastersupplier WHERE tipe_sup = 'C') c ON c.Id_Supplier=a.id_buyer
INNER JOIN (SELECT sod.id, sod.id_so, sod.color, sod.size, s.so_no, s.so_date, s.tax, s.curr, ac.kpno, mr.rate FROM so_det sod LEFT JOIN so s ON s.id=sod.id_so LEFT JOIN act_costing ac ON ac.id=s.id_cost LEFT JOIN masterrate mr ON mr.tanggal=s.so_date WHERE 1=1 AND mr.v_codecurr='PAJAK')d ON d.id=b.id_so_det
INNER JOIN (SELECT jod.id, jod.id_so, jod.id_jo, poi.id_po, poi.id_gen, poi.cancel, poh.pono FROM jo_det jod LEFT JOIN po_item poi ON poi.id_jo=jod.id_jo LEFT JOIN po_header poh ON poh.id=poi.id_po WHERE 1=1 AND poi.cancel!='Y' ) e ON e.id_so=d.id_so
INNER JOIN (SELECT id, kode_pterms, terms_pterms FROM masterpterms) f ON f.id=a.id_pterms
INNER JOIN (SELECT id_journal, date_journal, type_journal, fg_post, period, n_ppn, reff_doc FROM fin_journal_h WHERE fg_post='2' AND type_journal ='1' AND id_journal IS NOT NULL)g ON g.reff_doc=a.invno
INNER JOIN (SELECT bppbno_int, bppbdate, id_item, bcno, bcdate, invno, jenis_dok, confirm, no_fp, tgl_fp FROM bppb WHERE confirm='Y')h ON h.bppbno_int=a.bpbno
INNER JOIN (SELECT Styleno, itemname, Color, Size, id_item, goods_code FROM masterstyle) i ON i.id_item=h.id_item
WHERE (a.invdate <= '$d_to' and a.invdate >= '$d_from')
group by a.id_buyer, a.invno order by a.invdate)Y 
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	   X.coa             	LIKE'%".$searchValue."%'
	OR X.kode_sup            LIKE'%".$searchValue."%'
	OR X.nama_sup              		LIKE'%".$searchValue."%'
	OR X.negara                 LIKE'%".$searchValue."%'
	OR X.nomor_so               LIKE'%".$searchValue."%'
	OR X.nomor_po              	LIKE'%".$searchValue."%'
	OR X.nomor_ws             	LIKE'%".$searchValue."%'
	OR X.id_order             	LIKE'%".$searchValue."%'
	OR X.top            		LIKE'%".$searchValue."%'
	OR X.periode				LIKE'%".$searchValue."%'
	OR X.tgl_inv			LIKE'%".$searchValue."%'
	OR X.nomor_inv			LIKE'%".$searchValue."%'
	OR X.tgl_packlist       LIKE'%".$searchValue."%'
	OR X.nomor_packlist		LIKE'%".$searchValue."%'
	OR X.tgl_peb                LIKE'%".$searchValue."%'	
	OR X.nomor_peb              LIKE'%".$searchValue."%'	
	OR X.tgl_billoflading     LIKE'%".$searchValue."%'	
	OR X.nomor_billoflading   LIKE'%".$searchValue."%'	
	OR X.tgl_sj  			 LIKE'%".$searchValue."%'	
	OR X.nomor_sj  			 LIKE'%".$searchValue."%'		
	OR X.nomor_bc               LIKE'%".$searchValue."%'	
	OR X.kode_barang            LIKE'%".$searchValue."%'	
	OR X.warna_barang           LIKE'%".$searchValue."%'	
	OR X.nama_style             LIKE'%".$searchValue."%'	
	OR X.satuan_barang                	LIKE'%".$searchValue."%'	
	OR X.qty_barang                	LIKE'%".$searchValue."%'	
	OR X.original_currency      LIKE'%".$searchValue."%'	
	OR X.rate                	LIKE'%".$searchValue."%'	
	OR X.harga_barang                	LIKE'%".$searchValue."%'	
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
,	   X.kode_sup            
,	   X.nama_sup              		
,	   X.negara                 
,	   X.nomor_so               
,	   X.nomor_po              	
,	   X.nomor_ws             	
,	   X.id_order             	
,	   X.top            		
,	   X.periode					
,	   X.tgl_inv			
,	   X.nomor_inv			
,	   X.tgl_packlist       
,	   X.nomor_packlist		
,	   X.tgl_peb                
,	   X.nomor_peb              
,	   X.tgl_billoflading     
,	   X.nomor_billoflading   
,	   X.tgl_sj        
,	   X.nomor_sj      
,	   X.nomor_bc               
,	   X.kode_barang            
,	   X.warna_barang           
,	   X.nama_style             
,	   X.satuan_barang                	
,	   X.qty_barang                	
,	   X.original_currency      
,	   X.rate                	
,	   X.harga_barang                	
,	   X.total_rupiah           
,	   X.keterangan             
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
"negara"=>htmlspecialchars($row['negara']),
"nomor_so"=>htmlspecialchars($row['nomor_so']),
"nomor_po"=>htmlspecialchars($row['nomor_po']),
"nomor_ws"=>htmlspecialchars($row['nomor_ws']),
"id_order"=>htmlspecialchars($row['id_order']),
"top"=>htmlspecialchars($row['top']),
"periode"=>htmlspecialchars($row['periode']),
"tgl_inv"=>htmlspecialchars($row['tgl_inv']),
"nomor_inv"=>htmlspecialchars($row['nomor_inv']),
"tgl_packlist"=>htmlspecialchars($row['tgl_packlist']),
"nomor_packlist"=>htmlspecialchars($row['nomor_packlist']),
"tgl_peb"=>htmlspecialchars($row['tgl_peb']),
"nomor_peb"=>htmlspecialchars($row['nomor_peb']),
"tgl_billoflading"=>htmlspecialchars($row['tgl_billoflading']),
"nomor_billoflading"=>htmlspecialchars($row['nomor_billoflading']),
"tgl_sj"=>htmlspecialchars($row['tgl_sj']),
"nomor_sj"=>htmlspecialchars($row['nomor_sj']),
"nomor_bc"=>htmlspecialchars($row['nomor_bc']),
"kode_barang"=>htmlspecialchars($row['kode_barang']),
"warna_barang"=>htmlspecialchars($row['warna_barang']),
"nama_style"=>htmlspecialchars($row['nama_style']),
"satuan_barang"=>htmlspecialchars($row['satuan_barang']),
"qty_barang"=>htmlspecialchars($row['qty_barang']),
"original_currency"=>htmlspecialchars($row['original_currency']),
"rate"=>htmlspecialchars(number_format((float)$row['rate'], 2, '.', ',')),
"harga_barang"=>htmlspecialchars(number_format((float)$row['harga_barang'], 2, '.', ',')),
"total_rupiah"=>htmlspecialchars(number_format((float)$row['total_rupiah'], 2, '.', ',')),
"keterangan"=>htmlspecialchars($row['keterangan']),

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