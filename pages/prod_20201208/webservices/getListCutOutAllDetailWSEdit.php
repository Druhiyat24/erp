<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw']; 
$row = $_POST['start'];

$cut_out = $_GET['id_cut_out'];
// print_r($id_cost);die();
 
$table = "(
	SELECT     
		cod.id_cut_out_detail as id_cod,
		cod.*,
		mg.name_grouping,
		mp.nama_panel 
	FROM cut_out_detail AS cod 
	LEFT JOIN mastergrouping AS mg ON mg.id_grouping=cod.id_grouping
	LEFT JOIN masterpanel AS mp ON mp.id=cod.id_panel
	WHERE cod.id_cut_out='{$cut_out}'
) AS X";

$colomn = "X.id_cod
	,X.id_cut_out
	,X.id_cat
	,X.id_grouping
	,X.id_panel
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
$sel = mysqli_query($conn_li,"select count(*) allcount from $table WHERE 1 ".$searchQuery );
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records


$empQuery = "select $colomn from $table WHERE 1 ".$searchQuery." ORDER BY X.id_grouping ASC,X.fabric_code ASC,X.size ASC" ;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
// echo $empQuery;

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
	$inputNumbering1 .="<input type='text' id='1numbering_".$no."' class='form-control' value='$row[number_from]' onchange='handleKeyUp(this)'>";
	$inputNumbering2 = '';
	$inputNumbering2 .="<input type='text' id='2numbering_".$no."' class='form-control' value='$row[number_to]' onchange='handleKeyUp(this)'>";

	$inputEmbro = '';
	$inputEmbro .="<input type='text' id='embro_".$no."' class='form-control' value='$row[embro]' onchange='handleKeyUp(this)'>";

	$inputPrint = '';
	$inputPrint .="<input type='text' id='print_".$no."' class='form-control' value='$row[printing]' onchange='handleKeyUp(this)'>";

	$inputHeat = '';
	$inputHeat .="<input type='text' id='heat_".$no."' class='form-control' value='$row[heat_transfer]' onchange='handleKeyUp(this)'>";

	$inputCutt = '';
	$inputCutt .="<input type='text' id='cutt_".$no."' class='form-control' value='$row[cutting_output]' onchange='handleKeyUp(this);cuttNumber(this)'>";

	$inputReject = '';
	$inputReject .="<input type='text' id='reject_".$no."' class='form-control' value='$row[reject]' onchange='handleKeyUp(this);rejectNumber(this)'>";

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