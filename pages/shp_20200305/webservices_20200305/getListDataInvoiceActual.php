
<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$type_invoice = $_GET['type_invoice'];
$table = "(
SELECT 
				 A.n_id
				,A.v_noinvoicecommercial
				,A.v_pono
				,date(A.d_insert) d_insert
				,A.n_idinvoiceheader
				,if(IH.n_typeinvoice = '1','LOKAL','EXPORT')desc_inv
				,IH.n_post 
				,IH.invdate 
				,IH.n_typeinvoice
				,ACT.kpno
				,MSST.Styleno
				,IH.v_userpost
				,IH.v_codepaclist
				,IH.v_userpost_inv
				,IH.v_userpost_packing_list
				,IH.d_post_packing_list
				,IH.d_post_inv
				,MS.Supplier
				,SO.so_no
				FROM invoice_commercial A 
					LEFT JOIN invoice_header IH ON A.v_noinvoicecommercial = IH.invno
					LEFT JOIN invoice_detail ID ON IH.id = ID.id_inv
					LEFT JOIN so_det SOD ON SOD.id = ID.id_so_det
					LEFT JOIN so SO ON SO.id = SOD.id_so
					LEFT JOIN act_costing ACT ON ACT.id = SO.id_cost
					LEFT JOIN bppb BPB ON BPB.id_so_det = SOD.id
					LEFT JOIN masterstyle MSST ON BPB.id_item = MSST.id_item
					LEFT JOIN mastersupplier MS ON MS.Id_Supplier = ACT.id_buyer
					WHERE IH.n_typeinvoice = '$type_invoice'
					GROUP BY A.n_id)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (
 X.desc_inv					LIKE '%".$searchValue."%'
OR X.v_noinvoicecommercial      LIKE '%".$searchValue."%'
OR X.v_codepaclist              LIKE '%".$searchValue."%'
OR X.Supplier                   LIKE '%".$searchValue."%'
OR X.kpno                       LIKE '%".$searchValue."%'
OR X.so_no                      LIKE '%".$searchValue."%'
OR X.Styleno                    LIKE '%".$searchValue."%'
OR X.v_pono                     LIKE '%".$searchValue."%'
OR X.v_userpost                 LIKE '%".$searchValue."%'
OR X.d_insert                   LIKE '%".$searchValue."%'
OR X.v_userpost_inv             LIKE '%".$searchValue."%'
OR X.d_post_inv                 LIKE '%".$searchValue."%'
OR X.n_id                       LIKE '%".$searchValue."%'
OR X.n_idinvoiceheader          LIKE '%".$searchValue."%'
OR X.n_typeinvoice              LIKE '%".$searchValue."%'
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
$colomn = "		  X.desc_inv
				 ,X.v_noinvoicecommercial
				 ,X.v_codepaclist
				 ,X.Supplier
				 ,X.kpno
				 ,X.so_no
				 ,X.Styleno
				 ,X.v_pono
				 ,X.v_userpost
				 ,X.d_insert
				 ,X.v_userpost_inv
				 ,X.d_post_inv
				 ,X.n_id
				 ,X.n_idinvoiceheader
				 ,X.n_typeinvoice
				 ,X.n_post
				 ";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];

		$button = '';
		if($row['n_post'] != 2){
			$button .= "<a href='#' class='btn btn-primary' onclick='edit(".'"'.$row['n_id'].'"'.")' ><i class='fa fa-pencil'> </i></a>";
			$button .= "<a href='#' class='btn btn-info' onclick='send(".'"'.$row['n_idinvoiceheader'].'",'.'"INV"'.")' ><i class='fa fa-send'> </i></a>";			
			$button .=" <a href='#' class='btn btn-warning' onclick='print(".'"'.$row['n_id'].'",'.'"'.$row['n_typeinvoice'] .'"'.")' ><i class='fa fa-file-pdf-o'> </i></a>";	
		}else{
			$button .=" <a href='#' class='btn btn-warning' onclick='print(".'"'. $row['n_id'] .'",'.'"'. $row['n_typeinvoice'] .'"'.")' ><i class='fa fa-file-pdf-o'> </i></a>";				
		}	
	
	
	
   $data[] = array( 
"desc_inv"=>$row['desc_inv'],
"v_noinvoicecommercial"=>$row['v_noinvoicecommercial'],
"v_codepaclist"=>$row['v_codepaclist'],
"Supplier"=>$row['Supplier'],
"kpno"=>$row['kpno'],
"so_no"=>$row['so_no'],
"Styleno"=>$row['Styleno'],
"v_pono"=>$row['v_pono'],
"v_userpost"=>$row['v_userpost'],
"d_insert"=>$row['d_insert'],
"v_userpost_inv"=>$row['v_userpost_inv'],
"d_post_inv"=>$row['d_post_inv'],
"button"=>rawurlencode($button)
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