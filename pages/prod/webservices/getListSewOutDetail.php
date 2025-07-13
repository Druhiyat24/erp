<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];

$id = explode("_",$_GET['id_ws']);
$id_ws = $id[0];
$sew_in = $id[1];

$id_url = $_GET['id_url'];

// print_r($id_ws);die();

if($id_url == '-1' OR $id_url == 'undefined'){

	$cost = mysqli_query($conn_li,"SELECT max(id_sew_out) AS id FROM prod_sew_out WHERE id_cost = '{$id_ws}'");
	$ws = mysqli_fetch_assoc($cost);
	$id_cost = $ws['id'];
	// print_r($id_cost);die();
	if($id_cost == 'null' OR $id_cost == ''){

		$table = "(

			SELECT 
				swi.id_sew_in,
				swi.id_cost,
				ac.buyer,
				sid.id_sew_in_detail,
				sid.dest,
				sid.color,
				sid.size,
				sid.unit,
				sid.qty_input,
				'' AS balance,
				'' AS qty_output
			FROM prod_sew_in AS swi
			INNER JOIN (
				SELECT 
					sid.id_sew_in_detail,
					sid.id_sew_in,
					sid.dest,
					sid.color,
					sid.size,
					sid.unit,
					sid.qty_input
				FROM prod_sew_in_detail AS sid
			) AS sid ON sid.id_sew_in = swi.id_sew_in
			INNER JOIN (
				SELECT 
					ac.id,
					ac.id_buyer,
					buy.buyer
				FROM act_costing AS ac
				INNER JOIN (
					SELECT 
						buy.Id_Supplier AS id_buyer,
						buy.Supplier AS buyer,
						buy.tipe_sup
					FROM mastersupplier AS buy
					WHERE buy.tipe_sup = 'C'
				) AS buy ON buy.id_buyer = ac.id_buyer
			) AS ac ON ac.id = swi.id_cost
			WHERE swi.id_cost = '{$id_ws}'
			ORDER BY sid.id_sew_in_detail ASC
	
		) AS X";

	}
	else{

		$table = "(

			SELECT 
				dco.id_dc_out AS id_dc_in,
				dco.id_cost,
				ac.buyer,
				ac.ws,
				ac.styleno,
				dod.id_dc_out_detail,
				dod.id_so_det,
				dod.dest,
				dod.color,
				dod.size,
				dod.urut,
				dod.unit,
				dod.qty_so,
				dod.balance,
				dod.qty_output
			FROM prod_dc_out AS dco
			INNER JOIN (
				SELECT 
					dod.id_dc_out_detail,
					dod.id_dc_out,
					dod.id_so_det,
					dod.dest,
					dod.color,
					dod.size,
					dod.unit,
					dod.qty_so,
					dod.balance,
					dod.qty_output,
					msz.urut
				FROM prod_dc_out_detail AS dod
				LEFT JOIN (
					SELECT 
						msz.urut,
						msz.size
					FROM mastersize AS msz
				) AS msz ON msz.size = dod.size
			) AS dod ON dod.id_dc_out = dco.id_dc_out
			INNER JOIN (
				SELECT 
					ac.id,
					ac.id_buyer,
					ac.kpno AS ws,
					ac.styleno,
					buy.buyer
				FROM act_costing AS ac
				INNER JOIN (
					SELECT 
						buy.Id_Supplier AS id_buyer,
						buy.Supplier AS buyer,
						buy.tipe_sup
					FROM mastersupplier AS buy
					WHERE buy.tipe_sup = 'C'
				) AS buy ON buy.id_buyer = ac.id_buyer
			) AS ac ON ac.id = dco.id_cost
			WHERE dco.id_dc_out = '{$id_cost}' AND dod.balance != '0'

		) AS X";

	}

	
}
else{
	$table = "(
		
		SELECT 
			swo.id_sew_out AS id_sew_in,
			swo.id_cost,
			ac.buyer,
			sod.id_sew_out_detail AS id_sew_in_detail,
			sod.dest,
			sod.color,
			sod.size,
			sod.unit,
			sod.qty_sew_in AS qty_input,
			sod.balance,
			sod.qty_sew_out AS qty_output
		FROM prod_sew_out AS swo
		INNER JOIN (
			SELECT 
				sod.id_sew_out,
				sod.id_sew_out_detail,
				sod.dest,
				sod.color,
				sod.size,
				sod.unit,
				sod.qty_sew_in,
				sod.balance,
				sod.qty_sew_out
			FROM prod_sew_out_detail AS sod
		) AS sod ON sod.id_sew_out = swo.id_sew_out
		INNER JOIN (
			SELECT 
				ac.id,
				ac.id_buyer,
				buy.buyer
			FROM act_costing AS ac
			INNER JOIN (
				SELECT 
					buy.Id_Supplier AS id_buyer,
					buy.Supplier AS buyer,
					buy.tipe_sup
				FROM mastersupplier AS buy
				WHERE buy.tipe_sup = 'C'
			) AS buy ON buy.id_buyer = ac.id_buyer
		) AS ac ON ac.id = swo.id_cost
		WHERE swo.id_sew_out = '{$id_url}'
	
	) AS X";
}
		
	$colomn = " X.id_sew_in,
				X.id_cost,
				X.id_sew_in_detail,
				X.dest,
				X.unit,
				X.color,
				X.size,
				X.qty_output,
				X.qty_input,
				X.buyer,
				X.balance";

 //print_r($id_panel);die();

$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "id_sew_in_detail"; // Column name 
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value 

### Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (
   		X.buyer				LIKE'%".$searchValue."%'
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


$empQuery = "select $colomn from $table WHERE 1 ORDER BY $columnName ASC ".$searchQuery;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
// echo $empQuery;die();

$no = 1;
$num = 0;
while ($row = mysqli_fetch_assoc($empRecords)) {

	if($id_url == '-1' OR $id_url == 'undefined'){
		$qty_out = "";
		// $balance = $row['qty_output'];
		if($id_cost == 'null' OR $id_cost == ''){
			$balance = $row['qty_input'];
		}
		else{
			$balance = $row['balance'];
		}
	}
	else{
		$qty_out = $row['qty_output'];
		$balance = $row['balance'];
	}

	$inputQTY = '';
	$inputQTY .= "<input type='text' id='qty_".$num."' class='form-control' value='$qty_out' onchange='handlekeyup(this)'>";
	$num++;
	

	$data[] = array(
		"no"=>$no,
		"id_sew_in"=>htmlspecialchars($row['id_sew_in']),
		"id_cost"=>rawurldecode($row['id_cost']),
		"id_sew_in_detail"=>htmlspecialchars($row['id_sew_in_detail']),
		"dest"=>htmlspecialchars($row['dest']),
		"color"=>htmlspecialchars($row['color']),
		"size"=>htmlspecialchars($row['size']),
		"unit"=>htmlspecialchars($row['unit']),
		"buyer"=>htmlspecialchars($row['buyer']),
		"bal"=>htmlspecialchars(number_format($balance, 0, '', ',')),
		"bal_val"=>htmlspecialchars($balance),
		"qty_input"=>htmlspecialchars(number_format($row['qty_input'], 0, '', ',')),
		"qty_input_val"=>htmlspecialchars($row['qty_input']),
		"qty_output"=>rawurldecode($inputQTY ),
		"qty_val"=>(ISSET($row['qty_output']) ? $row['qty_output']  : "0"  )
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