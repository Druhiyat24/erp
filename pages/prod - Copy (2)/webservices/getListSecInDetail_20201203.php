<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];

$id_url = $_GET['id_url'];
$id_ws = $_GET['id_ws'];
$id_panel = $_GET['id_panel'];
$id_process = $_GET['id_process'];

// print_r($id_panel);die();

if($id_url == '-1' OR $id_url == 'undefined'){
	$table = "(

		SELECT 
			'' AS id_sec_in_detail,
			'' AS id_sec_in,
			qc.id_cut_qc,
			qc.id_cut_number,
			qc.id_cut_out_detail,
			qc.id_cost,
			qc.color,
			cod.fabric_desc,
			cod.size,
			cod.id_grouping,
			mg.name_grouping,
			cod.id_panel,
			mp.nama_panel,
			cod.lot,
			qc.qty_cut_qc,
			cn.number_cutting,
			cn.number_bundle,
			cn.number_sack,
			'' AS qty_reject_sec_in,
			'' AS qty_sec_in,
			qc.approve
		FROM prod_cut_qc AS qc
		INNER JOIN (
			SELECT 
				cn.id_cut_number,
				cn.number_cutting,
				cn.number_bundle,
				cn.number_sack
			FROM prod_cut_number AS cn
		) AS cn ON cn.id_cut_number = qc.id_cut_number
		INNER JOIN (
			SELECT 
				cod.id_cut_out_detail,
				cod.fabric_desc,
				cod.size,
				cod.id_grouping,
				cod.id_panel,
				cod.lot
			FROM prod_cut_out_detail AS cod
		) AS cod ON cod.id_cut_out_detail = qc.id_cut_out_detail
		INNER JOIN mastergrouping AS mg ON mg.id_grouping = cod.id_grouping
		INNER JOIN masterpanel AS mp ON mp.id = cod.id_panel
		WHERE qc.id_cost = '{$id_ws}' AND cod.id_panel IN ($id_panel) AND qc.approve = 'Y'

	) AS X";

}
else{
	$table = "(

		SELECT 
			si.id_sec_in,
			si.id_cost,
			sid.id_sec_in_detail,
			sid.id_cut_out_detail,
			sid.id_cut_qc,
			sid.id_cut_number,
			qc.fabric_desc,
			qc.color,
			qc.size,
			qc.name_grouping,
			sid.id_panel,
			mp.nama_panel,
			qc.lot,
			qc.qty_cut_qc,
			qc.number_cutting,
			qc.number_bundle,
			qc.number_sack,
			sid.qty_reject_sec_in,
			sid.qty_sec_in,
			qc.approve
		FROM prod_sec_in AS si
		INNER JOIN (
			SELECT 
				sid.id_sec_in_detail,
				sid.id_sec_in,
				sid.id_cut_out_detail,
				sid.id_cut_qc,
				sid.id_cut_number,
				sid.id_panel,
				sid.qty_reject_sec_in,
				sid.qty_sec_in
			FROM prod_sec_in_detail AS sid
		) AS sid ON sid.id_sec_in = si.id_sec_in
		INNER JOIN (
			SELECT 
				qc.id_cut_qc,
				qc.id_cut_number,
				qc.id_cut_out_detail,
				qc.color,
				qc.qty_cut_qc,
				cod.fabric_desc,
				cn.number_cutting,
				cn.number_bundle,
				cn.number_sack,
				cod.id_grouping,
				cod.name_grouping,
				cod.lot,
				cod.size,
				qc.approve
			FROM prod_cut_qc AS qc
			INNER JOIN (
				SELECT 
					cn.id_cut_number,
					cn.number_cutting,
					cn.number_bundle,
					cn.number_sack
				FROM prod_cut_number AS cn
			) AS cn ON cn.id_cut_number = qc.id_cut_number
			INNER JOIN (
				SELECT 
					cod.id_cut_out_detail,
					cod.fabric_desc,
					cod.id_grouping,
					cod.lot,
					cod.size,
					mg.name_grouping
				FROM prod_cut_out_detail AS cod
				INNER JOIN mastergrouping AS mg ON mg.id_grouping = cod.id_grouping
			) AS cod ON cod.id_cut_out_detail = qc.id_cut_out_detail
		) AS qc ON qc.id_cut_qc = sid.id_cut_qc
		INNER JOIN masterpanel AS mp ON mp.id = sid.id_panel
		WHERE si.id_sec_in = '{$id_url}'

	) AS X";
}
		
$colomn = "X.id_sec_in_detail,
			X.id_sec_in,
			X.id_cut_qc,
			X.id_cut_number,
			X.id_cut_out_detail,
			X.id_cost,
			X.color,
			X.fabric_desc,
			X.size,
			X.name_grouping,
			X.id_panel,
			X.nama_panel,
			X.lot,
			X.qty_cut_qc,
			X.number_cutting,
			X.number_bundle,
			X.number_sack,
			X.qty_reject_sec_in,
			X.qty_sec_in,
			X.approve
";

//print_r($id_panel);die();

$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "id"; // Column name 
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


$empQuery = "select $colomn from $table WHERE 1 ".$searchQuery. "ORDER BY X.id_cut_qc ASC";
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
// echo $empQuery;die();

$no = 1;
$num = 0;
while ($row = mysqli_fetch_assoc($empRecords)) {

	$inputQtyQC = '';
	$inputQtyQC .="<input type='text' id='qtyCN_".$num."' class='form-control forms' value='$row[qty_cut_qc]' disabled style='text-align: right;'>";

	$inputCutting = '';
	$inputCutting .="<input type='text' id='cut_".$num."' class='form-control' value='$row[number_cutting]' readonly>";

	$inputBundle = '';
	$inputBundle .="<input type='text' id='bundle_".$num."' class='form-control forms' value='$row[number_bundle]' readonly>";

	$inputSack = '';
	$inputSack .="<input type='text' id='sack_".$num."' class='form-control forms' value='$row[number_sack]' readonly>";

	if($row['qty_reject_sec_in'] == ''){
		$reject = "0";
	}
	else{
		$reject = $row['qty_reject_sec_in'];
	}
	$inputQtyReject = '';
	$inputQtyReject .= "<input type='text' id='reject_".$num."' class='form-control' value='$reject' onchange='handlekeyup(this)' style='text-align: right;'>";

	if($row['qty_sec_in'] == ''){
		$qtySI = $row['qty_cut_qc'];
	}
	else{
		$qtySI = $row['qty_sec_in'];
	}
	$inputQtySecIn = '';
	$inputQtySecIn .= "<input type='text' id='qtySecIn_".$num."' class='form-control' value='$qtySI' onchange='handlekeyup(this)' style='text-align: right;' disabled>";
	
	$num++;


	$data[] = array(
		"no"=>$no,
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
		"id_panel"=>htmlspecialchars($row['id_panel']),
		"nama_panel"=>htmlspecialchars($row['nama_panel']),
		"lot"=>htmlspecialchars($row['lot']),
		"qty_qc"=>rawurldecode($inputQtyQC),
		"qty_qc_val"=>htmlspecialchars($row['qty_cut_qc']),
		"number_cutting"=>rawurlencode($inputCutting),
		"number_cutting_val"=>"",
		"number_bundle"=>rawurlencode($inputBundle),
		"number_bundle_val"=>"",
		"number_sack"=>rawurlencode($inputSack),
		"number_sack_val"=>"",
		"qty_reject_si"=>rawurldecode($inputQtyReject),
		"qty_reject_si_val"=>(ISSET($row['qty_reject_sec_in']) ? $row['qty_reject_sec_in']  : 0 ),
		"qty_sec_in"=>rawurlencode($inputQtySecIn),
		"qty_sec_in_val"=>(ISSET($row['qty_sec_in']) ? $row['qty_sec_in']  : $row['qty_cut_qc']  )
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