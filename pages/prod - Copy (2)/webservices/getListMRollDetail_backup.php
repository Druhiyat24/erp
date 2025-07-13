<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];

$id_color = $_GET['color'];
$id_item = $_GET['item'];
// $id_url = $_GET['id_url'];

// print_r($id_item);die();
$sql = "SELECT 
	mr.fabric_color AS color, 
	mr.id_item AS item
	FROM prod_m_roll AS mr
	WHERE mr.fabric_color = '{$id_color}' 
	AND mr.id_item = '{$id_item}'
";
// echo $sql;die();
$stmt = mysql_query($sql);
$row = mysql_fetch_array($stmt);

if($row['color'] == $id_color && $row['item'] == $id_item){

	$table = "(

		SELECT 
			mr.id_m_roll,
			mr.id_cost,
			mr.id_item,
			mr.id_number,
			srn.id_panel,
			srn.nama_panel,
			mr.fabric_code,
			mr.fabric_name,
			mr.fabric_color,
			mr.lot,
			mr.roll_no,
			mr.qty_sticker,
			mr.fabric_used,
			mr.qty_cutting,
			mr.cons_ws,
			mr.cons_m,
			mr.cons_act,
			mr.cons_balance,
			mr.percentage,
			mr.binding,
			mr.actual_balance,
			mr.actual_total,
			mr.short_roll,
			mr.spread_sheet,
			mr.ratio,
			mr.qty_pcs,
			srn.p_markers,
			srn.efficiency,
			mr.fabric_total_act,
			mr.majun,
			mr.majun_kg,
			mr.ampar,
			mr.total,
			mr.used_total,
			mr.l_fabric 
		FROM prod_m_roll AS mr
		INNER JOIN (
			SELECT 
				srn.id_number,
				srn.id_mark_entry,
				srn.id_group_det,
				srn.id_panel,
				srn.efficiency,
				mp.nama_panel,
				srn.length_marker,
				CONCAT(med.unit_yds, ' YDS ', med.unit_inch, ' Inch') AS p_markers
			FROM prod_spread_report_number AS srn
			INNER JOIN (
				SELECT 
					med.id_mark_detail,
					med.id_mark,
					med.id_group_det,
					med.id_group,
					meg.unit_yds,
					meg.unit_inch
				FROM prod_mark_entry_detail AS med
				INNER JOIN prod_mark_entry_group AS meg ON meg.id_mark_entry = med.id_mark AND meg.id_group = med.id_group
				WHERE med.id_item = '{$id_item}'
				GROUP BY med.id_group
			) AS med ON med.id_mark = srn.id_mark_entry AND med.id_group_det = srn.id_group_det
			INNER JOIN masterpanel AS mp ON mp.id = srn.id_panel
		) AS srn ON srn.id_number = mr.id_number
		WHERE mr.fabric_color = '{$id_color}' AND mr.id_item = '{$id_item}'

	) AS X";
	
}
else{
	$table = "(
		
		SELECT 
			srd.id_spread_detail AS id_m_roll,
			srn.id_number,
			srn.id_internal,
			srn.id_so,
			srn.id_cost,
			srn.id_mark_entry,
			srn.id_group_det,
			srd.id_item,
			srn.color,
			srn.id_panel,
			mp.nama_panel,
			vpb.fabric_code,
			vpb.fabric_name,
			vpb.fabric_color,
			srd.lot_no AS lot,
			srd.roll_no,
			srd.roll_qty AS qty_sticker,
			'' AS fabric_used,
			'' AS qty_cutting,
			'' AS cons_ws,
			'' AS cons_m,
			'' AS cons_act,
			'' AS cons_balance,
			'' AS percentage,
			'' AS binding,
			'' AS actual_balance,
			'' AS actual_total,
			'' AS short_roll,
			'' AS spread_sheet,
			med.ratio,
			'' AS qty_pcs,
			CONCAT(med.unit_yds, ' YDS ', med.unit_inch, ' Inch') AS p_markers,
			srn.efficiency,
			'' AS fabric_total_act,
			'' AS majun,
			'' AS majun_kg,
			'' AS ampar,
			'' AS total,
			'' AS used_total,
			'' AS l_fabric
		FROM prod_spread_report_number AS srn
		INNER JOIN (
			SELECT 
				srd.id AS id_spread_detail,
				srd.id_number,
				srd.id_item,
				srd.id_roll_det,
				br.roll_no,
				br.lot_no,
				br.roll_qty
			FROM prod_spread_report_detail AS srd
			INNER JOIN (
				SELECT 
					br.id,
					br.roll_no,
					br.lot_no,
					br.roll_qty
				FROM bpb_roll AS br
			) AS br ON br.id = srd.id_roll_det
		) AS srd ON srd.id_number = srn.id_number
		INNER JOIN (
			SELECT 
				vpb.id_cost,
				vpb.goods_code AS fabric_code,
				vpb.fabric_desc AS fabric_name,
				vpb.color AS fabric_color,
				vpb.id_item_ms_item
			FROM view_portal_bom AS vpb
			WHERE vpb.id_item_ms_item = '{$id_item}'
		) AS vpb ON vpb.id_cost = srn.id_cost
		INNER JOIN (
			SELECT 
				med.id_mark_detail,
				med.id_mark,
				SUM(med.ratio) AS ratio,
				med.id_group_det,
				med.id_group,
				med.color,
				med.id_panel,
				med.id_item,
				meg.unit_yds,
				meg.unit_inch
			FROM prod_mark_entry_detail AS med
			INNER JOIN prod_mark_entry_group AS meg ON meg.id_mark_entry = med.id_mark AND meg.id_group = med.id_group
			WHERE med.color = '{$id_color}' AND med.id_item = '{$id_item}'
			GROUP BY med.id_mark,med.id_item,med.id_panel,med.color,med.id_group_det
		) AS med ON med.id_mark = srn.id_mark_entry AND med.id_panel = srn.id_panel AND med.id_group_det = srn.id_group_det
		INNER JOIN masterpanel AS mp ON mp.id = srn.id_panel
		WHERE srn.color = '{$id_color}' AND srd.id_item = '{$id_item}'
		GROUP BY srd.id_spread_detail
	
 	) AS X";
}
		
$colomn = "
	X.id_m_roll,
	X.id_cost,
	X.id_item,
	X.id_number,
	X.id_panel,
	X.nama_panel,
	X.fabric_code,
	X.fabric_name,
	X.fabric_color,
	X.lot,
	X.roll_no,
	X.qty_sticker,
	X.fabric_used,
	X.qty_cutting,
	X.cons_ws,
	X.cons_m,
	X.cons_act,
	X.cons_balance,
	X.percentage,
	X.binding,
	X.actual_balance,
	X.actual_total,
	X.short_roll,
	X.spread_sheet,
	X.ratio,
	X.qty_pcs,
	X.p_markers,
	X.efficiency,
	X.fabric_total_act,
	X.majun,
	X.majun_kg,
	X.ampar,
	X.total,
	X.used_total,
	X.l_fabric
";

 //print_r($id_panel);die();

$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "X.id_m_roll ASC"; // Column name 
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value 

### Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (
   		X.fabric_code			LIKE'%".$searchValue."%'
		OR X.fabric_name		LIKE'%".$searchValue."%'
		OR X.fabric_color		LIKE'%".$searchValue."%'
		OR X.lot				LIKE'%".$searchValue."%'
		OR X.roll_no			LIKE'%".$searchValue."%'
		OR X.qty_sticker		LIKE'%".$searchValue."%'
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

$no = 1;
$num = 0;
while ($row = mysqli_fetch_assoc($empRecords)) {

	$inputQS = '';
	$inputQS .= "<input type='text' id='qs_".$num."' class='form-control forms' value='".$row['qty_sticker']."' onchange='handlekeyup(this)' onmouseover='mouseOver(this)' style='min-width: 100px' readonly>";
	
	if($row['fabric_used'] == ''){
		$fUse = $row['qty_sticker'];
	}
	else{
		$fUse = $row['fabric_used'];
	}
	$inputFUse = '';
	$inputFUse .= "<input type='text' id='fUse_".$num."' class='form-control forms' value='$fUse' onchange='handlekeyup(this)' onmouseover='mouseOver(this)' style='min-width: 100px' readonly>";


	if($row['qty_cutting'] == ''){
		$qtyCut = "0";
	}
	else{
		$qtyCut = $row['qty_cutting'];
	}
	$inputQtyCut = '';
	$inputQtyCut .= "<input type='text' id='qtyCut_".$num."' class='form-control forms' value='$qtyCut' onchange='handlekeyup(this);qtyCutSum(this)' onmouseover='mouseOver(this)' style='min-width: 100px'>";


	if($row['cons_ws'] == ''){
		$consWs = "0.000";
	}
	else{
		$consWs = $row['cons_ws'];
	}
	$inputConsWs = '';
	$inputConsWs .= "<input type='text' id='consWs_".$num."' class='form-control forms' value='$consWs' onchange='handlekeyup(this);consWsSum(this);' onmouseover='mouseOver(this)' style='min-width: 100px'>";


	if($row['cons_m'] == ''){
		$consM = "0.000";
	}
	else{
		$consM = $row['cons_m'];
	}
	$inputConsM = '';
	$inputConsM .= "<input type='text' id='consM_".$num."' class='form-control forms' value='$consM' onchange='handlekeyup(this)' onmouseover='mouseOver(this)' style='min-width: 100px'>";


	if($row['cons_act'] == ''){
		$consAct = "0.000";
	}
	else{
		$consAct = $row['cons_act'];
	}
	$inputConsAct = '';
	$inputConsAct .= "<input type='text' id='consAct_".$num."' class='form-control forms' value='$consAct' onchange='handlekeyup(this)' onmouseover='mouseOver(this)' style='min-width: 100px' readonly>";


	if($row['cons_balance'] == ''){
		$consBal = "0.000";
	}
	else{
		$consBal = $row['cons_balance'];
	}
	$inputConsBal = '';
	$inputConsBal .= "<input type='text' id='consBal_".$num."' class='form-control forms' value='$consBal' onchange='handlekeyup(this)' onmouseover='mouseOver(this)' style='min-width: 100px' readonly>";


	if($row['percentage'] == ''){
		$percent = "";
	}
	else{
		$percent = $row['percentage'];
	}
	$inputPercent = '';
	$inputPercent .= "<input type='text' id='percent_".$num."' class='form-control forms' value='$percent' onchange='handlekeyup(this)' onmouseover='mouseOver(this)' style='min-width: 100px' readonly>";


	if($row['binding'] == ''){
		$bind = "";
	}
	else{
		$bind = $row['binding'];
	}
	$inputBind = '';
	$inputBind .= "<input type='text' id='bind_".$num."' class='form-control forms' value='$bind' onchange='handlekeyup(this);bindSum(this)' onmouseover='mouseOver(this)' style='min-width: 100px'>";


	if($row['actual_balance'] == ''){
		$actBal = "";
	}
	else{
		$actBal = $row['actual_balance'];
	}
	$inputActBal = '';
	$inputActBal .= "<input type='text' id='actBal_".$num."' class='form-control forms' value='$actBal' onchange='handlekeyup(this);actBalSum(this)' onmouseover='mouseOver(this)' style='min-width: 100px'>";


	if($row['actual_total'] == ''){
		$actTot = "";
	}
	else{
		$actTot = $row['actual_total'];
	}
	$inputActTot = '';
	$inputActTot .= "<input type='text' id='actTot_".$num."' class='form-control forms' value='$actTot' onchange='handlekeyup(this);' onmouseover='mouseOver(this)' style='min-width: 100px' readonly>";


	if($row['short_roll'] == ''){
		$sht = "";
	}
	else{
		$sht = $row['short_roll'];
	}
	$inputShortRoll = '';
	$inputShortRoll .= "<input type='text' id='sht_".$num."' class='form-control forms' value='$sht' onchange='handlekeyup(this)' onmouseover='mouseOver(this)' style='min-width: 100px' readonly>";


	if($row['spread_sheet'] == ''){
		$spd = "";
	}
	else{
		$spd = $row['spread_sheet'];
	}
	$inputSpreadSheet = '';
	$inputSpreadSheet .= "<input type='text' id='spd_".$num."' class='form-control forms' value='$spd' onchange='handlekeyup(this);spdSum(this);' onmouseover='mouseOver(this)' style='min-width: 100px'>";

	$inputRatio = '';
	$inputRatio .= "<input type='text' id='ratio_".$num."' class='form-control forms' value='".$row['ratio']."' onchange='handlekeyup(this)' onmouseover='mouseOver(this)' style='min-width: 100px' readonly>";

	if($row['qty_pcs'] == ''){
		$qtyPcs = "";
	}
	else{
		$qtyPcs = $row['qty_pcs'];
	}
	$inputQtyPcs = '';
	$inputQtyPcs .= "<input type='text' id='qtyPcs_".$num."' class='form-control forms' value='$qtyPcs' onchange='handlekeyup(this)' onmouseover='mouseOver(this)' style='min-width: 100px' readonly>";


	$inputEfficiency = '';
	$inputEfficiency .= "<input type='text' id='efi_".$num."' class='form-control forms' value='".$row['efficiency']."' onchange='handlekeyup(this)' onmouseover='mouseOver(this)' style='min-width: 100px' readonly>";


	if($row['fabric_total_act'] == ''){
		$fTotAct = "";
	}
	else{
		$fTotAct = $row['fabric_total_act'];
	}
	$inputFTotAct = '';
	$inputFTotAct .= "<input type='text' id='fTotAct_".$num."' class='form-control forms' value='$fTotAct' onchange='handlekeyup(this)' onmouseover='mouseOver(this)' style='min-width: 100px' readonly>";


	if($row['majun'] == ''){
		$majun = 100 - $row['efficiency'];
	}
	else{
		$majun = 100 - $row['efficiency'];
	}
	$inputMajun = '';
	$inputMajun .= "<input type='text' id='majun_".$num."' class='form-control forms' value='$majun' onchange='handlekeyup(this)' onmouseover='mouseOver(this)' style='min-width: 100px' readonly>";


	if($row['majun_kg'] == ''){
		$majunKg = "";
	}
	else{
		$majunKg = $row['majun_kg'];
	}
	$inputMajunKg = '';
	$inputMajunKg .= "<input type='text' id='majunKg_".$num."' class='form-control forms' value='$majunKg' onchange='handlekeyup(this)' onmouseover='mouseOver(this)' style='min-width: 100px' readonly>";


	if($row['ampar'] == ''){
		$ampar = "";
	}
	else{
		$ampar = $row['ampar'];
	}
	$inputAmpar = '';
	$inputAmpar .= "<input type='text' id='ampar_".$num."' class='form-control forms' value='$ampar' onchange='handlekeyup(this);amparSum(this);' onmouseover='mouseOver(this)' style='min-width: 100px'>";
	

	if($row['total'] == ''){
		$total = "";
	}
	else{
		$total = $row['total'];
	}
	$inputTotal = '';
	$inputTotal .= "<input type='text' id='total_".$num."' class='form-control forms' value='$total' onchange='handlekeyup(this)' onmouseover='mouseOver(this)' style='min-width: 100px' readonly>";


	if($row['used_total'] == ''){
		$useTotal = "";
	}
	else{
		$useTotal = $row['used_total'];
	}
	$inputUseTotal = '';
	$inputUseTotal .= "<input type='text' id='useTotal_".$num."' class='form-control forms' value='$useTotal' onchange='handlekeyup(this)' onmouseover='mouseOver(this)' style='min-width: 100px' readonly>";


	if($row['l_fabric'] == ''){
		$lFab = "";
	}
	else{
		$lFab = $row['l_fabric'];
	}
	$inputLFabric = '';
	$inputLFabric .= "<input type='text' id='lFab_".$num."' class='form-control forms' value='$lFab' onchange='handlekeyup(this);' onmouseover='mouseOver(this)' style='min-width: 100px'>";

	$num++;
	
	$data[] = array(
		"no"=>$no,
		"id_m_roll"=>htmlspecialchars($row['id_m_roll']),
		"id_cost"=>htmlspecialchars($row['id_cost']),
		"id_number"=>htmlspecialchars($row['id_number']),
		"panel"=>htmlspecialchars($row['nama_panel']),
		"fabric_code"=>rawurldecode($row['fabric_code']),
		"fabric_name"=>rawurldecode($row['fabric_name']),
		"fabric_color"=>htmlspecialchars($row['fabric_color']),
		"lot"=>htmlspecialchars($row['lot']),
		"roll"=>htmlspecialchars($row['roll_no']),
		"qty_sticker"=>rawurldecode($inputQS),
		"qty_sticker_val"=>(ISSET($row['qty_sticker']) ? $row['qty_sticker']  : ""  ),
		"id_m_roll_detail"=>htmlspecialchars($row['id_number']),
		"fabric_use"=>rawurldecode($inputFUse),
		"fabric_use_val"=>(ISSET($row['fabric_used']) ? $row['fabric_used']  : ""  ),
		"qty_cut"=>rawurldecode($inputQtyCut),
		"qty_cut_val"=>(ISSET($row['qty_cutting']) ? $row['qty_cutting']  : ""  ),
		"cons_ws"=>rawurldecode($inputConsWs),
		"cons_ws_val"=>(ISSET($row['cons_ws']) ? $row['cons_ws']  : ""  ),
		"cons_m"=>rawurldecode($inputConsM),
		"cons_m_val"=>(ISSET($row['cons_m']) ? $row['cons_m']  : ""  ),
		"cons_act"=>rawurldecode($inputConsAct),
		"cons_act_val"=>(ISSET($row['cons_act']) ? $row['cons_act']  : ""  ),
		"cons_bal"=>rawurldecode($inputConsBal),
		"cons_bal_val"=>(ISSET($row['cons_balance']) ? $row['cons_balance']  : ""  ),
		"percent"=>rawurldecode($inputPercent),
		"percent_val"=>(ISSET($row['percentage']) ? $row['percentage']  : ""  ),
		"bind"=>rawurldecode($inputBind),
		"bind_val"=>(ISSET($row['binding']) ? $row['binding']  : ""  ),
		"act_bal"=>rawurldecode($inputActBal),
		"act_bal_val"=>(ISSET($row['actual_balance']) ? $row['actual_balance']  : ""  ),
		"act_tot"=>rawurldecode($inputActTot),
		"act_tot_val"=>(ISSET($row['actual_total']) ? $row['actual_total']  : ""  ),
		"short_roll"=>rawurldecode($inputShortRoll),
		"short_roll_val"=>(ISSET($row['short_roll']) ? $row['short_roll']  : ""  ),
		"spread_sheet"=>rawurldecode($inputSpreadSheet),
		"spread_sheet_val"=>(ISSET($row['spread_sheet']) ? $row['spread_sheet']  : ""  ),
		"ratio"=>rawurldecode($inputRatio),
		"ratio_val"=>(ISSET($row['ratio']) ? $row['ratio']  : ""  ),
		"qty_pcs"=>rawurldecode($inputQtyPcs),
		"qty_pcs_val"=>(ISSET($row['qty_pcs']) ? $row['qty_pcs']  : ""  ),
		"p_markers"=>htmlspecialchars($row['p_markers']),
		"efficiency"=>rawurldecode($inputEfficiency),
		"efficiency_val"=>(ISSET($row['efficiency']) ? $row['efficiency']  : ""  ),
		"fabric_tot_act"=>rawurldecode($inputFTotAct),
		"fabric_tot_act_val"=>(ISSET($row['fabric_total_act']) ? $row['fabric_total_act']  : ""  ),
		"majun"=>rawurldecode($inputMajun),
		"majun_val"=>(ISSET($row['majun']) ? $row['majun']  : ""  ),
		"majun_kg"=>rawurldecode($inputMajunKg),
		"majun_kg_val"=>(ISSET($row['majun_kg']) ? $row['majun_kg']  : ""  ),
		"ampar"=>rawurldecode($inputAmpar),
		"ampar_val"=>(ISSET($row['ampar']) ? $row['ampar']  : ""  ),
		"total"=>rawurldecode($inputTotal),
		"total_val"=>(ISSET($row['total']) ? $row['total']  : ""  ),
		"use_total"=>rawurldecode($inputUseTotal),
		"use_total_val"=>(ISSET($row['used_total']) ? $row['used_total']  : ""  ),
		"l_fabric"=>rawurldecode($inputLFabric),
		"l_fabric_val"=>(ISSET($row['l_fabric']) ? $row['l_fabric']  : ""  )
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