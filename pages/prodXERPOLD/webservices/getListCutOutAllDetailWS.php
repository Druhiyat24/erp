<?php 
include __DIR__ .'/../../../include/conn.php';
include 'production_interface.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];

// $id_url = $_GET['id_url'];     
$id_cost = $_GET['cost'];
$color = $_GET['color'];
$id_item = $_GET['item'];
// print_r($id_item);die(); 


$sql_balance = sql_balance_cutting($id_cost);
$table = "(

	SELECT 
		cut_new.*,
		IFNULL(cut_old.tot_qty_cut_out, 0) AS cumulative,
		IF(cut_old.balance_qty_cut_out IS NULL, cut_new.qty_so, cut_old.balance_qty_cut_out) AS balance
	FROM (

		SELECT 
			coc.id_cat,
			coc.id_cost,
			coc.id_item,
			coc.fabric_code,
			coc.fabric_desc,
			coc.color,
			coc.id_grouping,
			mr.id_number,
			mg.name_grouping,
			mr.lot,
			mr.id_panel,
			mr.nama_panel,
			mr.id_so_det,
			mr.size,
			mr.qty_so
		FROM prod_cut_out_category AS coc
		INNER JOIN mastergrouping AS mg ON mg.id_grouping = coc.id_grouping
		INNER JOIN (
			SELECT 
				mr.id_m_roll,
				mr.id_cost,
				mr.id_number,
				mr.id_item,
				mr.fabric_color,
				mr.lot,
				srn.id_panel,
				srn.nama_panel,
				srn.id_so_det,
				srn.size,
				srn.qty_so
			FROM prod_m_roll AS mr
			INNER JOIN (
				SELECT 
					srn.id_number,
					srn.id_mark_entry,
					srn.id_panel,
					srn.id_so,
					pnl.nama_panel,
					s.id_so_det,
					s.size,
					s.qty_so
				FROM prod_spread_report_number AS srn		
				INNER JOIN (
					SELECT 
						s.id,
						sd.id_so_det,
						sd.size,
						sd.qty_so
					FROM so AS s
					INNER JOIN (
						SELECT 
							sd.id AS id_so_det,
							sd.id_so,
							sd.size,
							sd.qty AS qty_so,
							sd.color
						FROM so_det AS sd
						WHERE sd.cancel = 'N'
						AND sd.color = '{$color}'
					) AS sd ON sd.id_so = s.id
					WHERE s.cancel_h = 'N'
				) AS s ON s.id = srn.id_so
				INNER JOIN masterpanel AS pnl ON pnl.id = srn.id_panel
			) AS srn ON srn.id_number = mr.id_number
			GROUP BY srn.id_panel,mr.lot,srn.size
		) AS mr ON mr.id_cost = coc.id_cost AND mr.fabric_color = coc.color AND mr.id_item = coc.id_item
		WHERE coc.id_cost = '{$id_cost}'
		AND coc.color = '{$color}'
		AND coc.id_item = '{$id_item}'
		AND coc.is_save = 'N'
		
	) AS cut_new

	LEFT JOIN (
		{$sql_balance}
	) AS cut_old ON cut_old.id_so_det = cut_new.id_so_det AND cut_old.id_panel = cut_new.id_panel
	GROUP BY cut_new.id_so_det,cut_new.id_panel

) AS X";
		
$colomn = "
	X.id_cat,
	X.id_cost,
	X.id_item,
	X.id_so_det,
	X.fabric_code,
	X.fabric_desc,
	X.color,
	X.id_grouping,
	X.name_grouping,
	X.id_panel,
	X.nama_panel,
	X.lot,
	X.size,
	X.qty_so,
	X.cumulative,
	X.balance
";


 //print_r($id_panel);die();

$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "X.id_panel ASC, X.id_so_det ASC"; // Column name 
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value 

### Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (
   		X.fabric_code		LIKE'%".$searchValue."%'
		OR X.fabric_desc	LIKE'%".$searchValue."%'
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
	$inputCutt .="<input type='text' id='cutt_".$no."' class='form-control cutt forms' value='$row[cutting_output]' onchange='handleKeyUp(this);cuttNumber(this)'>";

	$inputReject = '';
	$inputReject .="<input type='text' id='reject_".$no."' class='form-control reject forms' value='$row[reject]' onchange='handleKeyUp(this);rejectNumber(this)'>";

	$fieldOkeCutt = '';
	$fieldOkeCutt .="<input type='text' id='oke_".$no."' class='form-control oke forms' value='$row[qty_cut_out]' onmouseover='mouseOver(this)' readonly>";
	$no++;
	

	$data[] = array(
		"num"=>$num,
		"id"=>htmlspecialchars($row['id_cat']),
		"id_item"=>htmlspecialchars($row['id_item']),
		"goods_code"=>rawurldecode($row['fabric_code']),
		"color"=>rawurldecode($row['color']),
		"itemdesc"=>rawurldecode($row['fabric_desc']),
		"grouping"=>htmlspecialchars($row['name_grouping']),
		"idg"=>htmlspecialchars($row['id_grouping']),
		"panel"=>htmlspecialchars($row['nama_panel']),
		"idp"=>htmlspecialchars($row['id_panel']),
		"lot"=>rawurldecode($row['lot']),
		"id_so_det"=>htmlspecialchars($row['id_so_det']),
		"size"=>rawurldecode($row['size']),
		"qtySO"=>rawurlencode($inputQtySO),
		"qtySO_val"=>"",
		"cumulative"=>rawurlencode($inputCumulative),
		"cumulative_val"=>"",
		"balance"=>rawurlencode($inputBalance),
		"balance_val"=>"",
		"inputCutt"=>rawurlencode($inputCutt),
		"cutt"=>"",
		"inputReject"=>rawurlencode($inputReject),
		"reject"=>"",
		"fieldOkeCutt"=>rawurlencode($fieldOkeCutt),
		"okeCutt"=>""
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