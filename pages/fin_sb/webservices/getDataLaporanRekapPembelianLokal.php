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
$d_from = d_from($data['from']);
$d_to   = d_to($data['to']);
/* 
print_r($_POST);
die();  */
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
			  SELECT mi.matclass
              , IF(ms.vendor_cat='NG','NON-GROUP','GROUP')AS non_group
              , IF(a.curr='USD',a.price,(a.price/mr.rate))AS price_USD
              , IF(a.curr='IDR',a.price,'')AS price_IDR
              , IF(a.curr='IDR',(a.price*a.qty),'')dpp
              , poh.ppn
              , ms.group_name
              , a.bpbdate
              , (a.price+((poh.ppn/100)*a.price))AS total_IDR_afterPPN
              , SUM((a.price+((poh.ppn/100)*a.price)))AS grand_total_IDR_afterPPN
              FROM masteritem mi 
              LEFT JOIN bpb a ON a.id_item=mi.id_item
              LEFT JOIN(
              SELECT MS_ITEM.id_item,MS_GROUP.* ,MS_ITEM.itemdesc,MS_ITEM.id_gen FROM mastergroup MS_GROUP
              INNER JOIN mastersubgroup MS_SUB_GROUP on MS_GROUP.id=MS_SUB_GROUP.id_group
              INNER JOIN mastertype2 MS_TYPE      ON MS_SUB_GROUP.id=MS_TYPE.id_sub_group
              INNER JOIN mastercontents MS_CONTENTS   ON MS_TYPE.id=MS_CONTENTS.id_type
              INNER JOIN masterwidth MS_WIDTH     ON MS_CONTENTS.id=MS_WIDTH.id_contents 
              INNER JOIN masterlength  MS_LENGTH    ON MS_WIDTH.id=MS_LENGTH.id_width
              INNER JOIN masterweight  MS_WEIGHT    ON MS_LENGTH.id=MS_WEIGHT.id_length
              INNER JOIN mastercolor  MS_COLOR    ON MS_WEIGHT.id=MS_COLOR.id_weight
              INNER JOIN masterdesc   MS_DESC     ON MS_COLOR.id=MS_DESC.id_color
              INNER JOIN masteritem MS_ITEM   ON MS_ITEM.id_gen = MS_DESC.id    
              ) MYITEM ON MYITEM.id_item=a.id_item
            LEFT JOIN mastersupplier ms ON ms.Id_Supplier=a.id_supplier
            LEFT JOIN po_header poh ON poh.id_supplier=ms.Id_Supplier
            LEFT JOIN masterrate mr ON mr.tanggal=poh.podate
            WHERE 1=1 
            AND ms.area = 'L' AND ms.tipe_sup='S' AND a.bpbno!='' and mi.matclass!=''  
            AND mr.v_codecurr='PAJAK'
            #AND a.bpbdate <= '2020-01-01' AND a.bpbdate >= '2020-03-01'
            GROUP BY mi.matclass,  ms.vendor_cat, poh.ppn
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	X.matclass      =  LIKE'%".$searchValue."%'
	X.non_group     =  LIKE'%".$searchValue."%'
	X.price_USD     =  LIKE'%".$searchValue."%'
	X.dpp     		=  LIKE'%".$searchValue."%'
	X.ppn     		=  LIKE'%".$searchValue."%'
	X.grand_total_IDR_afterPPN     		=  LIKE'%".$searchValue."%'


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
				X.matclass     
				,X.non_group       
				,X.price_USD       
				,X.dpp           
				,X.ppn        
				,X.grand_total_IDR_afterPPN  
				";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];


   $data[] = array(
"matclass"=>htmlspecialchars($row['matclass']),
"non_group"=>htmlspecialchars($row['non_group']),
"price_USD"=>htmlspecialchars(number_format((float)$row['price_USD'], 2, '.', ',')),
"dpp"=>htmlspecialchars(number_format((float)$row['dpp'], 2, '.', ',')),
"ppn"=>htmlspecialchars($row['ppn']),
"grand_total_IDR_afterPPN"=>htmlspecialchars(number_format((float)$row['grand_total_IDR_afterPPN'], 2, '.', ','))

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