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
SELECT a.*

		,supplier

		,IF(a.n_typeinvoice='1', 'LOCAL', 'EXPORT')description

		,W.n_id id_ic 

		,ID.id_so_det

		,ACT.kpno

		FROM invoice_header a inner join 

          mastersupplier ms on a.id_buyer=ms.id_supplier

		  LEFT JOIN invoice_commercial W ON W.n_idinvoiceheader = a.id

		LEFT JOIN invoice_detail ID ON ID.id_inv = a.id

		LEFT JOIN so_det SOD ON SOD.id = ID.id_so_det

		LEFT JOIN so SO ON SOD.id_so = SO.id

		LEFT JOIN act_costing ACT ON ACT.id = SO.id_cost	
		

		WHERE 1=1 AND a.n_post = '0' AND
		a.n_typeinvoice IN  ('1','2')
		
		GROUP BY a.id ORDER BY a.d_insert desc
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (
		X.description      		LIKE'%".$searchValue."%'
		OR X.v_codepaclist    		LIKE'%".$searchValue."%'
		OR X.kpno             		LIKE'%".$searchValue."%'
		OR X.Supplier         		LIKE'%".$searchValue."%'
		OR X.d_insert         		LIKE'%".$searchValue."%'
		OR X.date_paclist	    		LIKE'%".$searchValue."%'
		OR X.create_packing_list	    LIKE'%".$searchValue."%'

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
		X.id
		,X.description
		,X.v_codepaclist
		,X.kpno
		,X.Supplier
		,X.d_insert
		,X.date_paclist
		,X.create_packing_list
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];
$button = '';
$app = 'APP';
$blo = 'BLO';
		$button .="<a href='webservices/postPackInv.php?id=$row[id]&part=PL&type_invoice=$row[n_typeinvoice]&action=APPROVE&src=APP_INV' class='btn btn-primary'
                    data-toggle='tooltip'  title='Approve'><i class='fa fa-check'></i>
                  </a>";
		$button .="<a href='webservices/postPackInv.php?id=$row[id]&part=PL&type_invoice=$row[n_typeinvoice]&action=BLOCK&src=APP_INV' class='btn btn-danger'
                  data-toggle='tooltip' title='Block'><i class='fa fa-ban'></i>
                </a>";			

            $button .= " <a class='btn btn-warning btn-s' target='_blank' href='PdfInvoice.php?id=$row[id]&type=$row[description]' 

                data-toggle='tooltip' title='Cetak'><i class='fa fa-print'></i></a></td>"; 			  

									
				
				
				
				
   $data[] = array(
"description"=>htmlspecialchars($row['description']),        
"v_codepaclist"=>htmlspecialchars($row['v_codepaclist']),
"kpno"=>htmlspecialchars($row['kpno']),
"Supplier"=>htmlspecialchars($row['Supplier']),
"d_insert"=>htmlspecialchars($row['d_insert']),
"date_paclist"=>htmlspecialchars($row['date_paclist']),
"create_packing_list"=>htmlspecialchars($row['create_packing_list']),
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