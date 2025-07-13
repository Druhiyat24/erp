<?php 
include __DIR__ .'/../../../include/conn.php';
include 'production_interface.php';
## Read value
$draw = $_POST['draw']; 
$row = $_POST['start'];

$cut_qc = $_GET['url'];
$cost = $_GET['cost'];
// $color = $_GET['color'];
// print_r($color);die();

$sql_balance = sql_balance_cutting($cost);

if($cut_qc == "-1"){

	$table = "(
		
		SELECT 
			qc_new.*,
			qc_old.tot_qty_cut_qc AS cumulative,
			qc_old.balance_qty_cut_qc AS balance,
			qc_old.tot_qty_cut_qc AS before_cumulative,
			(qc_old.tot_qty_cut_qc + qc_old.balance_qty_cut_qc) AS before_balance
		FROM (
		
			SELECT 
				'' AS id_cut_qc_detail,
				'' AS id_cut_qc,
				cnd.id_cut_number_detail,
				cnd.id_cut_out_detail,
				cnd.id_cost,
				cnd.id_so_det,
				cnd.id_item,
				cnd.color,
				cod.fabric_desc,
				cod.size,
				cod.id_grouping,
				cod.name_grouping,
				cod.id_panel,
				cod.nama_panel,
				cod.lot,
				sd.qty AS qty_so,
				cnd.number_cutting,
				cnd.number_bundle,
				cnd.number_sack,
				'' AS qty_input_qc,
				'' AS qty_reject_qc,
				'' AS qty_cut_qc,
				'' AS remarks,
				'' AS approve
			FROM prod_cut_number_detail AS cnd
			INNER JOIN (
				SELECT 
					cod.id_cut_out_detail,
					cod.id_cut_out,
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
			) AS cod ON cod.id_cut_out_detail = cnd.id_cut_out_detail
			INNER JOIN so_det AS sd ON sd.id = cnd.id_so_det
			WHERE cnd.id_cost = '{$cost}' #AND cnd.color = '{$color}'

			AND cnd.id_cut_number_detail IN (
				SELECT d_num.id_cut_number_detail FROM (
					SELECT 
						MAX(d_cnd.id_cut_number_detail) AS id_cut_number_detail,
						d_cnd.id_cut_out_detail,
						d_cnd.id_so_det,
						d_cod.id_grouping,
						d_cod.id_panel,
						d_cod.lot,
						d_cnd.number_cutting,
						d_cnd.number_bundle,
						d_cnd.number_sack
					FROM prod_cut_number_detail AS d_cnd
					INNER JOIN (
						SELECT 
							d_cod.id_cut_out_detail,
							d_cod.id_grouping,
							d_cod.id_panel,
							d_cod.lot
						FROM prod_cut_out_detail AS d_cod
					) AS d_cod ON d_cod.id_cut_out_detail = d_cnd.id_cut_out_detail
					WHERE d_cnd.id_cost = '{$cost}' #AND d_cnd.color = '{$color}'	
					GROUP BY d_cod.id_grouping,d_cod.id_panel,d_cnd.id_so_det,d_cod.lot	
				) AS d_num
			)

			GROUP BY cod.id_grouping,cod.id_panel,cnd.id_so_det,cod.lot
		
		) AS qc_new

		LEFT JOIN (
			{$sql_balance}
		) AS qc_old ON qc_old.id_so_det = qc_new.id_so_det 
		AND qc_old.id_panel = qc_new.id_panel 
		AND qc_old.id_item = qc_new.id_item
		AND qc_old.lot = qc_new.lot
	
	) AS X";

}
else{

	$table = "(

		SELECT 
			qc_new.*,
			qc_old.tot_qty_cut_qc AS cumulative,
			qc_old.balance_qty_cut_qc AS balance,
			(qc_old.tot_qty_cut_qc - qc_new.qty_cut_qc) AS before_cumulative,
			(qc_old.tot_qty_cut_qc + qc_old.balance_qty_cut_qc) AS before_balance
		FROM (

			SELECT 
				cqd.id_cut_qc_detail,
				cqd.id_cut_qc,
				cqd.id_cut_number_detail,
				cqd.id_cut_out_detail,
				cqd.id_cost,
				cqd.id_so_det,
				cqd.id_item,
				cod.fabric_desc,
				cqd.color,
				cod.size,
				cod.id_grouping,
				cod.name_grouping,
				cod.id_panel,
				cod.nama_panel,
				cod.lot,
				sd.qty AS qty_so,
				cnd.number_cutting,
				cnd.number_bundle,
				cnd.number_sack,
				cqd.qty_input_qc,
				cqd.qty_reject_qc,
				cqd.qty_cut_qc,
				cqd.remarks,
				cqd.approve
			FROM prod_cut_qc_detail AS cqd
			INNER JOIN (
				SELECT 
					cnd.id_cut_number_detail,
					cnd.id_cut_number,
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
			INNER JOIN so_det AS sd ON sd.id = cqd.id_so_det
			WHERE cqd.id_cut_qc = '{$cut_qc}' AND cqd.id_cost = '{$cost}' #AND cqd.color = '{$color}'

		) AS qc_new

		LEFT JOIN (
			{$sql_balance}
		) AS qc_old ON qc_old.id_so_det = qc_new.id_so_det 
		AND qc_old.id_panel = qc_new.id_panel 
		AND qc_old.id_item = qc_new.id_item
		AND qc_old.lot = qc_new.lot
	
	) AS X";

}

$colomn = "
	X.id_cut_qc_detail,
	X.id_cut_qc,
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
	X.qty_input_qc,
	X.qty_reject_qc,
	X.qty_cut_qc,
	X.remarks,
	X.approve,
	X.before_cumulative,
	X.before_balance
";

$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "X.id_grouping ASC,X.id_panel ASC,X.lot ASC,X.id_so_det ASC"; // Column name 
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
	$inputCumulative .="<input type='text' id='cml_".$no."' class='form-control cutt forms' value='$row[cumulative]' onchange='handleKeyUp(this)' style='min-width: 80px' readonly>";

	$inputBalance = '';
	$inputBalance .="<input type='text' id='bal_".$no."' class='form-control cutt forms' value='$row[balance]' onchange='handleKeyUp(this)' style='min-width: 80px' readonly>";

	$inputCutting = '';
	$inputCutting .="<input type='text' id='cut_".$no."' class='form-control' value='$row[number_cutting]' style='min-width: 80px' readonly>";

	$inputBundle = '';
	$inputBundle .="<input type='text' id='bundle_".$no."' class='form-control forms' value='$row[number_bundle]' style='min-width: 80px' readonly>";

	$inputSack = '';
	$inputSack .="<input type='text' id='sack_".$no."' class='form-control forms' value='$row[number_sack]' style='min-width: 80px' readonly>";

	if($row['remarks'] == ''){
		$remark = "";
	}
	else{
		$remark = $row['remarks'];
	}
	$inputRemarks = '';
	$inputRemarks .="<input type='text' id='remark_".$no."' class='form-control' value='$remark' onchange='handleKeyUp(this)' style='min-width: 80px'>";

	if($row['qty_input_qc'] == ''){
		$qtyInQC = "";
	}
	else{
		$qtyInQC = $row['qty_input_qc'];
	}
	$inputQtyInQC = '';
	$inputQtyInQC .="<input type='number' id='qtyInQC_".$no."' class='form-control forms' value='$qtyInQC' onchange='handleKeyUp(this)' style='min-width: 80px'>";
	
	if($row['qty_reject_qc'] == ''){
		$reject = "";
	}
	else{
		$reject = $row['qty_reject_qc'];
	}
	$inputReject = '';
	$inputReject .="<input type='number' id='reject_".$no."' class='form-control forms' value='$reject' onchange='handleKeyUp(this)' style='min-width: 80px'>";

	if($row['qty_cut_qc'] == ''){
		$qtyQC = "";
	}
	else{
		$qtyQC = $row['qty_cut_qc'];
	}
	$inputQtyQC = '';
	$inputQtyQC .="<input type='text' id='qtyQC_".$no."' class='form-control forms' value='$qtyQC' disabled style='min-width: 80px'>";
	
	
	if($row['approve'] == 'Y'){
		$check = "checked='checked'";
	}
	else{
		$check = "";
	}
	$cekApprove = '';
	$cekApprove .="<input type='checkbox' id='check_".$no."' class='cek' value='1' $check onclick='handleKeyUp(this)'>";

	$no++;
	

	$data[] = array(
		"num"=>$num,
		"id_cut_qc_detail"=>htmlspecialchars($row['id_cut_qc_detail']),
		"id_cut_qc"=>htmlspecialchars($row['id_cut_qc']),
		"id_cut_number_detail"=>htmlspecialchars($row['id_cut_number_detail']),
		"id_cut_out_detail"=>htmlspecialchars($row['id_cut_out_detail']),
		"id_cost"=>rawurldecode($row['id_cost']),
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
		"number_cutting_val"=>"",
		"number_bundle"=>rawurlencode($inputBundle),
		"number_bundle_val"=>"",
		"number_sack"=>rawurlencode($inputSack),
		"number_sack_val"=>"",
		"qty_input_qc"=>rawurlencode($inputQtyInQC),
		"qty_input_qc_val"=>(ISSET($row['qty_input_qc']) ? $row['qty_input_qc'] : 0 ),
		"reject_qc_qty"=>rawurlencode($inputReject),
		"reject_qc_qty_val"=>(ISSET($row['qty_reject_qc']) ? $row['qty_reject_qc'] : 0 ),
		"qc_qty"=>rawurlencode($inputQtyQC),
		"qc_qty_val"=>(ISSET($row['qty_cut_qc']) ? $row['qty_cut_qc'] : 0 ),
		"remarks"=>rawurlencode($inputRemarks),
		"remarks_val"=>(ISSET($row['remarks']) ? $row['remarks']  : ""  ),
		"check_approve"=>rawurlencode($cekApprove),
		"check_val"=>(ISSET($row['approve']) ? $row['approve']  : "N"  ),
		"before_cumulative"=>htmlspecialchars($row['before_cumulative']),
		"before_balance"=>htmlspecialchars($row['before_balance'])
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