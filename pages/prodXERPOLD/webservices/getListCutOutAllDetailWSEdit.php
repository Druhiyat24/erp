<?php 
include __DIR__ .'/../../../include/conn.php';
include 'production_interface.php';
## Read value
$draw = $_POST['draw']; 
$row = $_POST['start'];

$cut_out = $_GET['id_cut_out'];
// print_r($id_cost);die();

$queryCost = mysqli_query($conn_li,"SELECT co.id_cost FROM prod_cut_out AS co WHERE co.id_cut_out = '{$cut_out}'");
$rowCost = mysqli_fetch_assoc($queryCost);
$id_cost = $rowCost['id_cost'];
// print_r($rowCost['id_cost']);die();

$sql_balance = sql_balance_cutting($id_cost);
$table = "(
	
	SELECT 
		cut_new.*,
		cut_old.tot_qty_cut_out AS cumulative,
		cut_old.balance_qty_cut_out AS balance
	FROM (

		SELECT     
			cod.id_cut_out_detail as id_cod,
			cod.id_cut_out,
			cod.id_cat,
			cod.id_so_det,
			cod.id_item,
			cod.id_grouping,
			cod.id_panel,
			mg.name_grouping,
			mp.nama_panel,
			cod.fabric_desc,
			cod.color,
			cod.lot,
			cod.size,
			sd.qty_so,
			cod.cutting_output,
			cod.reject,
			cod.qty_cut_out
		FROM prod_cut_out_detail AS cod 
		INNER JOIN mastergrouping AS mg ON mg.id_grouping = cod.id_grouping
		INNER JOIN masterpanel AS mp ON mp.id = cod.id_panel
		INNER JOIN (
			SELECT 
				sd.id,
				sd.size,
				sd.qty AS qty_so
			FROM so_det AS sd
		) AS sd ON sd.id = cod.id_so_det
		WHERE cod.id_cut_out = '{$cut_out}'

	) AS cut_new


	LEFT JOIN (
		{$sql_balance}
	) AS cut_old ON cut_old.id_so_det = cut_new.id_so_det AND cut_old.id_panel = cut_new.id_panel AND cut_old.id_item = cut_new.id_item

) AS X";

$colomn = "
	X.id_cod,
	X.id_cut_out,
	X.id_cat,
	X.id_so_det,
	X.id_grouping,
	X.id_panel,
	X.lot,
	X.color,
	X.fabric_desc,
	X.name_grouping,
	X.nama_panel,
	X.size,
	X.qty_so,
	X.cumulative,
	X.balance,
	X.cutting_output,
	X.reject,
	X.qty_cut_out
";

$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "X.id_grouping ASC, X.id_panel ASC, X.id_so_det ASC"; // Column name 
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value 

### Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (
   		X.fabric_desc		LIKE'%".$searchValue."%'
		OR X.size			LIKE'%".$searchValue."%'
	)";
}

## Total number of records without filtering
$sel = mysqli_query($conn_li,"select count(*) allcount from $table");
$records = mysqli_fetch_assoc($sel); 

$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($conn_li,"select count(*) allcount from $table WHERE 1 ".$searchQuery );
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records


$empQuery = "SELECT $colomn FROM $table WHERE 1 ".$searchQuery." ORDER BY $columnName";
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
// echo $empQuery;die();

$num = 1;
$no = 0;
while ($row = mysqli_fetch_assoc($empRecords)) {

	$inputQtySO = '';
	$inputQtySO .="<input type='text' id='qtySO_".$no."' class='form-control cutt forms' value='$row[qty_so]' onchange='handleKeyUp(this)' disabled>";

	$inputCumulative = '';
	$inputCumulative .="<input type='text' id='cml_".$no."' class='form-control cutt forms' value='$row[cumulative]' onchange='handleKeyUp(this)' readonly>";

	$inputBalance = '';
	$inputBalance .="<input type='text' id='bal_".$no."' class='form-control cutt forms' value='$row[balance]' onchange='handleKeyUp(this)' readonly>";

	$inputCutt = '';
	$inputCutt .="<input type='text' id='cutt_".$no."' class='form-control forms' value='$row[cutting_output]' onchange='handleKeyUp(this);cuttNumber(this)'>";

	$inputReject = '';
	$inputReject .="<input type='text' id='reject_".$no."' class='form-control forms' value='$row[reject]' onchange='handleKeyUp(this);rejectNumber(this)'>";

	$fieldOkeCutt = '';
	$fieldOkeCutt .="<input type='text' id='oke_".$no."' class='form-control forms' value='$row[qty_cut_out]' onmouseover='mouseOver(this)' readonly>";
	$no++;
	

	$data[] = array(
		"num"=>$num,
		"id"=>htmlspecialchars($row['id_cat']),
		"id_cat"=>htmlspecialchars($row['id_cat']),
		"id_det"=>htmlspecialchars($row['id_cod']),
		"goods_code"=>rawurldecode($row['fabric_code']),
		"color"=>rawurldecode($row['color']),
		"itemdesc"=>rawurldecode($row['fabric_desc']),
		"grouping"=>htmlspecialchars($row['name_grouping']),
		"idg"=>htmlspecialchars($row['id_grouping']),
		"panel"=>htmlspecialchars($row['nama_panel']),
		"idp"=>htmlspecialchars($row['id_panel']),
		"lot"=>htmlspecialchars($row['lot']),
		"id_so_det"=>htmlspecialchars($row['id_so_det']),
		"size"=>rawurldecode($row['size']),
		"qtySO"=>rawurlencode($inputQtySO),
		"qtySO_val"=>htmlspecialchars($row['qty_so']),
		"cumulative"=>rawurlencode($inputCumulative),
		"cumulative_val"=>htmlspecialchars($row['cumulative']),
		"balance"=>rawurlencode($inputBalance),
		"balance_val"=>htmlspecialchars($row['balance']),
		"inputCutt"=>rawurlencode($inputCutt),
		"cutt"=>htmlspecialchars($row['cutting_output']),
		"inputReject"=>rawurlencode($inputReject),
		"reject"=>htmlspecialchars($row['reject']),
		"fieldOkeCutt"=>rawurlencode($fieldOkeCutt),
		"okeCutt"=>htmlspecialchars($row['qty_cut_out'])
	);

	$num++;
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