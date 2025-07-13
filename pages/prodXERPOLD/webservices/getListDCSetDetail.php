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
			set_new.*,
			MIN(set_old.tot_qty_dc_set) AS cumulative,
			MIN(set_old.balance_qty_dc_set) AS balance
		FROM ( 

			SELECT 
				'' AS id_dc_set_detail,
				'' AS id_dc_set,
				djd.id_dc_join_detail,
				djd.id_dc_join,
				dj.id_cost,
				djd.id_cut_out_detail,
				djd.id_so_det,
				cod.color,
				cod.size,
				sd.qty AS qty_so,
				GROUP_CONCAT(DISTINCT cod.number_cutting) AS number_cutting,
				GROUP_CONCAT(DISTINCT cod.number_bundle) AS number_bundle,
				GROUP_CONCAT(DISTINCT cod.number_sack) AS number_sack,
				'' AS qty_input_dc_set,
				'' AS qty_reject_dc_set,
				'' AS qty_dc_set,
				'' AS location,
				'' AS remarks
			FROM prod_dc_join_detail AS djd
			INNER JOIN prod_dc_join AS dj ON dj.id_dc_join = djd.id_dc_join
			INNER JOIN (
				SELECT 
					cod.id_cut_out_detail,
					cod.color,
					cod.size,
					cnd.number_cutting,
					cnd.number_bundle,
					cnd.number_sack
				FROM prod_cut_out_detail AS cod
				INNER JOIN (
					SELECT 
						cnd.id_cut_number_detail,
						cnd.id_cut_out_detail,
						cnd.number_cutting,
						cnd.number_bundle,
						cnd.number_sack
					FROM prod_cut_number_detail AS cnd
				) AS cnd ON cnd.id_cut_out_detail = cod.id_cut_out_detail
			) AS cod ON cod.id_cut_out_detail = djd.id_cut_out_detail
			INNER JOIN so_det AS sd ON sd.id = djd.id_so_det

			WHERE dj.id_cost = '{$id_ws}'
			GROUP BY cod.color,cod.size
			ORDER BY djd.id_dc_join_detail ASC

		) AS set_new
		
		LEFT JOIN (
			{$sql_balance}
		) AS set_old ON set_old.id_so_det = set_new.id_so_det AND set_old.color = set_new.color
		GROUP BY set_new.color,set_new.id_so_det

	) AS X";

}
else{
	$table = "(
		
		SELECT 
			set_new.*,
			MIN(set_old.tot_qty_dc_set) AS cumulative,
			MIN(set_old.balance_qty_dc_set) AS balance
		FROM (
		
			SELECT 
				dsd.id_dc_set_detail,
				dsd.id_dc_set,
				ds.id_cost,
				dsd.id_dc_join_detail,
				dsd.id_cut_out_detail,
				dsd.id_so_det,
				dsd.color,
				dsd.size,
				sd.qty AS qty_so,
				cnd.number_cutting,
				cnd.number_bundle,
				cnd.number_sack,
				dsd.qty_input_dc_set,
				dsd.qty_reject_dc_set,
				dsd.qty_dc_set,
				dsd.location,
				dsd.remarks
			FROM prod_dc_set_detail AS dsd
			INNER JOIN prod_dc_set AS ds ON ds.id_dc_set = dsd.id_dc_set
			INNER JOIN so_det AS sd ON sd.id = dsd.id_so_det
			INNER JOIN (
				SELECT 
					cnd.id_cut_number_detail,
					cnd.id_cut_out_detail,
					cnd.number_cutting,
					cnd.number_bundle,
					cnd.number_sack
				FROM prod_cut_number_detail AS cnd
			) AS cnd ON cnd.id_cut_out_detail = dsd.id_cut_out_detail
			WHERE dsd.id_dc_set = '{$id_url}'
		
		) AS set_new
		
		LEFT JOIN (
			{$sql_balance}
		) AS set_old ON set_old.id_so_det = set_new.id_so_det AND set_old.color = set_new.color
		GROUP BY set_new.color,set_new.id_so_det
	
	) AS X";
}
		
$colomn = "
	X.id_dc_set_detail,
	X.id_dc_set,
	X.id_cost,
	X.id_dc_join_detail,
	X.id_cut_out_detail,
	X.id_so_det,
	X.color,
	X.size,
	X.qty_so,
	X.cumulative,
	X.balance,
	X.number_cutting,
	X.number_bundle,
	X.number_sack,
	X.qty_input_dc_set,
	X.qty_reject_dc_set,
	X.qty_dc_set,
	X.location,
	X.remarks
";

$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "X.id_dc_set_detail ASC"; // Column name 
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value 

### Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (
   		X.buyer				LIKE'%".$searchValue."%'
		OR X.styleno		LIKE'%".$searchValue."%'
		OR X.ws				LIKE'%".$searchValue."%'
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

	if($row['qty_input_dc_set'] == ''){
		$qtyInSet = "";
	}
	else{
		$qtyInSet = $row['qty_input_dc_set'];
	}
	$inputQtyInSet = '';
	$inputQtyInSet .="<input type='text' id='qtyInSet_".$num."' class='form-control forms' value='$qtyInSet' onchange='handleKeyUp(this)' style='min-width: 80px'>";

	if($row['qty_reject_dc_set'] == ''){
		$reject = "";
	}
	else{
		$reject = $row['qty_reject_dc_set'];
	}
	$inputReject = '';
	$inputReject .="<input type='text' id='reject_".$num."' class='form-control forms' value='$reject' onchange='handleKeyUp(this)' style='min-width: 80px'>";

	if($row['qty_dc_set'] == ''){
		$qtyJoin = "";
	}
	else{
		$qtyJoin = $row['qty_dc_set'];
	}
	$inputQtySet = '';
	$inputQtySet .="<input type='text' id='qtySet_".$num."' class='form-control forms' value='$qtyJoin' readonly style='min-width: 80px'>";

	if($row['location'] == ''){
		$location = "";
	}
	else{
		$location = $row['location'];
	}
	$inputLocation = '';
	$inputLocation .="<input type='text' id='location_".$num."' class='form-control' value='$location' onchange='handleKeyUp(this)' style='min-width: 80px'>";

	if($row['remarks'] == ''){
		$remarks = "";
	}
	else{
		$remarks = $row['remarks'];
	}
	$inputRemarks = '';
	$inputRemarks .="<input type='text' id='remarks_".$num."' class='form-control' value='$remarks' onchange='handleKeyUp(this)' style='min-width: 80px'>";
	
	$num++;
	

	$data[] = array(
		"no"=>$no,
		"id_dc_set_detail"=>htmlspecialchars($row['id_dc_set_detail']),
		"id_dc_set"=>htmlspecialchars($row['id_dc_set']),
		"id_cost"=>htmlspecialchars($row['id_cost']),
		"id_dc_join_detail"=>htmlspecialchars($row['id_dc_join_detail']),
		"id_cut_out_detail"=>htmlspecialchars($row['id_cut_out_detail']),
		"id_so_det"=>htmlspecialchars($row['id_so_det']),
		"color"=>htmlspecialchars($row['color']),
		"size"=>htmlspecialchars($row['size']),
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
		"qty_input_set"=>rawurlencode($inputQtyInSet),
		"qty_input_set_val"=>(ISSET($row['qty_input_dc_set']) ? $row['qty_input_dc_set'] : 0 ),
		"reject_dc_set"=>rawurlencode($inputReject),
		"reject_dc_set_val"=>(ISSET($row['qty_reject_dc_set']) ? $row['qty_reject_dc_set']  : 0 ),
		"qty_dc_set"=>rawurlencode($inputQtySet),
		"qty_dc_set_val"=>(ISSET($row['qty_dc_set']) ? $row['qty_dc_set']  : $row['qty_dc_join']  ),
		"location"=>rawurlencode($inputLocation),
		"location_val"=>(ISSET($row['location']) ? $row['location']  : "" ),
		"remarks"=>rawurlencode($inputRemarks),
		"remarks_val"=>(ISSET($row['remarks']) ? $row['remarks']  : "" )
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