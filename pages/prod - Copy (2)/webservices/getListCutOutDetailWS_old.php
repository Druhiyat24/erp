<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
 //print_r($_GET);die();
$pecah = explode("_",$_GET['id_cost']);
$id_cost = $pecah[0];
//$id_url = $_GET['id_url'];
$id_jo = $pecah[1];
$id_cut_in = $pecah[3];
// print_r($id_jo);die();
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "id"; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

$table = "(
	SELECT 
		X.*,
		Y.id_cut_in_detail,
		Y.id_cut_in,
		Y.lot
	FROM (

		SELECT 
			MAIN.goods_code fabric_code
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
			AND k.cancel='N' AND a.id = '1'
		) MAIN GROUP BY MAIN.id,MAIN.posno,MAIN.rule_bom,MAIN.size,MAIN.id_item,MAIN.color
	
	) X 
	INNER JOIN (
		SELECT * FROM prod_cut_in_detail WHERE id_cut_in='{$id_cut_in}' AND fg_cek = '1'
	)Y ON 
	X.id_cost			   		= Y.id_cost	
	AND X.id_so                = Y.id_so      
	AND X.id_so_det            = Y.id_so_det  
	AND X.id_item              = Y.id_item    
	AND X.id_bom_item          = Y.id_bom_item
	AND X.id_group             = Y.id_group   
	AND X.id_subgroup          = Y.id_subgroup
	AND X.id_type              = Y.id_type2   
	AND X.id_contents          = Y.id_content 
	AND X.id_width             = Y.id_width   
	AND X.id_length            = Y.id_length  
	AND X.id_weight            = Y.id_weight  
	AND X.id_color             = Y.id_color   
	AND X.id_desc              = Y.id_desc
	GROUP BY X.fabric_code
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
$colomn = "X.id
		  ,X.id_cut_in_detail
		  ,X.id_cut_in
		  ,X.id_subgroup
		  ,X.id_item
		  ,X.id_cost
		  ,X.fabric_code
		  ,X.color
		  ,X.fabric_desc
		  ,X.lot";

$empQuery = "select $colomn from $table WHERE 1 ".$searchQuery;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
// echo $empQuery;die();
$no = 1;
while ($row = mysqli_fetch_assoc($empRecords)) { 
	// echo '123';die();
//echo $row['n_post'];
	$id_group="";
	if($id_url)	{
		
	}

	$input = '';
	$input .="<select id='group_".$row['id_cut_in_detail']."' class='form-control select2 grouping'  tabindex='-1' aria-hidden='true' style='width: 100% !important;padding-bottom: 0px;padding-top: 0px;'>
				<option disabled selected>--Choose Shell--</option>";
					$sqlgrup=mysqli_query($conn_li,'SELECT * FROM mastergrouping');
					while ($grup = mysqli_fetch_assoc($sqlgrup)) {
	$input  .=" <option value='".$grup['id_grouping']."'>".$grup['name_grouping']."</option>";	
  					}
	$input	.="</select>";


	$input2 = '';
	$input2 .="<select id='panel_".$row['id_cut_in_detail']."' class='form-control select2 panel'  tabindex='-1' aria-hidden='true' style='width: 100% !important;padding-bottom: 0px;padding-top: 0px;'>
				<option disabled selected>--Choose Panel--</option>";
					$sqlpanel=mysqli_query($conn_li,'SELECT * FROM masterpanel');
					while ($panel = mysqli_fetch_assoc($sqlpanel)) {
	$input2 .=" <option value='".$panel['id']."'>".$panel['nama_panel']."</option>";
					}
	$input2	.="</select>";


	$input3 = '';
	$input3 .="<select id='lot_".$row['id_cut_in_detail']."' class='form-control select2 lot'  tabindex='-1' aria-hidden='true' style='width: 100% !important;padding-bottom: 0px;padding-top: 0px;'>
				<option disabled selected>--Choose Lot--</option>";
					$sqllot=mysqli_query($conn_li,"SELECT * FROM prod_cut_in_detail WHERE id_cut_in={$row['id_cut_in']} AND id_item={$row['id_item']} GROUP BY lot");
					// echo $sqllot;
					while ($lot = mysqli_fetch_assoc($sqllot)) {
	$input3 .=" <option value='".$lot['lot']."'>".$lot['lot']."</option>";
					}
	$input3	.="</select>";


	$button = '';
	$button .="<input type='button' id='cutout_".$row['id_cut_in_detail']."' onclick='Insert(".'"'.$row['id_cut_in_detail'].'"'.")' class='btn btn-primary addrow' value='Tambah'>
				<input type='hidden' 
						data-fabric_code='".rawurlencode($row['fabric_code'])."'
						data-id_costing='".rawurlencode($row['id_cost'])."'
						data-id_cut_in_detail='".rawurlencode($row['id_cut_in_detail'])."'
						data-color='".rawurlencode($row['color'])."'
						data-fabric_desc='".rawurlencode($row['fabric_desc'])."'
						data-is_new='".rawurlencode($row['is_new'])."'
						id='tmp_html_".$row['id_cut_in_detail']."' 
				value=''>
	";
	// $button .="<input type='button' id='cutout_".$row['id_item']."' onclick='getDetailAll(".'"'.$row['id_item'].'"'.','.'"'.$grup['id_grouping'].'"'.")' class='btn btn-primary' value='Tambah'>";
	// $no++;

	$data[] = array(
		"no"=>$no,
		"is_new"=>"0",
		"id_costing"=>htmlspecialchars($row['id_cost']),
		"id_cut_in_detail"=>htmlspecialchars($row['id_cut_in_detail']),
		"fabric_code"=>htmlspecialchars($row['fabric_code']),
		"color"=>htmlspecialchars($row['color']),
		"fabric_desc"=>htmlspecialchars($row['fabric_desc']),
		// "lot"=>htmlspecialchars($row['lot']),
		"lot"=>rawurlencode($input3),
		"select"=>rawurlencode($input),
		"select2"=>rawurlencode($input2),
		"button"=>rawurlencode($button)
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