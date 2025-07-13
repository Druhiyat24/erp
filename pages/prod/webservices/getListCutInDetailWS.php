<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_GET['draw'];
$row = $_GET['start'];
//print_r($_GET);
$id_cost = $_GET['id_cost'];
$id_url = $_GET['id_url'];
$id_jo = $_GET['id_jo'];
$req = $_GET['reqno'];
// print_r($id_cost);die();

if($id_url == '-1'){
	$table = "(
		
		SELECT 
			REQ.loc_qty
			,REQ.bppbno
			,REQ.qty_request
			,REQ.out_qty AS qty_bkb
			,BOM.*
			,REQ.lot_no AS lot
		FROM (
			SELECT 
				MAIN.goods_code
				,MAIN.id
				,MAIN.id_detail
				,MAIN.qty_roll
				,MAIN.qty_yard_exist
				,MAIN.qty_roll_exist
				,SUM(ifnull(MAIN.qty_yard,0))balance1
				,(MAIN.qty_roll)balance2
				,MAIN.id_bom_item
				,MAIN.color
				,MAIN.size
				,MAIN.fabric_desc
				,SUM(ifnull(MAIN.qty_yard,0))qty_yard
				,MAIN.cons
				,SUM(MAIN.qty_bom)qty_bom
				,MAIN.unit
				,MAIN.fullname
				,MAIN.cancel
				,MAIN.rule_bom
				,MAIN.posno
				,MAIN.nama_panel
				,MAIN.dest 
				,MAIN.id_cost
				,MAIN.id_so
				,MAIN.so_no				
				,MAIN.id_so_det 		
				,MAIN.id_group
				,MAIN.id_subgroup
				,MAIN.id_type
				,MAIN.id_contents
				,MAIN.id_width
				,MAIN.id_length
				,MAIN.id_weight
				,MAIN.id_color
				,MAIN.id_desc
				,MAIN.id_item		
				,MAIN.id_jo
				,'0' fg_cek
				,'' remark
				,'' qty_received
				,'' qty_return
			FROM (	
				SELECT
					MS_ITEM.goods_code 
					,s.nama_sub_group
					,'0' id
					,'0' id_detail
					,'0' qty_roll
					,'0' qty_yard_exist
					,'0' qty_roll_exist			
					,k.id id_bom_item
					,IF(k.rule_bom LIKE '%ALL COLOR%','ALL COLOR',l.color)color
					,k.rule_bom
					,if(k.rule_bom LIKE '%ALL SIZE%','ALL SIZE',l.size)size
					,concat(if(a.nama_group='-','',a.nama_group),' ',if(s.nama_sub_group='-','',s.nama_sub_group),' ',
					if(d.nama_type='-','',d.nama_type),' ',if(e.nama_contents='-','',e.nama_contents),' ',if(f.nama_width='-','',f.nama_width),' ',
					if(g.nama_length='-','',g.nama_length),' ',if(h.nama_weight='-','',h.nama_weight),' ',if(i.nama_color='-','',i.nama_color),' ',if(j.nama_desc='-','',j.nama_desc),' ',if(j.add_info='-','',j.add_info)) fabric_desc,
					l.qty qty_yard 
					,k.cons
					,round(((ifnull(l.qty,0)))*((ifnull(k.cons,0))),2) qty_bom
					,k.unit
					,up.fullname
					,k.cancel
					,k.dest
					,k.posno
					,mpan.nama_panel
					,ACT.id id_cost
					,SO.id id_so
					,SO.so_no
					,k.id_so_det 
					,a.id id_group
					,s.id id_subgroup
					,d.id id_type
					,e.id id_contents
					,f.id id_width
					,g.id id_length
					,h.id id_weight
					,i.id id_color
					,j.id id_desc
					,k.id_item
					,k.id_jo
				FROM bom_jo_item k 
				INNER JOIN so_det l ON k.id_so_det=l.id 
				INNER JOIN mastergroup a 
				INNER JOIN mastersubgroup s ON a.id=s.id_group
				INNER JOIN mastertype2 d ON s.id=d.id_sub_group
				INNER JOIN mastercontents e ON d.id=e.id_type
				INNER JOIN masterwidth f ON e.id=f.id_contents 
				INNER JOIN masterlength g ON f.id=g.id_width
				INNER JOIN masterweight h ON g.id=h.id_length
				INNER JOIN mastercolor i ON h.id=i.id_weight
				INNER JOIN masterdesc j ON i.id=j.id_color AND k.id_item=j.id
				INNER JOIN masteritem MS_ITEM ON MS_ITEM.id_gen = j.id
				INNER JOIN so SO ON SO.id = l.id_so
				INNER JOIN act_costing ACT ON ACT.id = SO.id_cost
				INNER JOIN userpassword up ON k.username=up.username
				LEFT JOIN masterpanel mpan ON k.id_panel=mpan.id
				WHERE k.id_jo='{$id_jo}' AND k.status='M' 
				AND k.cancel='N'
				AND a.id = '1'
			) MAIN GROUP BY  MAIN.id,MAIN.posno,MAIN.rule_bom,MAIN.size,MAIN.id_item,MAIN.color
		)BOM 

		INNER JOIN (
			SELECT 
				b.username
				,b.bppbno
				,b.id_item
				,b.id_jo
				,b.bppbdate
				,ac.kpno
				,ac.styleno
				,b.tanggal_aju
				,supplier tujuan
				,so.mindeldate as del_date
				,concat(mi.goods_code,' ',mi.itemdesc) itemdesc
				,mi.color
				,no_rak as location
				,qtyloc as loc_qty
				,unitloc as loc_unit
				,b.qty as qty_request
				,qtysdhout as out_qty
				,unitsdhout as out_unit
				,'' as check_picker
				,'' as check_loader
				,'' as check_penerima
				,b.remark
				,mi.id_gen
				,tbllok.lot_no         
			FROM bppb_req b
			INNER JOIN masteritem mi on b.id_item = mi.id_item 
			INNER JOIN mastersupplier msup on b.id_supplier=msup.id_supplier 
			INNER JOIN (
				SELECT 
					id_so,
					id_jo 
				FROM jo_det GROUP BY id_jo
			) jod ON b.id_jo=jod.id_jo 
			INNER JOIN (
				SELECT 
					so.id,
					id_cost,
					min(sod.deldate_det) mindeldate 
				FROM so 
				INNER JOIN so_det sod ON so.id=sod.id_so GROUP BY so.id
			) so on jod.id_so=so.id 
			INNER JOIN act_costing ac ON so.id_cost=ac.id 
			LEFT JOIN (
				SELECT 
					id_item,
					id_jo,
					group_concat(kode_rak,' ',qtyloc,' ',unitloc SEPARATOR ', ') no_rak,
					0 qtyloc,
					'' unitloc,
					lot_no 
				FROM (
					SELECT 
						a.id_item,
						a.id_jo,
						d.kode_rak,
						if(r.lot_no = '' OR r.lot_no IS NULL OR r.lot_no = '-', 'N/A', r.lot_no) lot_no,
						round(sum(roll_qty),2) qtyloc,
						unit unitloc 
					FROM bpb_roll_h a 
					INNER JOIN bpb_roll r ON a.id=r.id_h 
					INNER JOIN master_rak d ON r.id_rak_loc=d.id  
					GROUP BY id_item,id_jo,d.kode_rak
				) tmplok GROUP BY id_item,id_jo
			) tbllok ON b.id_item=tbllok.id_item AND b.id_jo=tbllok.id_jo 
			LEFT JOIN (
				SELECT 
					bppbno_req,
					id_item,
					id_jo,
					sum(qty) qtysdhout,
					unit unitsdhout 
				FROM bppb 
				WHERE bppbno_req='{$req}' GROUP BY id_item,id_jo
			) tblsdhout ON b.bppbno=tblsdhout.bppbno_req AND b.id_item=tblsdhout.id_item 
			WHERE 1=1
			AND bppbno = '{$req}'
		)REQ ON BOM.id_jo = REQ.id_jo AND REQ.id_gen = BOM.id_item
		WHERE REQ.out_qty IS NOT NULL
	
	) AS X";

}
else {
	$table = "(
		SELECT
			CIH.id_cut_in AS id
			,CIH.id_act_costing AS id_cost
			,CIH.id_jo
			,CID.id_cut_in_detail AS id_detail
			,CID.fabric_code AS goods_code
			,CID.color
			,CID.fabric_desc
			,CID.lot
			,CID.fg_cek
			,CID.unit
			,CID.remark 
			,CID.qty_received 
			,CID.qty_return
			,CID.id_so
			,CID.id_so_det
			,CID.id_item
			,CID.id_bom_item
			,CID.id_group
			,CID.id_subgroup
			,CID.id_type2 AS id_type
			,CID.id_content AS id_contents
			,CID.id_width
			,CID.id_length
			,CID.id_weight
			,CID.id_color
			,CID.id_desc
			,''qty_yard
			,''cons
			,''qty_bom
			,''fullname
			,''cancel
			,''rule_bom
			,''posno
			,''nama_panel
			,''dest
			,''balance1
			,''balance2
			,''size
			,REQ.qty_request 
			,REQ.out_qty AS qty_bkb
		FROM prod_cut_in CIH
		INNER JOIN (
			SELECT * FROM prod_cut_in_detail WHERE id_cost = '{$id_cost}'
		)CID ON CIH.id_cut_in = CID.id_cut_in

		INNER JOIN (
			SELECT 
				b.username
				,b.bppbno
				,b.id_item
				,b.id_jo
				,b.bppbdate
				,ac.kpno
				,ac.styleno
				,b.tanggal_aju
				,supplier tujuan
				,so.mindeldate as del_date
				,concat(mi.goods_code,' ',mi.itemdesc) itemdesc
				,mi.color
				,no_rak as location
				,qtyloc as loc_qty
				,unitloc as loc_unit
				,b.qty as qty_request
				,qtysdhout as out_qty
				,unitsdhout as out_unit
				,'' as check_picker
				,'' as check_loader
				,'' as check_penerima
				,b.remark
				,mi.id_gen
				,tbllok.lot_no         
			FROM bppb_req b
			INNER JOIN masteritem mi on b.id_item = mi.id_item 
			INNER JOIN mastersupplier msup on b.id_supplier=msup.id_supplier 
			INNER JOIN (
				SELECT 
					id_so,
					id_jo 
				FROM jo_det GROUP BY id_jo
			) jod ON b.id_jo=jod.id_jo 
			INNER JOIN (
				SELECT 
					so.id,
					id_cost,
					min(sod.deldate_det) mindeldate 
				FROM so 
				INNER JOIN so_det sod ON so.id=sod.id_so GROUP BY so.id
			) so on jod.id_so=so.id 
			INNER JOIN act_costing ac ON so.id_cost=ac.id 
			LEFT JOIN (
				SELECT 
					id_item,
					id_jo,
					group_concat(kode_rak,' ',qtyloc,' ',unitloc SEPARATOR ', ') no_rak,
					0 qtyloc,
					'' unitloc,
					lot_no 
				FROM (
					SELECT 
						a.id_item,
						a.id_jo,
						d.kode_rak,
						if(r.lot_no = '' OR r.lot_no IS NULL, 'N/A', r.lot_no) lot_no,
						round(sum(roll_qty),2) qtyloc,
						unit unitloc 
					FROM bpb_roll_h a 
					INNER JOIN bpb_roll r ON a.id=r.id_h 
					INNER JOIN master_rak d ON r.id_rak_loc=d.id  
					GROUP BY id_item,id_jo,d.kode_rak
				) tmplok GROUP BY id_item,id_jo
			) tbllok ON b.id_item=tbllok.id_item AND b.id_jo=tbllok.id_jo 
			LEFT JOIN (
				SELECT 
					bppbno_req,
					id_item,
					id_jo,
					sum(qty) qtysdhout,
					unit unitsdhout 
				FROM bppb 
				WHERE bppbno_req='{$req}' GROUP BY id_item,id_jo
			) tblsdhout ON b.bppbno=tblsdhout.bppbno_req AND b.id_item=tblsdhout.id_item 
			WHERE 1=1
			AND bppbno = '{$req}'
		)REQ ON CIH.id_jo = REQ.id_jo AND REQ.id_gen = CID.id_item
		
		WHERE CIH.id_act_costing = '{$id_cost}' AND CIH.id_jo ='{$id_jo}' AND CIH.request_no = '{$req}'
	
	) AS X";
}
	
$colomn = " 
		X.goods_code
		,X.id
		,X.id_detail
		,X.balance1
		,X.balance2		
		,X.id_bom_item
		,X.color
		,X.size
		,X.fabric_desc
		,X.qty_yard
		,X.cons
		,X.qty_bom
		,X.unit
		,X.fullname
		,X.cancel
		,X.rule_bom
		,X.posno
		,X.nama_panel
		,X.dest 
		,X.id_cost
		,X.id_so
		,X.so_no		
		,X.id_so_det 		
		,X.id_group
		,X.id_subgroup
		,X.id_type
		,X.id_contents
		,X.id_width
		,X.id_length
		,X.id_weight
		,X.id_color
		,X.id_desc	
		,X.id_item
		,X.lot
		,X.qty_request
		,X.qty_bkb
		,X.qty_received
		,X.qty_return
		,X.fg_cek
		,X.remark
";


/* $rowperpage = $_GET['length']; // Rows display per page
$columnIndex = $_GET['order'][0]['column']; // Column index */
$columnName = "id"; // Column name
 //$columnSortOrder = $_GET['order'][0]['dir']; // asc or desc
$searchValue = $_GET['search']['value']; // Search value
 

### Search 
$searchQuery = " ";  
if($searchValue != ''){
	$searchQuery = " AND (
   		X.kpno					LIKE'%".$searchValue."%'
		OR X.goods_code			LIKE'%".$searchValue."%'
		OR X.color				LIKE'%".$searchValue."%'
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


$empQuery = "select $colomn from $table WHERE 1 ".$searchQuery;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
// echo $empQuery;die();
$no = 0;
$fabric_code = '';
// $subtotal = 0;
$total_qty = 0;
$x=0;
$line_total = 0;
$qty_input = 0;
$num = 1;

while ($row = mysqli_fetch_assoc($empRecords)) {

	if($row['remark'] == ''){
		$rem_val = "";
	}
	else{
		$rem_val = $row['remark'];
	}
	$input = '';
	$input .="<input type='text' id='remark_".$no."' class='form-control remark' value='$rem_val' onchange='handleKeyUp(this)' onmouseover='mouseOver(this)'>";




	if($row['qty_received'] == ''){
		$need_val = "0.00";
	}
	else{
		$need_val = $row['qty_received'];
	}
	$need = '';
	$need .="<input type='text' id='need_".$no."' class='form-control need ".rawurlencode($row['goods_code'])."' value='$need_val' onchange='handleKeyUp(this)'>";



	
	if($row['fg_cek'] == '1'){
		$cek = "checked='checked'";
	}
	else{
		$cek = "";
	}
	$button = '';
	$button .="<input type='checkbox' id='cek_".$no."' class='cek' value='1' $cek onclick='handleKeyUp(this)'>";
	
	//$qty_input = $row['qty_received'];

	if ($id_url == '-1') {
		$ret_val = "";
		$qty_input = "0.00";
	} else {
		$ret_val = "<input type='text' id='ret_".$no."' class='form-control ret' value='".$row['qty_return']."' onchange='handleKeyUp(this)'>";
		$qty_input = (float)$qty_input + (float)$row['qty_received'];
	}
	$retur = '';
	$retur .="$ret_val";
	

	$total_qty = $total_qty + $row['qty_bkb'];

	if($x=='0'){
		$fabric_code = $row['goods_code'];
	}else{
			$line_total = 1;
			$data[] = array(
				"num"=>htmlspecialchars(""),
				"id"=>htmlspecialchars(""),
				"id_detail"=>htmlspecialchars(""),
				"id_bom_item"=>htmlspecialchars(""),
				"fabric_code"=>htmlspecialchars(""),
				"material_color"=>htmlspecialchars(""),
				"fabric_desc"=>htmlspecialchars(""),
				"lot"=>htmlspecialchars("-"),
				// "lot_qty"=>htmlspecialchars((number_format($subtotal, 2, '.', ','))),
				"need"=>htmlspecialchars(""),
				"need_val"=>htmlspecialchars(""),
				"return"=>htmlspecialchars(""),
				"return_val"=>htmlspecialchars(""),
				"unit"=>htmlspecialchars(""),
				"cek_val"=>htmlspecialchars(""),
				"button"=>htmlspecialchars(""),
				"remark"=>htmlspecialchars(""),
				"remark_val"=>htmlspecialchars(""),
				"id_cost"=>"",
				"id_so"=>"",
				"so_no"=>"",
				"id_so_det"=>"",
				"id_group"=>"",
				"id_subgroup"=>"",
				"id_type"=>"",
				"id_contents"=>"",
				"id_width"=>"",
				"id_length"=>"",
				"id_weight"=>"",
				"id_color"=>"",
				"id_item"=>"",
				"id_desc"=>"",
				"isTotal"=>"0"
			);		
			// $fabric_code = $row['goods_code'];
			// $subtotal = 0;
			// $subtotal = $row['lot_qty'];

	}


	if($line_total =='1'){
		$no++;
		if($row['remark'] == ''){
			$rem_val = "";
		}
		else{
			$rem_val = $row['remark'];
		}
		$input = '';
		$input .="<input type='text' id='remark_".$no."' class='form-control remark' value='$rem_val' onchange='handleKeyUp(this)' onmouseover='mouseOver(this)'>";




		if($row['qty_received'] == ''){
			$need_val = "0.00";
		}
		else{
			$need_val = $row['qty_received'];
		}
		$need = '';
		$need .="<input type='text' id='need_".$no."' class='form-control need ".rawurlencode($row['goods_code'])."' value='$need_val' onchange='handleKeyUp(this)'>";



		
		if($row['fg_cek'] == '1'){
			$cek = "checked='checked'";
		}
		else{
			$cek = "";
		}
		$button = '';
		$button .="<input type='checkbox' id='cek_".$no."' class='cek' value='1' $cek onclick='handleKeyUp(this)'>";
		
		// $qty_input = $row['qty_received'];

		if ($id_url == '-1') {
			$ret_val = "";
			$qty_input = "0.00";
		} else {
			$ret_val = "<input type='text' id='ret_".$no."' class='form-control ret' value='".$row['qty_return']."' onchange='handleKeyUp(this)'>";
			//$qty_input = $qty_input + $row['qty_received'];
		}
		$retur = '';
		$retur .="$ret_val";
		
		$line_total =0;
	}
	// (ISSET($row['qty_received']) ? $row['qty_received']  : "0.00"  )
	if(ISSET($row['qty_received'])){
		if($row['qty_received'] == ''){
			$row['qty_received'] = '0';
		}
	}

	$data[] = array(
		"num"=>$num,
		"id"=>htmlspecialchars($row['id']),
		"id_detail"=>htmlspecialchars($row['id_detail']),
		"id_bom_item"=>htmlspecialchars($row['id_bom_item']),
		"fabric_code"=>rawurldecode($row['goods_code']),
		"material_color"=>rawurldecode($row['color']),
		"fabric_desc"=>rawurldecode($row['fabric_desc']),
		"lot"=>htmlspecialchars($row['lot']),
		// "lot_qty"=>htmlspecialchars((number_format($row['lot_qty'], 2, '.', ','))),
		"need"=>rawurlencode($need),
		"need_val"=>(ISSET($row['qty_received']) ? $row['qty_received']  : "0.00"  ),
		"return"=>rawurlencode($retur),
		"return_val"=>(ISSET($row['qty_return']) ? $row['qty_return']  : ""  ),
		"unit"=>htmlspecialchars($row['unit']),
		"cek_val"=>(ISSET($row['fg_cek']) ? $row['fg_cek']  : "0"  ),
		"button"=>rawurlencode($button),
		"remark"=>rawurlencode($input),
		"remark_val"=>(ISSET($row['remark']) ? $row['remark']  : ""  ),
		"id_cost"=>$row['id_cost'],
		"id_so"=>$row['id_so'],
		"so_no"=>$row['so_no'],
		"id_so_det"=>$row['id_so_det'],
		"id_group"=>$row['id_group'],
		"id_subgroup"=>$row['id_subgroup'],
		"id_type"=>$row['id_type'],
		"id_contents"=>$row['id_contents'],
		"id_width"=>$row['id_width'],
		"id_length"=>$row['id_length'],
		"id_weight"=>$row['id_weight'],
		"id_color"=>$row['id_color'],
		"id_item"=>$row['id_item'],
		"id_desc"=>$row['id_desc'],
		"isTotal"=>"1"
	);

	$num++;
	$x=$x+1;
	$no++;
}

// $row2 = mysqli_fetch_assoc($empRecords);
// print_r($row2);

$total_input = '';
$total_input .="<input type='text' id='totin' class='form-control' value='$qty_input' disabled=disabled>";

$data[] = array(
	"num"=>htmlspecialchars(""),
	"id"=>htmlspecialchars(""),
	"id_detail"=>htmlspecialchars(""),
	"id_bom_item"=>htmlspecialchars(""),
	"fabric_code"=>htmlspecialchars(""),
	"material_color"=>htmlspecialchars(""),
	"fabric_desc"=>htmlspecialchars(""),
	"lot"=>htmlspecialchars("Total Received"),
	// "lot_qty"=>htmlspecialchars((number_format($subtotal, 2, '.', ','))),
	"need"=>rawurlencode($total_input),
	"need_val"=>htmlspecialchars(""),
	"return"=>htmlspecialchars(""),
	"return_val"=>htmlspecialchars(""),
	"unit"=>htmlspecialchars(""),
	"cek_val"=>htmlspecialchars(""),
	"button"=>htmlspecialchars(""),
	"remark"=>htmlspecialchars(""),
	"remark_val"=>htmlspecialchars(""),
	"id_cost"=>"",
	"id_so"=>"",
	"so_no"=>"",
	"id_so_det"=>"",
	"id_group"=>"",
	"id_subgroup"=>"",
	"id_type"=>"",
	"id_contents"=>"",
	"id_width"=>"",
	"id_length"=>"",
	"id_weight"=>"",
	"id_color"=>"",
	"id_item"=>"",
	"id_desc"=>"",
	"isTotal"=>"0"
);

$qty_out = '';
$qty_out .="<input type='text' id='totout' class='form-control' value='$total_qty' disabled=disabled>";

$data[] = array(
	"num"=>htmlspecialchars(""),
	"id"=>htmlspecialchars(""),
	"id_detail"=>htmlspecialchars(""),
	"id_bom_item"=>htmlspecialchars(""),
	"fabric_code"=>htmlspecialchars(""),
	"material_color"=>htmlspecialchars(""),
	"fabric_desc"=>htmlspecialchars(""),
	"lot"=>htmlspecialchars("Total BPPB Out"),
	// "lot_qty"=>htmlspecialchars((number_format($subtotal, 2, '.', ','))),
	"need"=>rawurlencode($qty_out),
	"need_val"=>htmlspecialchars(""),
	"return"=>htmlspecialchars(""),
	"return_val"=>htmlspecialchars(""),
	"unit"=>htmlspecialchars(""),
	"cek_val"=>htmlspecialchars(""),
	"button"=>htmlspecialchars(""),
	"remark"=>htmlspecialchars(""),
	"remark_val"=>htmlspecialchars(""),
	"id_cost"=>"",
	"id_so"=>"",
	"so_no"=>"",
	"id_so_det"=>"",
	"id_group"=>"",
	"id_subgroup"=>"",
	"id_type"=>"",
	"id_contents"=>"",
	"id_width"=>"",
	"id_length"=>"",
	"id_weight"=>"",
	"id_color"=>"",
	"id_item"=>"",
	"id_desc"=>"",
	"isTotal"=>"0"
);	

## Response
$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecordwithFilter,
  "iTotalDisplayRecords" => $totalRecords,
  "aaData" => $data
);
echo json_encode($response);
?>