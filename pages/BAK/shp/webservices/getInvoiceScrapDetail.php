<?php 
include __DIR__ .'/../../../include/conn.php';
include __DIR__ .'/../../forms/fungsi.php';
## Read value
$draw = $_GET['draw'];
$row = $_GET['start'];/* 
print_r($_GET); */
$id_bkb = $_GET['id_bkb'];
$id_url = $_GET['id_url'];
$bkb_no = flookup("bppbno_int","bppb","id='{$id_bkb}'");
if($id_url =="XX"){
	$table = "(SELECT 
	 '0' id
	,'0' id_inv_sc
	,BKB.id id_bkb
	,BKB.bppbno_int bkb
	,BKB.curr
	,BKB.id_item
	,BKB.unit
	,BKB.qty qty_bkb
	,BKB.id_jo
	,'0' qty_exist
	,'0' price_exist	
	,BKB.price price_bkb
	,MS.itemdesc tipe_scrap
	,((BKB.qty)-(ifnull(ISC.qty,0))) stock 
	,'0' discount
	FROM bppb BKB
	INNER JOIN stock ST ON ST.id_item = BKB.id_item
	INNER JOIN masteritem MS ON MS.id_item = BKB.id_item
	LEFT JOIN (SELECT SUM(IFNULL(A.qty,0))qty,MAX(A.id_item)id_item FROM shp_invoice_scrap_detail A WHERE 1=1 GROUP BY A.id_item)ISC
	ON BKB.id_item = ISC.id_item
WHERE BKB.bppbno_int = '{$bkb_no}' AND ST.mattype = 'S'
)X

";
}else{
	$table = "(SELECT 
	 EXIST.id
	,EXIST.id_inv_sc
	,BKB.id id_bkb
	,BKB.bppbno_int bkb
	,EXIST.curr
	,EXIST.id_item
	,EXIST.unit
	,BKB.qty qty_bkb
	,EXIST.qty qty_exist
	,EXIST.price price_exist
	,BKB.price price_bkb
	,MS.itemdesc tipe_scrap
	,EXIST.id_jo
	,((BKB.qty)-(ifnull(ISC.qty,0)) + (EXIST.qty) ) stock 
	,EXIST.discount
	FROM bppb BKB
	INNER JOIN stock ST ON ST.id_item = BKB.id_item
	INNER JOIN masteritem MS ON MS.id_item = BKB.id_item
	INNER JOIN (SELECT curr,id,id_inv_sc,id_bppb,id_item,unit,qty,price,id_jo,discount FROM shp_invoice_scrap_detail WHERE id_inv_sc='{$id_url}')EXIST ON BKB.id = EXIST.id_bppb
	LEFT JOIN (SELECT SUM(IFNULL(A.qty,0))qty,MAX(A.id_item)id_item FROM shp_invoice_scrap_detail A WHERE 1=1 GROUP BY A.id_item)ISC
	ON BKB.id_item = ISC.id_item	
	INNER JOIN shp_invoice_scrap_header EXIST_H ON EXIST_H.id = EXIST.id_inv_sc
WHERE BKB.bppbno_int = '{$bkb_no}' AND ST.mattype = 'S'
)X";
}
	$colomn = " 
		 X.id
		 ,X.id_jo
		 ,X.discount
		,X.id_inv_sc
		,X.id_item
		,X.id_bkb
		,X.curr
		,X.bkb
		,X.qty_exist
		,X.price_exist		
		,X.tipe_scrap
		,X.stock
		,X.unit
		,X.qty_bkb
		,X.price_bkb
		
	";
$rowperpage = $_GET['length']; // Rows display per page
//$columnIndex = $_GET['order'][0]['column']; // Column index
$columnName = "id"; // Column name
//$columnSortOrder = $_GET['order'][0]['dir']; // asc or desc
$searchValue = $_GET['search']['value']; // Search value
### Search 
$searchQuery = " ";  
if($searchValue != ''){
	$searchQuery = " AND (
   		X.bkb					LIKE'%".$searchValue."%'
		OR X.tipe_scrap			LIKE'%".$searchValue."%'
		OR X.stock				LIKE'%".$searchValue."%'
		OR X.unit		LIKE'%".$searchValue."%'
	)";
}

## Total number of records without filtering
$sel = mysqli_query($conn_li,"select count(*) allcount from $table");
$records = mysqli_fetch_assoc($sel); 

$totalRecords = $records['allcount'];

## Total number of record with filtering  
$sel = mysqli_query($conn_li,"select count(*) allcount from $table WHERE 1 ".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records


$empQuery = "select $colomn from $table WHERE 1 ".$searchQuery;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
/*      echo $empQuery;
 die();  */  
$no = 0;
while ($row = mysqli_fetch_assoc($empRecords)) {
	if($id_url =="XX"){
		$qty = $row['qty_bkb'];
		$price = $row['price_bkb'];
		$curr = "";
		$discount = $row['discount'];
		
	}else{
		$qty = $row['qty_exist'];
		$price = $row['price_exist'];	
		$discount = $row['discount'];
		$curr = $row['curr'];	
	}
	$input = '';
	$input .="<input type='text' id='qty_".$no."' class='form-control qty' value='{$qty}' onkeyup='handleKeyUp(this)'>";

	$input2 = '';
	$input2 .="<input type='text' id='price_".$no."' class='form-control price' value='{$price}' onkeyup='handleKeyUp(this)'>";
	
	$input3 = '';
	$input3 .="<SELECT id='curr_".$no."' class='form-control curr' value='{$curr}' onchange='handleKeyUp(this)'>
					<option  >--Pilih Curr--</option>
					<option ".($curr == 'IDR' ? 'selected="selected"':'' )." value='IDR'>IDR</option>
					<option ".($curr == 'USD' ? 'selected="selected"':'' )."value='USD'>USD</option>
			</SELECT>
	";
	$input4 = "";
	$input4 .="<input type='text' id='discount_".$no."' class='form-control price' value='{$discount}' onkeyup='handleKeyUp(this)'>";
	$no++;
	$data[] = array(
		"id"=>htmlspecialchars($row['id']),
		"id_inv_sc"=>htmlspecialchars($row['id_inv_sc']), //,X.id_item
		"id_bkb"=>htmlspecialchars($row['id_bkb']),
		"bkb"=>htmlspecialchars($row['bkb']),
		"id_item"=>htmlspecialchars($row['id_item']),
		"id_jo"=>htmlspecialchars($row['id_jo']),
		"stock"=>htmlspecialchars($row['stock']),
		"qty_bkb"=>htmlspecialchars(($row['qty_bkb'])),
		"qty"=>htmlspecialchars(($row['qty_bkb'])),
		"discount"=>htmlspecialchars(($row['discount'])),
		"price"=>htmlspecialchars(($row['price_bkb'])),
		"price_bkb"=>htmlspecialchars($row['price_bkb']),	
		"curr"=>htmlspecialchars($row['curr']),
		"tipe_scrap"=>htmlspecialchars($row['tipe_scrap']),	
		"unit"=>htmlspecialchars($row['unit']),	
		"button"=>rawurlencode($input),
		"button2"=>rawurlencode($input2),
		"button3"=>rawurlencode($input3),
		"button4"=>rawurlencode($input4),
		
		
		
		
		// "qty_header"=>htmlspecialchars($row['qty_header'])
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