<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw']; 
$row = $_POST['start'];

$cut_out = $_GET['id_cut_out'];
$id_cost = $_GET['id_cost'];
/*  print_r($_GET);die(); */
 
$table = "(
		SELECT 
		Z.id_cod,
		Z.id_cat,
		Z.id_panel,
		Z.id_grouping,		
		Z.fabric_code,
		Z.fabric_desc,
		Z.name_grouping,
		Z.nama_panel,
		Z.number_from,
		Z.number_to,
		Z.embro,
		Z.printing,
		Z.heat_transfer,
		Z.size,
		Z.date_detail,
		Z.cutting_output,
		Z.reject,
		Z.ok_cutt
	FROM (


		SELECT 
			Q.id_cod,
			Q.id_cat,
			Q.id_panel,
			Q.id_grouping,
			Q.fabric_code,
			Q.fabric_desc,
			Q.name_grouping,
			Q.nama_panel,
			Q.number_from,
			Q.number_to,
			Q.embro,
			Q.printing,
			Q.heat_transfer,
			Q.size,
			Q.date_detail,
			Q.cutting_output,
			Q.reject,
			Q.ok_cutt
		FROM (
			SELECT     
				cod.id_cut_out_detail AS id_cod,
				cod.id_cut_out,
				cod.id_cat,
				cod.fabric_code,
				cod.fabric_desc,
				mg.name_grouping,
				cod.id_grouping,
				cod.id_panel,
				mp.nama_panel,
				cod.number_from,
				cod.number_to,
				cod.embro,
				cod.printing,
				cod.heat_transfer,
				cod.size,
				cod.date_detail,
				cod.cutting_output,
				cod.reject,
				cod.ok_cutt
			FROM cut_out_detail AS cod 
			LEFT JOIN mastergrouping AS mg ON mg.id_grouping=cod.id_grouping
			LEFT JOIN masterpanel AS mp ON mp.id=cod.id_panel
			WHERE cod.id_cut_out='{$cut_out}'
		) AS Q
		
		UNION ALL 
		
		SELECT 
			P.id_cod,	
			P.id_cat,
			P.id_panel,
			P.id_grouping,
			P.fabric_code,
			P.fabric_desc,
			P.name_grouping,
			P.nama_panel,
			P.number_from,
			P.number_to,
			P.embro,
			P.printing,
			P.heat_transfer,
			P.size,
			P.date_detail,
			P.cutting_output,
			P.reject,
			P.ok_cutt
		FROM (
			SELECT 
				'0' AS id_cod,
				coc.id_cat,
				coc.fabric_code,
				coc.fabric_desc,
				coc.id_grouping,
				coc.id_panel,
				mg.name_grouping,
				mp.nama_panel,
				ac.kpno,
				sd.id AS id_sd,
				sd.size,
				'' AS number_from,
				'' AS number_to,
				'' AS embro,
				'' AS printing,
				'' AS heat_transfer,
				'' AS date_detail,
				'' AS cutting_output,
				'' AS reject,
				'' AS ok_cutt
			FROM cut_out_category AS coc
			LEFT JOIN act_costing AS ac ON ac.id = coc.id_cost
			LEFT JOIN so AS s ON s.id_cost = ac.id
			LEFT JOIN so_det AS sd ON sd.id_so = s.id
			LEFT JOIN mastergrouping AS mg ON mg.id_grouping = coc.id_grouping
			LEFT JOIN masterpanel AS mp ON mp.id = coc.id_panel
			LEFT JOIN cut_out_detail AS cod ON cod.id_cat = coc.id_cat
			WHERE coc.id_cost='{$id_cost}' 
			AND coc.id_cat NOT IN (	
									SELECT X.id_cat FROM (
										SELECT A.id_cat,B.id_cut_out_detail,B.id_cat cat_det FROM cut_out_category A
										LEFT JOIN cut_out_detail B ON A.id_cat = B.id_cat
										WHERE 1=1 AND B.id_cat IS NOT NULL
									) X)
		) AS P GROUP BY P.size,P.id_cod

	) Z ORDER BY Z.id_panel ASC
) AS X";

$colomn = "
	X.id_cod
	,X.id_cat
	,X.id_panel
	,X.id_grouping	
	,X.fabric_code
	,X.fabric_desc
	,X.name_grouping
	,X.nama_panel
	,X.number_from
	,X.number_to
	,X.embro
	,X.printing
	,X.heat_transfer
	,X.size
	,X.cutting_output
	,X.reject
	,X.ok_cutt";

$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "id"; // Column name 
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


$empQuery = "select $colomn from $table WHERE 1 ".$searchQuery." ORDER BY X.id_grouping ASC,X.size ASC";
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
  //echo $empQuery;

// $sqlgrup=mysqli_query($conn_li,"SELECT mg.* FROM mastergrouping AS mg WHERE mg.id_grouping='$id_grouping'");
// $rowgrup = mysqli_fetch_assoc($sqlgrup);

// $sqlpanel=mysqli_query($conn_li,"SELECT mp.* FROM masterpanel AS mp WHERE mp.id='$id_panel'");
// $rowpanel = mysqli_fetch_assoc($sqlpanel);
// print_r($rowgrup);die();	

$no = 0;
while ($row = mysqli_fetch_assoc($empRecords)) {
	// echo '123';die();
	//echo $row['n_post'];

	$dt = date("Y-m-d");

	$number_from = " ";
	$number_to = " ";
	$embro = " ";
	$printing = " ";
	$heat_transfer = " ";
	$cutting_output = "0";
	$reject = "0";
	$ok_cutt = "0";
	if($id_url){
		$number_from = $row['number_from'];
		$number_to = $row['number_to'];
		$embro = $row['embro'];
		$printing = $row['printing'];
		$heat_transfer = $row['heat_transfer'];
		$cutting_output = $row['cutting_output'];
		$reject = $row['reject'];
		$ok_cutt = $row['ok_cutt'];
	}

	$inputNumbering1 = '';
	$inputNumbering1 .="<input type='text' id='1numbering_".$no."' class='form-control' value='$row[number_from]' onkeyup='handleKeyUp(this)'>";
	$inputNumbering2 = '';
	$inputNumbering2 .="<input type='text' id='2numbering_".$no."' class='form-control' value='$row[number_to]' onkeyup='handleKeyUp(this)'>";

	$inputEmbro = '';
	$inputEmbro .="<input type='text' id='embro_".$no."' class='form-control' value='$row[embro]' onkeyup='handleKeyUp(this)'>";

	$inputPrint = '';
	$inputPrint .="<input type='text' id='print_".$no."' class='form-control' value='$row[printing]' onkeyup='handleKeyUp(this)'>";

	$inputHeat = '';
	$inputHeat .="<input type='text' id='heat_".$no."' class='form-control' value='$row[heat_transfer]' onkeyup='handleKeyUp(this)'>";

	$inputCutt = '';
	$inputCutt .="<input type='text' id='cutt_".$no."' class='form-control' value='$row[cutting_output]' onkeyup='handleKeyUp(this);cuttNumber(this)'>";

	$inputReject = '';
	$inputReject .="<input type='text' id='reject_".$no."' class='form-control' value='$row[reject]' onkeyup='handleKeyUp(this);rejectNumber(this)'>";

	$fieldOkeCutt = '';
	$fieldOkeCutt .="<input type='text' id='oke_".$no."' class='form-control' value='$row[ok_cutt]' readonly>";
	$no++;
	

	$data[] = array(
		"id"=>htmlspecialchars($row['id_cat']),
		"id_cat"=>htmlspecialchars($row['id_cat']),
		"id_det"=>htmlspecialchars($row['id_cod']),
		"goods_code"=>rawurldecode($row['fabric_code']),
		"itemdesc"=>rawurldecode(substr($row['fabric_desc'],0,30)),
		"grouping"=>htmlspecialchars($row['name_grouping']),
		"idg"=>htmlspecialchars($row['id_grouping']),
		"panel"=>htmlspecialchars($row['nama_panel']),
		"idp"=>htmlspecialchars($row['id_panel']),
		"inputNumbering1"=>rawurlencode($inputNumbering1),
		"numbering1"=>htmlspecialchars($row['number_from']),
		"inputNumbering2"=>rawurlencode($inputNumbering2),
		"numbering2"=>htmlspecialchars($row['number_to']),
		"inputEmbro"=>rawurlencode($inputEmbro),
		"embro"=>htmlspecialchars($row['embro']),
		"inputPrint"=>rawurlencode($inputPrint),
		"print"=>htmlspecialchars($row['printing']),
		"inputHeat"=>rawurlencode($inputHeat),
		"heat"=>htmlspecialchars($row['heat_transfer']),
		"size"=>rawurldecode($row['size']),
		"dt"=>$dt,
		"inputCutt"=>rawurlencode($inputCutt),
		"cutt"=>htmlspecialchars($row['cutting_output']),
		"inputReject"=>rawurlencode($inputReject),
		"reject"=>htmlspecialchars($row['reject']),
		"fieldOkeCutt"=>rawurlencode($fieldOkeCutt),
		"okeCutt"=>htmlspecialchars($row['ok_cutt']),
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