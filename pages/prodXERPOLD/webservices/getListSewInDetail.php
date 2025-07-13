<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];

$id_ws = $_GET['id_ws'];
$id_url = $_GET['id_url'];

// print_r($id_ws);die();

if($id_url == '-1' OR $id_url == 'undefined'){

	$cost = mysqli_query($conn_li,"SELECT max(id_sew_in) AS id FROM prod_sew_in WHERE id_cost = '{$id_ws}'");
	$ws = mysqli_fetch_assoc($cost);
	$id_cost = $ws['id'];
	// print_r($id_cost);die();
	if($id_cost == 'null' OR $id_cost == ''){

		$table = "(

			SELECT 
				dco.id_dc_out,
				dco.id_cost AS id_ws,
				ac.buyer,
				dod.id_dc_out_detail,
				dod.id_cost,
				dod.id_so_det,
				dod.dest,
				dod.color,
				dod.size,
				dod.unit,
				dod.qty_output,
				'' AS balance,
				'' AS qty_input
			FROM prod_dc_out AS dco
			INNER JOIN (
				SELECT 
					dod.id_dc_out_detail,
					dod.id_dc_out,
					dod.id_cost,
					dod.id_so_det,
					dod.dest,
					dod.color,
					dod.size,
					dod.unit,
					SUM(dod.qty_output) qty_output
				FROM prod_dc_out_detail AS dod
				GROUP BY dod.id_so_det
			) AS dod ON dod.id_dc_out = dco.id_dc_out
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
			) AS ac ON ac.id = dco.id_cost
			WHERE dco.id_cost = '{$id_ws}'
			ORDER BY dod.id_dc_out_detail ASC
	
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
			swi.id_sew_in AS id_dc_out,
			swi.id_cost AS id_ws,
			wid.id_sew_in_detail AS id_dc_out_detail,
			'' AS id_so_det,
			ac.buyer,
			wid.dest,
			wid.color,
			wid.size,
			wid.unit,
			wid.qty_dc_output AS qty_output,
			wid.balance,
			wid.qty_input
		FROM prod_sew_in AS swi
		INNER JOIN (
			SELECT 
				wid.id_sew_in_detail,
				wid.id_sew_in,
				wid.dest,
				wid.color,
				wid.size,
				wid.unit,
				wid.qty_dc_output,
				wid.balance,
				wid.qty_input
			FROM prod_sew_in_detail AS wid
		) AS wid ON wid.id_sew_in = swi.id_sew_in
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
		WHERE swi.id_sew_in = '{$id_url}'
	
	) AS X";
}
		
	$colomn = " X.id_dc_out,
				X.id_ws,
				X.id_dc_out_detail,
				X.id_so_det,
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
$columnName = "id_dc_out_detail"; // Column name 
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
			$balance = $row['qty_output'];
		}
		else{
			$balance = $row['balance'];
		}
	}
	else{
		$qty_in = $row['qty_input'];
		$balance = $row['balance'];
	}

	$inputQTY = '';
	$inputQTY .= "<input type='text' id='qty_".$num."' class='form-control' value='$qty_in' onchange='handlekeyup(this)'>";
	$num++;
	

	$data[] = array(
		"no"=>$no,
		"id_dc_in"=>htmlspecialchars($row['id_dc_in']),
		"id_cost"=>rawurldecode($row['id_cost']),
		"id_dc_out_detail"=>htmlspecialchars($row['id_dc_out_detail']),
		"id_so_det"=>htmlspecialchars($row['id_so_det']),
		"dest"=>htmlspecialchars($row['dest']),
		"color"=>htmlspecialchars($row['color']),
		"size"=>htmlspecialchars($row['size']),
		"unit"=>htmlspecialchars($row['unit']),
		"buyer"=>htmlspecialchars($row['buyer']),
		"bal"=>htmlspecialchars(number_format($balance, 0, '', ',')),
		"bal_val"=>htmlspecialchars($balance),
		"qty_output"=>htmlspecialchars(number_format($row['qty_output'], 0, '', ',')),
		"qty_output_val"=>htmlspecialchars($row['qty_output']),
		"qty_input"=>rawurldecode($inputQTY ),
		"qty_val"=>(ISSET($row['qty_input']) ? $row['qty_input']  : "0"  )
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