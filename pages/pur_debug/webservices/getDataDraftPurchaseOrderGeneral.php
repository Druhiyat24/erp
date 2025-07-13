<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = 'id'; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(SELECT 	 A.id
		,A.draftno
		,C.reqno
				,B_CANCEL.jlh_app
		,F_F.Supplier nama_supplier
		,IF(B_CANCEL.jlh_app IS NULL
			,'CANCELLED',
			IF(A.app ='W','WAITING',
				IF(A.app='A','APPROVED','CANCELED/REJECT')))status
		,G.kode_pterms
		,A.draftdate
		,A.id_supplier
		,A.id_terms
		,A.n_kurs
		,A.etd
		,A.eta
		,A.expected_date
		,A.notes
		,A.tax
		,A.jenis
		,A.ppn
		,A.pph
		,A.app
		,A.app_by
		,A.app_date
		,A.revise
		,A.username
		,A.discount
		,A.jml_pterms
		,A.id_dayterms
		,A.fg_pkp
		,A.po_over
		,A.po_close
			FROM po_header_draft A
			INNER JOIN(SELECT * FROM po_item_draft GROUP BY id_gen)B ON A.id = B.id_po_draft
			INNER JOIN(
SELECT A.id,B.jlh_app FROM po_header_draft A LEFT JOIN(
SELECT COUNT(*)jlh_app,id_po_draft FROM po_item_draft WHERE cancel = 'N' GROUP BY id_po_draft)B
ON A.id = B.id_po_draft)B_CANCEL ON B_CANCEL.id = A.id
			INNER JOIN(SELECT * FROM reqnon_header WHERE cancel_h = 'N')C ON B.id_jo = C.id
			INNER JOIN(SELECT * FROM mastersupplier WHERE tipe_sup = 'S')F_F ON A.id_supplier = F_F.Id_Supplier
			INNER JOIN(SELECT * FROM masterpterms)G ON G.id = A.id_terms
			WHERE A.jenis IN ('N') GROUP BY A.id
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (
X.draftno			LIKE'%".$searchValue."%'	
X.id                LIKE'%".$searchValue."%'
X.reqno                LIKE'%".$searchValue."%'
X.nama_supplier     LIKE'%".$searchValue."%'
X.status            LIKE'%".$searchValue."%'
X.kode_pterms       LIKE'%".$searchValue."%'
X.draftdate         LIKE'%".$searchValue."%'
X.id_supplier       LIKE'%".$searchValue."%'
X.id_terms          LIKE'%".$searchValue."%'
X.n_kurs            LIKE'%".$searchValue."%'
X.etd               LIKE'%".$searchValue."%'
X.eta               LIKE'%".$searchValue."%'
X.expected_date     LIKE'%".$searchValue."%'
X.notes             LIKE'%".$searchValue."%'
X.tax               LIKE'%".$searchValue."%'
X.jenis             LIKE'%".$searchValue."%'
X.ppn               LIKE'%".$searchValue."%'
X.pph               LIKE'%".$searchValue."%'
X.app               LIKE'%".$searchValue."%'
X.app_by            LIKE'%".$searchValue."%'
X.app_date          LIKE'%".$searchValue."%'
X.revise            LIKE'%".$searchValue."%'
X.username          LIKE'%".$searchValue."%'
X.discount          LIKE'%".$searchValue."%'
X.jml_pterms        LIKE'%".$searchValue."%'
X.id_dayterms       LIKE'%".$searchValue."%'
X.fg_pkp            LIKE'%".$searchValue."%'
X.po_over           LIKE'%".$searchValue."%'
X.po_close          LIKE'%".$searchValue."%'
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
 X.draftno
,X.id
,X.reqno
,X.nama_supplier
,X.status
,X.kode_pterms
,X.draftdate
,X.id_supplier
,X.id_terms
,X.n_kurs
,X.etd
,X.eta
,X.expected_date
,X.notes
,X.tax
,X.jenis
,X.ppn
,X.pph
,X.app
,X.app_by
,X.app_date
,X.revise
,X.username
,X.discount
,X.jml_pterms
,X.id_dayterms
,X.fg_pkp
,X.po_over
,X.po_close";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
/*   print_r($empQuery);
 die();  */
$data = array();
// echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {


$button = '';
if(ISSET($row['status_po']) &&  $row['status_po'] == 'Cancelled' ){
	$button = $row['status_po'];
}else{
	if($row['app'] == 'A' ){
/* 		$button .="                  <a href='?mod=draft_po_gen_form&id=$row[id]'
                    data-toggle='tooltip' title='View'><i class='fa fa-eye'></i>
                  </a>"; */
		$button .="<a href='pdfPOG_Draft.php?id=$row[id]'
                  data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i>
                </a>";		
	}else{

		$button .="                  <a href='?mod=draft_po_gen_form&id=$row[id]'
                    data-toggle='tooltip' title='View'><i class='fa fa-pencil'></i>

                  </a>";
	}

}

//echo $row['n_post'];


   $data[] = array(
"draftno"=>htmlspecialchars($row['draftno']),               
"draftdate"=>htmlspecialchars($row['draftdate']),
"kode_pterms"=>htmlspecialchars($row['kode_pterms']),
"reqno"=>htmlspecialchars($row['reqno']),
"notes"=>htmlspecialchars($row['notes']),
"n_kurs"=>htmlspecialchars($row['n_kurs']),
"status"=>htmlspecialchars($row['status']),
"app_by"=>htmlspecialchars($row['app_by']),
"app_date"=>htmlspecialchars($row['app_date']), //is_color
"app_by"=>htmlspecialchars($row['app_by']), 
"app_date"=>htmlspecialchars($row['app_date']), 
"nama_supplier"=>htmlspecialchars($row['nama_supplier']), 
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