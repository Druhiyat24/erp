<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];

$id_url = $_GET['id_url'];

// print_r($id_url);die();

$sql = "SELECT qc.id_sec_out FROM prod_sec_qc AS qc WHERE qc.id_sec_out = '{$id_url}'";
// echo $sql;die();
$stmt = mysql_query($sql);
$rowQC = mysql_fetch_array($stmt);

if($rowQC['id_sec_out'] == $id_url){
	$table = "(

		SELECT 
			qc.id_sec_qc,
			qc.id_sec_out_detail,
			qc.id_sec_out,
			qc.id_sec_in_detail,
			sod.id_sec_in,
			qc.id_cut_out_detail,
			qc.id_cut_number,
			qc.id_cut_qc,
			sod.id_cost,
			cod.fabric_desc,
			cod.color,
			cod.size,
			cod.id_grouping,
			cod.name_grouping,
			cod.id_panel,
			cod.nama_panel,
			cod.lot,
			sod.qty_sec_out,
			cod.number_cutting,
			cod.number_bundle,
			cod.number_sack,
			qc.qty_reject_sec_qc,
			qc.qty_sec_qc,
			qc.approve_sec
		FROM prod_sec_qc AS qc
		INNER JOIN (
			SELECT 
				sod.id_sec_out_detail,
				sod.id_sec_out,
				sod.id_sec_in_detail,
				sid.id_sec_in,
				os.id_cost,
				sod.qty_sec_out
			FROM prod_sec_out_detail AS sod
			INNER JOIN (
				SELECT 
					os.id_sec_out,
					os.id_cost
				FROM prod_sec_out AS os
			) AS os ON os.id_sec_out = sod.id_sec_out
			INNER JOIN (
				SELECT 
					sid.id_sec_in_detail,
					sid.id_sec_in
				FROM prod_sec_in_detail AS sid
			) AS sid ON sid.id_sec_in_detail = sod.id_sec_in_detail
			WHERE os.id_sec_out = '{$id_url}'
		) AS sod ON sod.id_sec_out_detail = qc.id_sec_out_detail
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
				cod.lot,
				cn.number_cutting,
				cn.number_bundle,
				cn.number_sack
			FROM prod_cut_out_detail AS cod
			INNER JOIN mastergrouping AS mg ON mg.id_grouping = cod.id_grouping
			INNER JOIN masterpanel AS mp ON mp.id = cod.id_panel
			INNER JOIN (
				SELECT 
					cn.id_cut_number,
					cn.id_cut_out_detail,
					cn.number_cutting,
					cn.number_bundle,
					cn.number_sack
				FROM prod_cut_number AS cn
			) AS cn ON cn.id_cut_out_detail = cod.id_cut_out_detail
		) AS cod ON cod.id_cut_out_detail = qc.id_cut_out_detail
		WHERE qc.id_sec_out = '{$id_url}'
		
	) AS X";

}
else{
	$table = "(
		
		SELECT 
			'' AS id_sec_qc,
			os.id_sec_out,
			os.id_cost,
			sod.id_sec_out_detail,
			sod.id_sec_in,
			sod.id_sec_in_detail,
			sod.id_cut_out_detail,
			sod.id_cut_number,
			sod.id_cut_qc,
			sod.fabric_desc,
			sod.color,
			sod.size,
			sod.name_grouping,
			sod.nama_panel,
			sod.lot,
			sod.qty_sec_out,
			sod.number_cutting,
			sod.number_bundle,
			sod.number_sack,
			'' AS qty_reject_sec_qc,
			'' AS qty_sec_qc,
			'' AS approve_sec
		FROM prod_sec_out AS os
		INNER JOIN (
			SELECT 
				sod.id_sec_out_detail,
				sod.id_sec_out,
				sod.id_sec_in_detail,
				sid.id_sec_in,
				sod.id_cut_out_detail,
				sod.id_cut_number,
				sod.id_cut_qc,
				cod.fabric_desc,
				cod.color,
				cod.size,
				cod.id_grouping,
				cod.name_grouping,
				cod.id_panel,
				cod.nama_panel,
				cod.lot,
				sod.qty_sec_out,
				cn.number_cutting,
				cn.number_bundle,
				cn.number_sack
			FROM prod_sec_out_detail AS sod
			INNER JOIN (
				SELECT 
					sid.id_sec_in_detail,
					sid.id_sec_in
				FROM prod_sec_in_detail AS sid
			) AS sid ON sid.id_sec_in_detail = sod.id_sec_in_detail
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
			) AS cod ON cod.id_cut_out_detail = sod.id_cut_out_detail
			INNER JOIN (
				SELECT 
					cn.id_cut_number,
					cn.number_cutting,
					cn.number_bundle,
					cn.number_sack
				FROM prod_cut_number AS cn
			) AS cn ON cn.id_cut_number = sod.id_cut_number
		) AS sod ON sod.id_sec_out = os.id_sec_out
		WHERE os.id_sec_out = '{$id_url}'

	) AS X";
}
		
$colomn = "X.id_sec_qc,
			X.id_sec_out_detail,
			X.id_sec_out,
			X.id_sec_in_detail,
			X.id_sec_in,
			X.id_cut_qc,
			X.id_cut_number,
			X.id_cut_out_detail,
			X.id_cost,
			X.color,
			X.fabric_desc,
			X.size,
			X.name_grouping,
			X.nama_panel,
			X.lot,
			X.qty_sec_out,
			X.number_cutting,
			X.number_bundle,
			X.number_sack,
			X.qty_reject_sec_qc,
			X.qty_sec_qc,
			X.approve_sec
";

$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "id_sec_in"; // Column name 
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


$empQuery = "select $colomn from $table WHERE 1 ".$searchQuery;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
// echo $empQuery;die();

$no = 1;
$num = 0;
while ($row = mysqli_fetch_assoc($empRecords)) {

	$inputQtySecOut = '';
	$inputQtySecOut .="<input type='text' id='qtySecOut_".$num."' class='form-control forms' value='$row[qty_sec_out]' disabled style='text-align: right;'>";

	$inputCutting = '';
	$inputCutting .="<input type='text' id='cut_".$num."' class='form-control' value='$row[number_cutting]' readonly>";

	$inputBundle = '';
	$inputBundle .="<input type='text' id='bundle_".$num."' class='form-control forms' value='$row[number_bundle]' readonly>";

	$inputSack = '';
	$inputSack .="<input type='text' id='sack_".$num."' class='form-control forms' value='$row[number_sack]' readonly>";

	if($row['qty_reject_sec_qc'] == ''){
		$reject = "0";
	}
	else{
		$reject = $row['qty_reject_sec_qc'];
	}
	$inputQtyReject = '';
	$inputQtyReject .= "<input type='text' id='reject_".$num."' class='form-control' value='$reject' onchange='handlekeyup(this)' style='text-align: right;'>";

	if($row['qty_sec_qc'] == ''){
		$qtySO = $row['qty_sec_out'];
	}
	else{
		$qtySO = $row['qty_sec_qc'];
	}
	$inputQtySecQC = '';
	$inputQtySecQC .= "<input type='text' id='qtySecQC_".$num."' class='form-control' value='$qtySO' onchange='handlekeyup(this)' style='text-align: right;' disabled>";

	if($row['approve_sec'] == 'Y'){
		$check = "checked='checked'";
	}
	else{
		$check = "";
	}
	$cekApprove = '';
	$cekApprove .="<input type='checkbox' id='check_".$num."' class='cek' value='1' $check onclick='handlekeyup(this)'>";
	
	$num++;

	$data[] = array(
		"no"=>$no,
		"id_sec_out_detail"=>htmlspecialchars($row['id_sec_out_detail']),
		"id_sec_out"=>htmlspecialchars($row['id_sec_out']),
		"id_sec_in_detail"=>htmlspecialchars($row['id_sec_in_detail']),
		"id_sec_in"=>htmlspecialchars($row['id_sec_in']),
		"id_cut_qc"=>htmlspecialchars($row['id_cut_qc']),
		"id_cut_number"=>htmlspecialchars($row['id_cut_number']),
		"id_cut_out_detail"=>htmlspecialchars($row['id_cut_out_detail']),
		"id_cost"=>htmlspecialchars($row['id_cost']),
		"fabric_desc"=>htmlspecialchars($row['fabric_desc']),
		"color"=>htmlspecialchars($row['color']),
		"size"=>htmlspecialchars($row['size']),
		"name_grouping"=>htmlspecialchars($row['name_grouping']),
		"nama_panel"=>htmlspecialchars($row['nama_panel']),
		"lot"=>htmlspecialchars($row['lot']),
		"qty_sec_out"=>rawurldecode($inputQtySecOut),
		"qty_sec_out_val"=>htmlspecialchars($row['qty_sec_out']),
		"number_cutting"=>rawurlencode($inputCutting),
		"number_cutting_val"=>"",
		"number_bundle"=>rawurlencode($inputBundle),
		"number_bundle_val"=>"",
		"number_sack"=>rawurlencode($inputSack),
		"number_sack_val"=>"",
		"qty_reject_qc"=>rawurldecode($inputQtyReject),
		"qty_reject_qc_val"=>(ISSET($row['qty_reject_sec_qc']) ? $row['qty_reject_sec_qc']  : 0 ),
		"qty_sec_qc"=>rawurlencode($inputQtySecQC),
		"qty_sec_qc_val"=>(ISSET($row['qty_sec_qc']) ? $row['qty_sec_qc']  : $row['qty_sec_out']  ),
		"check_approve"=>rawurlencode($cekApprove),
		"check_val"=>(ISSET($row['approve_sec']) ? $row['approve_sec']  : "N"  )
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