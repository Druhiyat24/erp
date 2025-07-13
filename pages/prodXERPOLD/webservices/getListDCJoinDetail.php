<?php 
include __DIR__ .'/../../../include/conn.php';
include 'production_interface.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];

$id_ws = $_GET['id_ws'];
$id_url = $_GET['id_url'];
// print_r($id_ws);die();

$sql_balance = sql_balance_cutting($id_ws);

if($id_url == '-1' OR $id_url == 'undefined'){
	$table = "(

		SELECT 
			join_new.*,
			join_old.tot_qty_dc_join AS cumulative,
			join_old.balance_qty_dc_join AS balance
		FROM (

			SELECT 
				'' AS id_dc_join_detail,
				'' AS id_dc_join,
				cqd.id_cut_qc_detail,
				cqd.id_cut_qc,
				cqd.id_cut_number_detail,
				cqd.id_cut_out_detail,
				cqd.id_cost,
				cqd.id_so_det,
				cqd.id_item,
				cqd.color,
				cod.fabric_desc,
				cod.size,
				cod.id_grouping,
				cod.name_grouping,
				cod.id_panel,
				cod.nama_panel,
				cod.lot,
				sd.qty_so,
				GROUP_CONCAT(cnd.number_cutting) AS number_cutting,
				GROUP_CONCAT(cnd.number_bundle) AS number_bundle,
				GROUP_CONCAT(cnd.number_sack) AS number_sack,
				'' AS qty_input_dc_join,
				'' AS qty_reject_dc_join,
				'' AS qty_dc_join
			FROM prod_cut_qc_detail AS cqd
			INNER JOIN (
				SELECT 
					cnd.id_cut_number_detail,
					cnd.number_cutting,
					cnd.number_bundle,
					cnd.number_sack
				FROM prod_cut_number_detail AS cnd
			) AS cnd ON cnd.id_cut_number_detail = cqd.id_cut_number_detail
			INNER JOIN (
				SELECT 
					cod.id_cut_out_detail,
					cod.fabric_desc,
					cod.size,
					cod.id_grouping,
					mg.name_grouping,
					cod.id_panel,
					mp.nama_panel,
					cod.lot
				FROM prod_cut_out_detail AS cod
				INNER JOIN mastergrouping AS mg ON mg.id_grouping = cod.id_grouping
				INNER JOIN masterpanel AS mp ON mp.id = cod.id_panel
			) AS cod ON cod.id_cut_out_detail = cqd.id_cut_out_detail
			INNER JOIN (
				SELECT 
					sd.id,
					sd.qty AS qty_so
				FROM so_det AS sd
			) AS sd ON sd.id = cqd.id_so_det
			WHERE cqd.id_cost = '{$id_ws}'
			GROUP BY cod.id_grouping,cod.id_panel,cqd.id_so_det
		
		) AS join_new
		
		LEFT JOIN (
			{$sql_balance}
		) AS join_old ON join_old.id_so_det = join_new.id_so_det AND join_old.id_panel = join_new.id_panel AND join_old.id_item = join_new.id_item

	) AS X";
}
else{
	$table = "(
		
		SELECT 
			join_new.*,
			join_old.tot_qty_dc_join AS cumulative,
			join_old.balance_qty_dc_join AS balance
		FROM (
		
			SELECT 
				djd.id_dc_join_detail,
				djd.id_dc_join,
				djd.id_cut_out_detail,
				djd.id_cut_number_detail,
				djd.id_cut_qc_detail,
				dj.id_cost,
				djd.id_so_det,
				djd.id_item,
				cod.fabric_desc,
				cod.color,
				cod.size,
				cod.id_grouping,
				cod.name_grouping,
				cod.id_panel,
				cod.nama_panel,
				cod.lot,
				sd.qty AS qty_so,
				GROUP_CONCAT(cnd.number_cutting) AS number_cutting,
				GROUP_CONCAT(cnd.number_bundle) AS number_bundle,
				GROUP_CONCAT(cnd.number_sack) AS number_sack,
				djd.qty_input_dc_join,
				djd.qty_reject_dc_join,
				djd.qty_dc_join
			FROM prod_dc_join_detail AS djd
			INNER JOIN prod_dc_join AS dj ON dj.id_dc_join = djd.id_dc_join
			INNER JOIN so_det AS sd ON sd.id = djd.id_so_det
			INNER JOIN (
				SELECT 
					cod.id_cut_out_detail,
					cod.fabric_desc,
					cod.color,
					cod.size,
					cod.id_grouping,
					mg.name_grouping,
					cod.id_panel,
					mp.nama_panel,
					cod.lot
				FROM prod_cut_out_detail AS cod
				INNER JOIN mastergrouping AS mg ON mg.id_grouping = cod.id_grouping
				INNER JOIN masterpanel AS mp ON mp.id = cod.id_panel
			) AS cod ON cod.id_cut_out_detail = djd.id_cut_out_detail	
			INNER JOIN (
				SELECT 
					cnd.id_cut_number_detail,
					cnd.id_cut_out_detail,
					cnd.number_cutting,
					cnd.number_bundle,
					cnd.number_sack
				FROM prod_cut_number_detail AS cnd
			) AS cnd ON cnd.id_cut_out_detail = cod.id_cut_out_detail
			WHERE djd.id_dc_join = '{$id_url}'
			GROUP BY cod.id_grouping,cod.id_panel,djd.id_so_det
			ORDER BY djd.id_dc_join_detail ASC
			
		) AS join_new

		LEFT JOIN (
			{$sql_balance}
		) AS join_old ON join_old.id_so_det = join_new.id_so_det AND join_old.id_panel = join_new.id_panel AND join_old.id_item = join_new.id_item
	
	) AS X";
}
		
$colomn = " 
	X.id_dc_join_detail,
	X.id_dc_join,
	X.id_cut_qc_detail,
	X.id_cut_number_detail,
	X.id_cut_out_detail,
	X.id_cost,
	X.id_so_det,
	X.id_item,
	X.fabric_desc,
	X.color,
	X.size,
	X.id_grouping,
	X.name_grouping,
	X.id_panel,
	X.nama_panel,
	X.lot,
	X.qty_so,
	X.cumulative,
	X.balance,
	X.number_cutting,
	X.number_bundle,
	X.number_sack,
	X.qty_input_dc_join,
	X.qty_reject_dc_join,
	X.qty_dc_join
";

 //print_r($id_panel);die();

$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "X.color ASC,X.id_grouping ASC,X.id_panel ASC,X.id_so_det ASC"; // Column name 
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value 

### Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (
   		X.fabric_desc		LIKE'%".$searchValue."%'
		OR X.color			LIKE'%".$searchValue."%'
		OR X.size			LIKE'%".$searchValue."%'
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


$empQuery = "SELECT $colomn FROM $table WHERE 1 ORDER BY $columnName ".$searchQuery;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
// echo $empQuery;die();

$no = 1;
$num = 0;
while ($row = mysqli_fetch_assoc($empRecords)) {

	$inputQtySO = '';
	$inputQtySO .="<input type='text' id='qtySO_".$num."' class='form-control forms' value='$row[qty_so]' readonly style='min-width: 80px'>";

	$inputCumulative = '';
	$inputCumulative .="<input type='text' id='cml_".$num."' class='form-control cutt forms' value='$row[cumulative]' onchange='handleKeyUp(this)' readonly>";

	$inputBalance = '';
	$inputBalance .="<input type='text' id='bal_".$num."' class='form-control cutt forms' value='$row[balance]' onchange='handleKeyUp(this)' readonly>";

	// $inputCutting = '';
	// $inputCutting .="<input type='text' id='cut_".$num."' class='form-control' value='$row[number_cutting]' readonly>";

	// $inputBundle = '';
	// $inputBundle .="<input type='text' id='bundle_".$num."' class='form-control forms' value='$row[number_bundle]' readonly>";

	// $inputSack = '';
	// $inputSack .="<input type='text' id='sack_".$num."' class='form-control forms' value='$row[number_sack]' readonly>";

	if($row['qty_input_dc_join'] == ''){
		$qtyInJoin = "";
	}
	else{
		$qtyInJoin = $row['qty_input_dc_join'];
	}
	$inputQtyInJoin = '';
	$inputQtyInJoin .="<input type='text' id='qtyInJoin_".$num."' class='form-control forms' value='$qtyInJoin' onchange='handleKeyUp(this)' style='min-width: 80px'>";

	if($row['qty_reject_dc_join'] == ""){
		$reject = "";
	}
	else{
		$reject = $row['qty_reject_dc_join'];
	}
	$inputReject = '';
	$inputReject .="<input type='text' id='reject_".$num."' class='form-control forms' value='$reject' onchange='handleKeyUp(this)' style='min-width: 80px'>";

	if($row['qty_dc_join'] == ""){
		$qtyJoin = "";
	}
	else{
		$qtyJoin = $row['qty_dc_join'];
	}
	$inputQtyJoin = '';
	$inputQtyJoin .="<input type='text' id='qtyJoin_".$num."' class='form-control forms' value='$qtyJoin' readonly style='min-width: 80px'>";
	
	$num++;
	

	$data[] = array(
		"no"=>$no,
		"id_dc_join_detail"=>htmlspecialchars($row['id_dc_join_detail']),
		"id_dc_join"=>htmlspecialchars($row['id_dc_join']),
		"id_cut_qc_detail"=>htmlspecialchars($row['id_cut_qc_detail']),
		"id_cut_number_detail"=>htmlspecialchars($row['id_cut_number_detail']),
		"id_cut_out_detail"=>htmlspecialchars($row['id_cut_out_detail']),
		"id_cost"=>htmlspecialchars($row['id_cost']),
		"id_so_det"=>htmlspecialchars($row['id_so_det']),
		"id_item"=>htmlspecialchars($row['id_item']),
		"fabric_desc"=>htmlspecialchars($row['fabric_desc']),
		"color"=>htmlspecialchars($row['color']),
		"size"=>htmlspecialchars($row['size']),
		"name_grouping"=>htmlspecialchars($row['name_grouping']),
		"nama_panel"=>htmlspecialchars($row['nama_panel']),
		"lot"=>htmlspecialchars($row['lot']),
		"qty_so"=>rawurlencode($inputQtySO),
		"qty_so_val"=>htmlspecialchars($row['qty_so']),
		"cumulative"=>rawurlencode($inputCumulative),
		"cumulative_val"=>htmlspecialchars($row['cumulative']),
		"balance"=>rawurlencode($inputBalance),
		"balance_val"=>htmlspecialchars($row['balance']),
		"number_cutting"=>htmlspecialchars($row['number_cutting']),
		// "number_cutting_val"=>"",
		"number_bundle"=>htmlspecialchars($row['number_bundle']),
		// "number_bundle_val"=>"",
		"number_sack"=>htmlspecialchars($row['number_sack']),
		// "number_sack_val"=>"",
		"qty_input_join"=>rawurlencode($inputQtyInJoin),
		"qty_input_join_val"=>(ISSET($row['qty_input_dc_join']) ? $row['qty_input_dc_join'] : 0 ),
		"reject_dc_join"=>rawurlencode($inputReject),
		"reject_dc_join_val"=>(ISSET($row['qty_reject_dc_join']) ? $row['qty_reject_dc_join'] : 0 ),
		"qty_dc_join"=>rawurlencode($inputQtyJoin),
		"qty_dc_join_val"=>(ISSET($row['qty_dc_join']) ? $row['qty_dc_join'] : 0 )
		// "status"=>htmlspecialchars($row['status_approve'])
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