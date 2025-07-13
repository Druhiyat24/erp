<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];

$id_url = $_GET['id_url'];     
$id_cost = $_GET['id_cost'];
// print_r($id_cost);die(); 

$string = $_GET['id_cost'];
// $array = json_decode($string);
// $jlh = count($array);
// $trigger = $jlh - 1;
// $str = ""; 
// for($i=0;$i<$jlh;$i++){
// 	// print_r($array[0]);die();
// 	if($i == $trigger){
// 		$str .="'".$array[$i]."'";
// 	}else{
// 		// print_r($array[$i]);die();
// 		$str .="'".$array[$i]."',";
// 	}
// } 

if($id_url == '-1'){
	// $table = "(SELECT c.id, c.kpno, mi.goods_code AS fabric_code, mi.itemdesc AS fabric_desc, sd.size, sz.categori, sz.urut
	// 	FROM masteritem AS mi 
	// 	INNER JOIN act_costing_mat AS cm ON cm.id_item=mi.id_item
	// 	LEFT JOIN act_costing AS c ON c.id=cm.id_act_cost
	// 	LEFT JOIN so AS s ON s.id_cost=c.id
	// 	LEFT JOIN so_det AS sd ON sd.id_so=s.id
	// 	INNER JOIN cut_in AS ci ON ci.id_act_costing=c.id 
	// 	INNER JOIN mastersize AS sz ON sz.size=sd.size
	// 	WHERE mi.id_item IN ($str) GROUP BY sd.size ORDER BY sz.urut ASC) AS X";

	$table = "(SELECT 
					Y.id_cat
					,Y.fabric_code
					,Y.fabric_desc
					,Y.id_grouping
					,Y.id_panel
					,Y.name_grouping
					,Y.nama_panel
					,Y.id_sd
					,Y.size
				FROM (
					SELECT 
		  				coc.id_cat,
		  				coc.fabric_code,
						coc.fabric_desc,
						coc.id_grouping,
						coc.id_panel,
		  				mg.name_grouping,
		  				mp.nama_panel,
		  				ac.kpno,
		  				sd.id AS id_sd,
		  				sd.size
	  				FROM cut_out_category AS coc
	  				LEFT JOIN act_costing AS ac ON ac.id = coc.id_cost
	  				LEFT JOIN so AS s ON s.id_cost = ac.id
	  				LEFT JOIN so_det AS sd ON sd.id_so = s.id
	  				LEFT JOIN mastergrouping AS mg ON mg.id_grouping = coc.id_grouping
	  				LEFT JOIN masterpanel AS mp ON mp.id = coc.id_panel
	  				WHERE coc.id_cost='{$id_cost}'
					#GROUP BY sd.id 
				) AS Y GROUP BY Y.size,Y.id_cat 
				ORDER BY Y.size ASC
			) AS X";
		
	$colomn = "X.id_cat
			  ,X.fabric_code
			  ,X.fabric_desc
			  ,X.id_grouping
			  ,X.id_panel
			  ,X.name_grouping
			  ,X.nama_panel
			  ,X.size";
}
else{
	$table = "(SELECT cod.*,mg.name_grouping,mp.nama_panel FROM cut_out_detail AS cod 
		LEFT JOIN mastergrouping AS mg ON mg.id_grouping=cod.id_grouping
		LEFT JOIN masterpanel AS mp ON mp.id=cod.id_panel
		WHERE cod.id_cut_out='{$id_url}'
		AND cod.id_cost='{$id_cost}'  ORDER BY Y.size ASC) AS X";

	$colomn = "X.id_cut_out
		,X.fabric_code
		,X.fabric_desc
		,X.id_grouping
		,X.id_panel
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
}

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


$empQuery = "select $colomn from $table WHERE 1 ".$searchQuery;
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
		"goods_code"=>rawurldecode($row['fabric_code']),
		"itemdesc"=>rawurldecode(substr($row['fabric_desc'],0,30)),
		"grouping"=>htmlspecialchars($row['name_grouping']),
		"idg"=>htmlspecialchars($row['id_grouping']),
		"panel"=>htmlspecialchars($row['nama_panel']),
		"idp"=>htmlspecialchars($row['id_panel']),
		"inputNumbering1"=>rawurlencode($inputNumbering1),
		"numbering1"=>'',
		"inputNumbering2"=>rawurlencode($inputNumbering2),
		"numbering2"=>'',
		"inputEmbro"=>rawurlencode($inputEmbro),
		"embro"=>'',
		"inputPrint"=>rawurlencode($inputPrint),
		"print"=>'',
		"inputHeat"=>rawurlencode($inputHeat),
		"heat"=>'',
		"size"=>rawurldecode($row['size']),
		"dt"=>$dt,
		"inputCutt"=>rawurlencode($inputCutt),
		"cutt"=>'',
		"inputReject"=>rawurlencode($inputReject),
		"reject"=>'',
		"fieldOkeCutt"=>rawurlencode($fieldOkeCutt),
		"okeCutt"=>''
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