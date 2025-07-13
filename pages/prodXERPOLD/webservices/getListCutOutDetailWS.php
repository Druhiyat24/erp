<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
 //print_r($_GET);die();

$data = explode("_", $_GET['data']);
$id_cost = $data[0];
$color = $data[1];

$id_url = $_GET['id'];
// print_r($id_url);die();

$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "id_item"; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

$table = "(

	SELECT 
		vpb.id_item_ms_item AS id_item,
		vpb.goods_code AS code,
		vpb.fabric_desc AS description,
		vpb.color,
		vpb.id_cost
	FROM view_portal_bom AS vpb
	INNER JOIN (
		SELECT 
			mr.id_cost,
			mr.fabric_color,
			mr.id_item
		FROM prod_m_roll AS mr
		WHERE mr.id_cost = '{$id_cost}'
		AND mr.fabric_color = '{$color}'
		GROUP BY mr.id_item
	) AS mr ON mr.id_cost = vpb.id_cost AND mr.fabric_color = vpb.color AND mr.id_item = vpb.id_item_ms_item
	WHERE vpb.id_cost = '{$id_cost}' AND vpb.color = '{$color}'

) AS X";

### Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (
   		X.fabric_code			LIKE'%".$searchValue."%'
		OR X.fabric_desc		LIKE'%".$searchValue."%'
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
$colomn = "
	X.id_item,
	X.code,
	X.description,
	X.color,
	X.id_cost
";

$empQuery = "SELECT $colomn FROM $table WHERE 1 ".$searchQuery;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
// echo $empQuery;die();
$no = 1;


if($id_url == "-1"){
	$addQuery = "";
}
else{
	$cekGrup = mysqli_query($conn_li,
		"SELECT 
			coc.id_cat,
			coc.id_cost,
			coc.color,
			coc.id_item,
			cod.id_cut_out
		FROM prod_cut_out_category AS coc
		INNER JOIN (
			SELECT 
				cod.id_cat,
				cod.id_cut_out
			FROM prod_cut_out_detail AS cod
		) AS cod ON cod.id_cat = coc.id_cat
		WHERE coc.id_cost = '{$id_cost}'
		AND coc.color = '{$color}'
		AND cod.id_cut_out = '{$id_url}'
		GROUP BY id_cat
	");
	$addQuery = "
		WHERE mg.id_grouping NOT IN (
			SELECT 
				coc.id_grouping 
			FROM prod_cut_out_category AS coc
			INNER JOIN prod_cut_out_detail AS cod ON cod.id_cat = coc.id_cat
			WHERE coc.id_cost = '{$id_cost}' AND coc.color = '{$color}' AND cod.id_cut_out = '{$id_url}'
			GROUP BY coc.id_cat
		)
	";
}


while ($row = mysqli_fetch_assoc($empRecords)) {
	
	$rowGrup = mysqli_fetch_assoc($cekGrup);
	if($rowGrup['id_item'] == $row['id_item']){
		$disable = "disabled";
	}
	else{
		$disable = "";
	}


	$inputGroup = "";
	$inputGroup .="<select id='group_".$row['id_item']."' class='form-control select2 grouping' $disable tabindex='-1' aria-hidden='true' style='width: 100% !important;padding-bottom: 0px;padding-top: 0px;'>
					<option value='0' disabled selected>--Choose Shell--</option>";
						$sqlgrup = mysqli_query($conn_li,"SELECT 
								mg.id_grouping,
								mg.name_grouping 
							FROM mastergrouping AS mg
							$addQuery
						");
						while ($grup = mysqli_fetch_assoc($sqlgrup)) {
	$inputGroup  .=" <option value='".$grup['id_grouping']."'>".$grup['name_grouping']."</option>";	
  					}
	$inputGroup	.="</select>";


	$button = "";
	$button .="<input type='button' id='cutout_".$row['id_item']."' $disable onclick='getDataDetail(".'"'.$row['id_cost'].'","'.$row['color'].'","'.$row['id_item'].'"'.")' class='btn btn-primary addrow' value='Add'>
				<input type='hidden' 
					data-id_item='".rawurlencode($row['id_item'])."'
					data-id_cost='".rawurlencode($row['id_cost'])."'
					data-code='".rawurlencode($row['code'])."'
					data-description='".rawurlencode($row['description'])."'
					data-color='".rawurlencode($row['color'])."'
					data-is_new='".rawurlencode($row['is_new'])."'
					id='tmp_html_".$row['id_item']."' 
				value=''>
	";
	// $button .="<input type='button' id='cutout_".$row['id_item']."' onclick='Insert(".'"'.$row['id_item'].'"'.")' class='btn btn-primary addrow' value='Add'>";

	$data[] = array(
		"no"			=> $no,
		"is_new"		=> "0",
		"id_item"		=> htmlspecialchars($row['id_item']),
		"id_cost"		=> htmlspecialchars($row['id_cost']),
		"code"			=> rawurldecode($row['code']),
		"description"	=> rawurldecode($row['description']),
		"color"			=> htmlspecialchars($row['color']),
		"group"			=> rawurlencode($inputGroup),
		"button"		=> rawurlencode($button)
	);
	$no++;

}

## Response
$response = array(
  "draw" 					=> intval($draw),
  "iTotalRecords"			=> $totalRecordwithFilter,
  "iTotalDisplayRecords"	=> $totalRecords,
  "aaData"					=> $data
);
echo json_encode($response);

?>