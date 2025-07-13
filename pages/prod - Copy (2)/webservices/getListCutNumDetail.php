<?php 
include __DIR__ .'/../../../include/conn.php';
include 'production_interface.php';
## Read value
$draw = $_POST['draw']; 
$row = $_POST['start'];

$cut_out = $_GET['url'];
$cost = $_GET['cost'];
$color = $_GET['color'];
// print_r($cut_out);die();

// $sql = "SELECT 
// 	cn.id_cut_out,
// 	cn.id_cost,
// 	cn.color
// 	FROM prod_cut_number AS cn
// 	WHERE cn.id_cut_out = '{$cut_out}'
// 	AND cn.id_cost = '{$cost}'
// 	AND cn.color = '{$color}'
// ";
// // echo $sql;die();
// $stmt = mysql_query($sql);
// $row = mysql_fetch_array($stmt);

$sql_balance = sql_balance_cutting($cost);
// print_r($sql_balance);die();

if($cut_out == "-1"){

	$table = "(
		
		SELECT 
			num_new.*,
			num_old.tot_qty_cut_num AS cumulative,
			num_old.balance_qty_cut_num AS balance
		FROM (

			SELECT 
				'' AS id_cut_number_detail,
				'' AS id_cut_number,
				cod.id_cut_out_detail,
				cod.id_cut_out,
				cod.id_cost,
				cod.id_so_det,
				cod.id_item,
				cod.fabric_desc,
				cod.color,
				cod.size,
				cod.id_grouping,
				mg.name_grouping,
				cod.id_panel,
				mp.nama_panel,
				cod.lot,
				#SUM(cod.qty_cut_out) AS qty_cut_out,
				sd.qty AS qty_so,
				#'' AS cumulative,
				#'' AS balance,
				'' AS number_cutting,
				'' AS number_bundle,
				'' AS number_sack,
				'' AS qty_input_number,
				'' AS qty_reject,
				'' AS qty_cut_number,
				'' AS remarks
			FROM prod_cut_out_detail AS cod
			INNER JOIN mastergrouping AS mg ON mg.id_grouping = cod.id_grouping
			INNER JOIN masterpanel AS mp ON mp.id = cod.id_panel
			INNER JOIN so_det AS sd ON sd.id = cod.id_so_det
			WHERE cod.id_cost = '{$cost}' AND cod.color = '{$color}'
			GROUP BY cod.id_grouping,cod.id_panel,cod.id_so_det

		) AS num_new

		LEFT JOIN (
			{$sql_balance}
		) AS num_old ON num_old.id_so_det = num_new.id_so_det AND num_old.id_panel = num_new.id_panel AND num_old.id_item = num_new.id_item
	
	) AS X";

}
else{

	$table = "(
		
		SELECT 
			num_new.*,
			num_old.tot_qty_cut_num AS cumulative,
			num_old.balance_qty_cut_num AS balance
		FROM (
			SELECT
				cn.id_cut_number_detail,
				cn.id_cut_number,
				cn.id_cut_out_detail,
				cn.id_cost,
				cn.id_so_det,
				cn.id_item,
				cod.fabric_desc,
				cod.color,
				cod.size,
				cod.lot,
				sd.qty AS qty_so,
				cod.id_grouping,
				mg.name_grouping,
				cod.id_panel,
				mp.nama_panel,
				cn.number_cutting,
				cn.number_bundle,
				cn.number_sack,
				cn.qty_input_number,
				cn.qty_reject,
				cn.qty_cut_number,
				cn.remarks
			FROM prod_cut_number_detail AS cn 
			INNER JOIN (
				SELECT 
					cod.id_cut_out_detail,
					cod.fabric_desc,
					cod.color,
					cod.size,
					cod.lot,
					cod.id_grouping,
					cod.id_panel
				FROM prod_cut_out_detail AS cod
			) AS cod ON cod.id_cut_out_detail = cn.id_cut_out_detail
			INNER JOIN mastergrouping AS mg ON mg.id_grouping = cod.id_grouping
			INNER JOIN masterpanel AS mp ON mp.id = cod.id_panel
			INNER JOIN so_det AS sd ON sd.id = cn.id_so_det
			WHERE cn.id_cut_number = '{$cut_out}' AND cn.id_cost = '{$cost}' AND cn.color = '{$color}'
		) AS num_new

		LEFT JOIN (
			{$sql_balance}
		) AS num_old ON num_old.id_so_det = num_new.id_so_det AND num_old.id_panel = num_new.id_panel AND num_old.id_item = num_new.id_item
	
	) AS X";

}

$colomn = "
	X.id_cut_number_detail,
	X.id_cut_number,
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
	#X.qty_cut_out,
	X.qty_so,
	X.cumulative,
	X.balance,
	X.number_cutting,
	X.number_bundle,
	X.number_sack,
	X.qty_input_number,
	X.qty_reject,
	X.qty_cut_number,
	X.remarks
";

$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "X.id_grouping ASC,X.id_panel ASC,X.id_so_det ASC"; // Column name 
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value 
 
### Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (
		X.fabric_desc			LIKE'%".$searchValue."%'
		OR X.size				LIKE'%".$searchValue."%'
		OR X.name_grouping		LIKE'%".$searchValue."%'
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
	$inputQtySO .="<input type='text' id='qtySO_".$no."' class='form-control forms' value='$row[qty_so]' disabled style='min-width: 80px'>";

	$inputCumulative = '';
	$inputCumulative .="<input type='text' id='cml_".$no."' class='form-control cutt forms' value='$row[cumulative]' onchange='handleKeyUp(this)' readonly>";

	$inputBalance = '';
	$inputBalance .="<input type='text' id='bal_".$no."' class='form-control cutt forms' value='$row[balance]' onchange='handleKeyUp(this)' readonly>";

	if($row['number_cutting'] == ''){
		$n_cutt = "";
	}
	else{
		$n_cutt = $row['number_cutting'];
	}
	$inputCutting = '';
	$inputCutting .="<input type='text' id='cut_".$no."' class='form-control' value='$n_cutt' onchange='handleKeyUp(this)' style='min-width: 80px'>";

	if($row['number_bundle'] == ''){
		$n_bund = "";
	}
	else{
		$n_bund = $row['number_bundle'];
	}
	$inputBundle = '';
	$inputBundle .="<input type='text' id='bundle_".$no."' class='form-control forms' value='$n_bund' onchange='handleKeyUp(this)' style='min-width: 80px'>";

	if($row['number_sack'] == ''){
		$n_sack = "";
	}
	else{
		$n_sack = $row['number_sack'];
	}
	$inputSack = '';
	$inputSack .="<input type='text' id='sack_".$no."' class='form-control forms' value='$n_sack' onchange='handleKeyUp(this)' style='min-width: 80px'>";
	
	if($row['remarks'] == ''){
		$remark = "";
	}
	else{
		$remark = $row['remarks'];
	}
	$inputRemarks = '';
	$inputRemarks .="<input type='text' id='remark_".$no."' class='form-control' value='$remark' onchange='handleKeyUp(this)' style='min-width: 80px'>";

	if($row['qty_input_number'] == ''){
		$qtyInNum = "";
	}
	else{
		$qtyInNum = $row['qty_input_number'];
	}
	$inputQtyInNum = '';
	$inputQtyInNum .="<input type='text' id='qtyInNum_".$no."' class='form-control forms' value='$qtyInNum' onchange='handleKeyUp(this)' style='min-width: 80px'>";

	if($row['qty_reject'] == ''){
		$reject = "";
	}
	else{
		$reject = $row['qty_reject'];
	}
	$inputReject = '';
	$inputReject .="<input type='text' id='reject_".$no."' class='form-control forms' value='$reject' onchange='handleKeyUp(this)' style='min-width: 80px'>";

	if($row['qty_cut_number'] == ''){
		$qtyNumber = "";
	}
	else{
		$qtyNumber = $row['qty_cut_number'];
	}
	$inputQtyNumber = '';
	$inputQtyNumber .="<input type='text' id='qtyNum_".$no."' class='form-control forms' value='$qtyNumber' disabled style='min-width: 80px'>";

	$no++;
	

	$data[] = array(
		"num"=>$num,
		"id_cut_number_detail"=>htmlspecialchars($row['id_cut_number_detail']),
		"id_cut_number"=>htmlspecialchars($row['id_cut_number']),
		"id_cut_out_detail"=>htmlspecialchars($row['id_cut_out_detail']),
		"id_cost"=>htmlspecialchars($row['id_cost']),
		"id_so_det"=>htmlspecialchars($row['id_so_det']),
		"id_item"=>htmlspecialchars($row['id_item']),
		"description"=>rawurldecode($row['fabric_desc']),
		"color"=>rawurldecode($row['color']),
		"size"=>rawurldecode($row['size']),
		"grouping"=>htmlspecialchars($row['name_grouping']),
		"panel"=>htmlspecialchars($row['nama_panel']),
		"lot"=>htmlspecialchars($row['lot']),
		"qty_so"=>rawurlencode($inputQtySO),
		"qty_so_val"=>htmlspecialchars($row['qty_so']),
		"cumulative"=>rawurlencode($inputCumulative),
		"cumulative_val"=>htmlspecialchars($row['cumulative']),
		"balance"=>rawurlencode($inputBalance),
		"balance_val"=>htmlspecialchars($row['balance']),
		"number_cutting"=>rawurlencode($inputCutting),
		"number_cutting_val"=>(ISSET($row['number_cutting']) ? $row['number_cutting'] : "" ),
		"number_bundle"=>rawurlencode($inputBundle),
		"number_bundle_val"=>(ISSET($row['number_bundle']) ? $row['number_bundle'] : "" ),
		"number_sack"=>rawurlencode($inputSack),
		"number_sack_val"=>(ISSET($row['number_sack']) ? $row['number_sack'] : "" ),
		"qty_input_number"=>rawurlencode($inputQtyInNum),
		"qty_input_number_val"=>(ISSET($row['qty_input_number']) ? $row['qty_input_number'] : 0 ),
		"reject_qty"=>rawurlencode($inputReject),
		"reject_qty_val"=>(ISSET($row['qty_reject']) ? $row['qty_reject'] : 0 ),
		"number_qty"=>rawurlencode($inputQtyNumber),
		"number_qty_val"=>(ISSET($row['qty_cut_number']) ? $row['qty_cut_number'] : 0 ),
		"remarks"=>rawurlencode($inputRemarks),
		"remarks_val"=>(ISSET($row['remarks']) ? $row['remarks']  : ""  )
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