<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_GET['draw'];
$row = $_GET['start'];
//print_r($_GET);
$id_cost = $_GET['id_cost'];
$id_url = $_GET['id_url'];
$id_jo = $_GET['id_jo'];
if($id_url == '-1'){
	$table = "(	
SELECT 
		 MAIN.goods_code
		,MAIN.id
		,MAIN.id_detail
		,MAIN.qty_roll
		,MAIN.qty_yard_exist
		,MAIN.qty_roll_exist
		/*,SUM(ifnull(MAIN.qty_yard,0))balance1 */
		,'0.00' balance1
		,'0.00' balance2
		/*,(MAIN.qty_roll)balance2 */
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
 FROM(	
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
        	from bom_jo_item k inner join so_det l on k.id_so_det=l.id 
          inner join mastergroup a 
		  inner join mastersubgroup s on a.id=s.id_group
          inner join mastertype2 d on s.id=d.id_sub_group
          inner join mastercontents e on d.id=e.id_type
          inner join masterwidth f on e.id=f.id_contents 
          inner join masterlength g on f.id=g.id_width
          inner join masterweight h on g.id=h.id_length
          inner join mastercolor i on h.id=i.id_weight
          inner join masterdesc j on i.id=j.id_color and k.id_item=j.id
		  		INNER JOIN masteritem MS_ITEM   ON MS_ITEM.id_gen = j.id
		  		INNER JOIN so SO ON SO.id = l.id_so
		  		INNER JOIN act_costing ACT ON ACT.id = SO.id_cost
          inner join userpassword up on k.username=up.username
					left join masterpanel mpan on k.id_panel=mpan.id 
where k.id_jo='{$id_jo}' and k.status='M' and k.cancel='N'
					AND a.id = '1'
)MAIN GROUP BY  MAIN.id,MAIN.posno,MAIN.rule_bom,MAIN.size,MAIN.id_item,MAIN.color
					) AS X";
	
/* 	$colomn = "X.id
		,X.nama_group fabric_code
		,X.color material_color
		,X.item fabric_desc"; */
}
else {
	$table = "(SELECT 				
					 INDUK.goods_code 
					,CIH.id_cut_in
					,CIH.id_act_costing
					,CIH.id_jo
					,CID.qty_yard qty_yard_exist
					,CID.qty_roll qty_roll_exist
					/*,(INDUK.qty_yard - CID.qty_yard)balance1
					,(INDUK.qty_roll - CID.qty_roll)balance2 */
					,'0.00' balance1
					,'0.00' balance2 					
					,CIH.id_cut_in id
					,CID.id_cut_in_detail id_detail
					,INDUK.qty_roll
					,INDUK.id_bom_item
					,INDUK.color
					,INDUK.size
					,INDUK.fabric_desc
					,INDUK.qty_yard
					,INDUK.cons
					,INDUK.qty_bom
					,INDUK.unit
					,INDUK.fullname
					,INDUK.cancel
					,INDUK.rule_bom
					,INDUK.posno
					,INDUK.nama_panel
					,INDUK.dest 
					,INDUK.id_cost
					,INDUK.id_so
					,INDUK.id_so_det 		
					,INDUK.id_group
					,INDUK.id_subgroup
					,INDUK.id_type
					,INDUK.id_contents
					,INDUK.id_width
					,INDUK.id_length
					,INDUK.id_weight
					,INDUK.id_color
					,INDUK.id_desc
					,INDUK.id_item		


 FROM cut_in CIH
INNER JOIN (SELECT * FROM cut_in_detail WHERE id_cost = '{$id_cost}')CID ON CIH.id_cut_in = CID.id_cut_in
INNER JOIN (	


	SELECT 
					 MAIN.goods_code
					,MAIN.id
					,MAIN.id_detail
					,MAIN.qty_roll
					,MAIN.id_bom_item
					,MAIN.color
					,MAIN.size
					,MAIN.fabric_desc
					,SUM(MAIN.qty_yard)qty_yard
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
			
			
			
			FROM(	
				SELECT
					MS_ITEM.goods_code 
					,s.nama_sub_group
					,'0' id
					,'0' id_detail
					,'0' qty_roll
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
						from bom_jo_item k inner join so_det l on k.id_so_det=l.id 
					inner join mastergroup a 
					inner join mastersubgroup s on a.id=s.id_group
					inner join mastertype2 d on s.id=d.id_sub_group
					inner join mastercontents e on d.id=e.id_type
					inner join masterwidth f on e.id=f.id_contents 
					inner join masterlength g on f.id=g.id_width
					inner join masterweight h on g.id=h.id_length
					inner join mastercolor i on h.id=i.id_weight
					inner join masterdesc j on i.id=j.id_color and k.id_item=j.id
							INNER JOIN masteritem MS_ITEM   ON MS_ITEM.id_gen = j.id
							INNER JOIN so SO ON SO.id = l.id_so
							INNER JOIN act_costing ACT ON ACT.id = SO.id_cost
					inner join userpassword up on k.username=up.username
								left join masterpanel mpan on k.id_panel=mpan.id 
			where k.id_jo='{$id_jo}' and k.status='M' and k.cancel='N' 
								AND a.id = '1'
			)MAIN GROUP BY  MAIN.id,MAIN.posno,MAIN.rule_bom,MAIN.size,MAIN.id_item,MAIN.color
			
		)INDUK ON CID.id_cost = INDUK.id_cost AND CID.id_bom_item = INDUK.id_bom_item
			WHERE CIH.id_act_costing = '{$id_cost}' AND CIH.id_jo ='{$id_jo}') AS X";
}
	$colomn = " 
		X.goods_code
		,X.id
		,X.id_detail
		,X.qty_yard_exist
		,X.qty_roll_exist
		,X.balance1
		,X.balance2		
		,X.qty_roll
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
while ($row = mysqli_fetch_assoc($empRecords)) {
	// echo '123';die();
//echo $row['n_post'];
	// $yard = $row['qty'];
	// $roll = $yard / 100;

	$qty_yard = "0";
	if($id_url){
		$qty_yard = $row['qty_yard_exist'];
		$qty_roll = $row['qty_roll_exist'];				

	}else{
		$qty_yard = $row['qty_yard'];
		$qty_roll = $row['qty_roll'];		
	}

	$input = '';
	$input .="<input type='text' id='qtyyard_".$no."' class='form-control yard' value='{$qty_yard}' onchange='handleKeyUp(this)'>";

	$input2 = '';
$input2 .="<input type='text' id='qtyroll_".$no."' class='form-control roll' value='{$qty_roll}' onchange='handleKeyUp(this)'>";
	$no++;

	$data[] = array(
		"id"=>htmlspecialchars($row['id']),
		"id_detail"=>htmlspecialchars($row['id_detail']),
		"id_bom_item"=>htmlspecialchars($row['id_bom_item']),
		"fabric_code"=>htmlspecialchars($row['goods_code']),
		"material_color"=>htmlspecialchars($row['color']),
		"fabric_desc"=>htmlspecialchars($row['fabric_desc']),
		"qty_yard"=>$row['qty_yard'],
		"qty_yard_need"=>"0.00",
		"qty_roll_need"=>"0.00",
		"button"=>rawurlencode($input),
		// "qty_yard"=>htmlspecialchars($row['qty']),
		"balance1"=>htmlspecialchars(number_format((float)$row['balance1'], 2, '.', ',')),
		"unit1"=>"YDS",
		"qty_roll"=>"0",
		"button2"=>rawurlencode($input2),
		// "qty_roll"=>htmlspecialchars($roll),
		"balance2"=>"0.00",
		"unit2"=>"ROLL",
		"id_cost"=>$row['id_cost'],
		"id_so"=>$row['id_so'],
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
		"id_desc"=>$row['id_desc']
		
		
		
		
		// "qty_header"=>htmlspecialchars($row['qty_header'])
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