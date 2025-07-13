<?php 
include __DIR__ .'/../../../include/conn.php';
include 'production_interface.php';
## Read value
$draw = $_POST['draw']; 
$row = $_POST['start'];

$cut_out = $_GET['id_cut_out'];
$id_cost = $_GET['id_cost'];
$color = $_GET['color'];
$id_item = $_GET['id_item'];
// $id_url = $_GET['id_url'];
// print_r($id_cost);die();

$sql_balance = sql_balance_cutting($id_cost);

$table = "(

	SELECT 
		Z.id_cod,
		Z.id_cat,
		Z.id_so_det,
		Z.id_item,
		Z.id_panel,
		Z.id_grouping,
		Z.lot,	
		Z.fabric_code,
		Z.color,
		Z.fabric_desc,
		Z.name_grouping,
		Z.nama_panel,
		Z.size,
		Z.qty_so,
		Z.cumulative,
		Z.balance,
		Z.cutting_output,
		Z.reject,
		Z.qty_cut_out
	FROM (

		SELECT 
			Q.id_cod,
			Q.id_cat,
			Q.id_so_det,
			Q.id_item,
			Q.id_panel,
			Q.id_grouping,
			Q.fabric_code,
			Q.color,
			Q.fabric_desc,
			Q.lot,
			Q.name_grouping,
			Q.nama_panel,
			Q.size,
			Q.qty_so,
			R.tot_qty_cut_out AS cumulative,
			R.balance_qty_cut_out AS balance,
			Q.cutting_output,
			Q.reject,
			Q.qty_cut_out
		FROM (
			SELECT     
				cod.id_cut_out_detail AS id_cod,
				cod.id_cut_out,
				cod.id_cat,
				cod.id_so_det,
				cod.id_item,
				cod.fabric_code,
				cod.color,
				cod.fabric_desc,
				mg.name_grouping,
				cod.id_grouping,
				cod.id_panel,
				mp.nama_panel,
				cod.lot,
				cod.size,
				sd.qty_so,
				cod.cutting_output,
				cod.reject,
				cod.qty_cut_out
			FROM prod_cut_out_detail AS cod 
			LEFT JOIN mastergrouping AS mg ON mg.id_grouping=cod.id_grouping
			LEFT JOIN masterpanel AS mp ON mp.id=cod.id_panel
			INNER JOIN (
				SELECT 
					sd.id,
					sd.size,
					sd.qty AS qty_so
				FROM so_det AS sd
			) AS sd ON sd.id = cod.id_so_det
			WHERE cod.id_cut_out = '{$cut_out}'
		) AS Q
		LEFT JOIN (
			{$sql_balance}
		) AS R ON R.id_so_det = Q.id_so_det AND R.id_panel = Q.id_panel AND R.id_item = Q.id_item
				
		UNION ALL 
			
		SELECT 
			P.id_cod,	
			P.id_cat,
			P.id_so_det,
			P.id_item,
			P.id_panel,
			P.id_grouping,
			P.fabric_code,
			P.color,
			P.fabric_desc,
			P.lot,
			P.name_grouping,
			P.nama_panel,
			P.size,
			P.qty_so,
			IFNULL(O.tot_qty_cut_out, 0) AS cumulative,
			IF(O.balance_qty_cut_out IS NULL, P.qty_so, O.balance_qty_cut_out) AS balance,
			P.cutting_output,
			P.reject,
			P.qty_cut_out
		FROM (
			SELECT 
				'0' AS id_cod,
				coc.id_cat,
				coc.id_item,
				coc.fabric_code,
				coc.color,
				coc.fabric_desc,
				coc.id_grouping,
				mr.id_panel,
				mr.lot,
				mg.name_grouping,
				mr.nama_panel,
				mr.id_so_det,
				mr.size,
				mr.qty_so,
				'' AS cutting_output,
				'' AS reject,
				'' AS qty_cut_out
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
					) AS s ON s.id = srn.id_so
					INNER JOIN masterpanel AS pnl ON pnl.id = srn.id_panel
				) AS srn ON srn.id_number = mr.id_number
				GROUP BY srn.id_panel,mr.lot,srn.size
			) AS mr ON mr.id_cost = coc.id_cost AND mr.fabric_color = coc.color AND mr.id_item = coc.id_item
					
			WHERE coc.id_cost = '{$id_cost}' 
			AND coc.color = '{$color}' 
			AND coc.id_item = '{$id_item}'
			AND coc.is_save = 'N'
			AND coc.id_cat NOT IN (	
				SELECT X.id_cat FROM (
					SELECT A.id_cat,B.id_cut_out_detail,B.id_cat cat_det FROM prod_cut_out_category A
					LEFT JOIN prod_cut_out_detail B ON A.id_cat = B.id_cat
					WHERE 1=1 AND B.id_cat IS NOT NULL
				) 
			X)
		) AS P
		LEFT JOIN (
			{$sql_balance}
		) AS O ON O.id_so_det = P.id_so_det AND O.id_panel = P.id_panel AND O.id_item = P.id_item

	) Z

) AS X";

$colomn = "
	X.id_cod,
	X.id_cat,
	X.id_so_det,
	X.id_item,
	X.id_panel,
	X.id_grouping,	
	X.fabric_code,
	X.color,
	X.fabric_desc,
	X.lot,
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
		"okeCutt"=>htmlspecialchars($row['qty_cut_out']),
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