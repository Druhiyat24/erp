<?php 
	function d_from($from){
		$d_from = explode("/",$from."/01");
		$d_from = $d_from[1]."-".$d_from[0]."-".$d_from[2];
		return $d_from;
	}
	function d_to($to){
		$d_to = explode("/",$to."/01");
		$d_to = $d_to[1]."-".$d_to[0]."-".$d_to[2];
		$d_to = date("Y-m-d", strtotime("1 month",strtotime($d_to)));
		$d_to = date("Y-m-d", strtotime("-1 days",strtotime($d_to)));
		return $d_to;
	}

include __DIR__ .'/../../../include/conn.php';
## Read value
$data = $_GET;
$d_from = $d_from =date("Y-m-d", strtotime($data['from']));
$d_to = $d_to =date("Y-m-d", strtotime($data['to']));

/* 
print_r($data);
die();  */ 
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
/* $columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name */
//$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
SELECT s.id
		,s.id_cost
		,s.so_no
		,s.so_date
		,IFNULL(sd.deldate_det,'N/A')deldate_det
		,s.revise
		,IFNULL(ms.supplier_code,'N/A')supplier_code
		,IFNULL(ms.Supplier,'N/A')Supplier
		,IFNULL(mi.goods_code,'N/A')goods_code
		,IFNULL(sd.color,'N/A')color
		,IFNULL(sd.size,'N/A')size
		,sd.color_size
		,IFNULL(mi.itemdesc,'N/A')itemdesc
		,sd.unit
		,IFNULL(s.qty,'N/A')qty_so
		,ROUND(sd.price,2)price
		,if(s.so_no IS NOT NULL ,(s.qty*sd.price),'0')total_price
		,IFNULL(bp.bppbno_int,'N/A')bppbno_int
		,IFNULL(bp.bppbdate,'N/A')bppbdate
		,IFNULL(bp.qty,'N/A')qty_surat_jalan
		,'N/A' id_order
		,ROUND(bp.qty*sd.price,2)total_price_sj
		,ifnull(s.qty-bp.qty,'N/A')outstanding_qty
	
	FROM so s

LEFT JOIN ( select
		 id
		,id_so
		,color
		,CONCAT(color,' (',size,')') AS color_size
		,unit
		,size
		,ROUND(price,2)price
		,deldate_det
		FROM so_det ) sd ON s.id = sd.id_so
		
LEFT JOIN ( SELECT 
		 id
		,id_buyer
			
		FROM act_costing ) act ON s.id_cost = act.id
			
LEFT JOIN ( SELECT
			Id_Supplier
	 	  ,Supplier
	 	  ,supplier_code
	 	  
	 	  FROM mastersupplier ) ms ON act.id_buyer = ms.Id_Supplier

LEFT JOIN ( select 
		 id_item
		,id_so_det
		
	FROM masterstyle )e ON e.id_item = sd.id

LEFT JOIN ( SELECT
		 id_item
		,goods_code
		,itemdesc
	
	FROM masteritem ) mi ON mi.id_item =  e.id_item 

LEFT JOIN ( SELECT
		 id
		,bppbno_int
		,bppbdate
		,id_item
		,id_so_det
		,qty
		,curr
		
	FROM bppb ) bp ON bp.id_so_det = sd.id

	
	WHERE sd.deldate_det NOT IN ('0000-00-00') AND s.so_date >='{$d_from}' AND s.so_date <='{$d_to}'

	AND bp.id_so_det IS NOT NULL	

ORDER BY 	sd.id_so ASC,s.so_no ASC
		   ,s.so_date DESC
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (
	   X.so_no                LIKE'%".$searchValue."%'
	OR X.so_date              LIKE'%".$searchValue."%'
	OR X.deldate_det          LIKE'%".$searchValue."%'
	OR X.revise               LIKE'%".$searchValue."%'
	OR X.supplier_code        LIKE'%".$searchValue."%'
	OR X.Supplier             LIKE'%".$searchValue."%'
	OR X.goods_code           LIKE'%".$searchValue."%'
	OR X.color          	  LIKE'%".$searchValue."%'
	OR X.itemdesc		      LIKE'%".$searchValue."%'
	OR X.unit				  LIKE'%".$searchValue."%'
	OR X.qty_so 	          LIKE'%".$searchValue."%'	
	OR X.price			      LIKE'%".$searchValue."%'
	OR X.total_price          LIKE'%".$searchValue."%'
	OR X.bppbno_int	          LIKE'%".$searchValue."%'
	OR X.bppbdate          	  LIKE'%".$searchValue."%'	
	OR X.id_order        	  LIKE'%".$searchValue."%'	
	OR X.qty_surat_jalan      LIKE'%".$searchValue."%'	

)
";
}

## Total number of records without filtering
$sel = mysqli_query($conn_li,"select count(*) allcount from $table");
$records = mysqli_fetch_assoc($sel); 

$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($conn_li,"select count(*)  allcount from $table WHERE 1 ".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records
$colomn = "		 	                    
			 X.so_no            	
			,X.so_date                 	
			,X.deldate_det                  	
			,X.revise    
			,X.supplier_code
			,X.Supplier
			,X.goods_code                   	
			,X.color_size	
			,X.itemdesc
			,X.unit	
			,X.qty_so
			,X.price                    	
			,X.total_price	
			,X.bppbno_int	
			,X.bppbdate	
			,X.id_order	
			,X.qty_surat_jalan
			,X.total_price_sj	
			
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by X.so_date limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];


$total_qty	 = $row['qty_surat_jalan'];
$total_price_sj = $row['total_price_sj'];
// $outstanding_qty= $outstanding_qty + $row['outstanding_qty'];
// $qty_so= $qty_so;



	$data[] = array(
"so_no"=>htmlspecialchars($row['so_no']),
"so_date"=>htmlspecialchars($row['so_date']),
"deldate_det"=>htmlspecialchars($row['deldate_det']),
"revise"=>htmlspecialchars($row['revise']),
"supplier_code"=>htmlspecialchars($row['supplier_code']),
"Supplier"=>htmlspecialchars($row['Supplier']),
"goods_code"=>htmlspecialchars($row['goods_code']),
"color_size"=>htmlspecialchars($row['color_size']),
"itemdesc"=>htmlspecialchars($row['itemdesc']),
"unit"=>htmlspecialchars($row['unit']),
"qty_so"=>htmlspecialchars($row['qty_so']),
"price"=>htmlspecialchars(number_format((float)$row['price'], 2, '.', ',')),
"total_price"=>htmlspecialchars(number_format((float)$row['total_price'], 2, '.', ',')),
"bppbno_int"=>htmlspecialchars($row['bppbno_int']),
"bppbdate"=>htmlspecialchars($row['bppbdate']),
"id_order"=>htmlspecialchars($row['id_order']),
"qty_surat_jalan"=>htmlspecialchars($row['qty_surat_jalan']),
"total_qty"=>htmlspecialchars($total_qty),
"total_price_sj"=>htmlspecialchars(number_format((float)$row['total_price_sj'], 2, '.', ',')),
// "outstanding_qty"=>htmlspecialchars($outstanding_qty),
   );
	$qty_so=$outstanding_qty;
	//$outstanding_qty=$qty_so;
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