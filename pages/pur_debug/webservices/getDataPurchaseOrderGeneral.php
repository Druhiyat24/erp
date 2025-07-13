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
$table = "(
		  select t_it_po_cx,t_it_po, if(t_it_po_cx=t_it_po,'Cancelled','') status_po,a.app,a.app_by,a.app_date,a.id,a.pono,a.podate,s.supplier,

          d.nama_pterms,tmppoit.buyer,a.notes,a.n_kurs,a.revise,tmppoit.kpno,tmppoit.jo_no,tmppoit.styleno,

          tmppoit.tqtypo,tmppoit.tqtybpb,

		  IF( tmppoit.tqtybpb IS NOT NULL,
				IF(tmppoit.tqtybpb < tmppoit.tqtypo,'YELLOW','RED'),'0' )is_color
		
		  
          from po_header a inner join 

          mastersupplier s on a.id_supplier=s.id_supplier inner join 

          masterpterms d on a.id_terms=d.id

          inner join 

          (select group_concat(distinct jo_no) jo_no,ac.kpno,ac.styleno,poit.id_jo,poit.id_po,ms.supplier buyer,count(*) t_it_po,

            sum(if(poit.cancel='Y',1,0)) t_it_po_cx,sum(poit.qty) tqtypo,

            tmpbpb.tqtybpb from po_item poit 

            inner join jo_det jod on jod.id_jo=poit.id_jo 

            inner join jo on jo.id=jod.id_jo  

            inner join so on jod.id_so=so.id 

            inner join act_costing ac on so.id_cost=ac.id 

            inner join mastersupplier ms on ac.id_buyer=ms.id_supplier 

            left join 

            (select id_po_item,sum(qty) tqtybpb from bpb group by id_po_item)

            tmpbpb on poit.id=tmpbpb.id_po_item 

            -- where poit.cancel='N'   

            group by poit.id_po

          ) tmppoit on tmppoit.id_po=a.id where a.jenis!='N' 
          order by podate desc 
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	
X.id  			LIKE'%".$searchValue."%'
OR X.pono           LIKE'%".$searchValue."%'
OR X.podate        LIKE'%".$searchValue."%'
OR X.supplier       LIKE'%".$searchValue."%'
OR X.nama_pterms    LIKE'%".$searchValue."%'
OR X.buyer          LIKE'%".$searchValue."%'
OR X.n_kurs	     LIKE'%".$searchValue."%'
OR X.status_po	     LIKE'%".$searchValue."%'
OR X.app			 LIKE'%".$searchValue."%'
OR X.app_by			 LIKE'%".$searchValue."%'
OR X.app_date			 LIKE'%".$searchValue."%'

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
			,X.pono                   
			,X.revise            	
			,X.podate                 	
			,X.supplier                  	
			,X.nama_pterms              	
			,X.buyer
			,X.kpno                    	
			,X.styleno                   	
			,X.notes
			,X.n_kurs
			,X.app		
			,X.app_by		
			,X.app_date	
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
 // print_r($empRecords);
// die();
$data = array();
// echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {


$button = '';
if($row['status_po'] == 'Cancelled' ){
	$button = $row['status_po'];
}else{
	if($row['app'] == 'A' ){
		$button .="                  <a href='?mod=3z&id=$row[id]'

                    data-toggle='tooltip' title='View'><i class='fa fa-eye'></i>

                  </a>";
		$button .="<a href='pdfPO.php?id=$row[id]'

                  data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i>

                </a>";		
	}else{

		$button .="                  <a href='?mod=3e&id=$row[id]'

                    data-toggle='tooltip' title='View'><i class='fa fa-pencil'></i>

                  </a>";
	}
	$button .="<a href='?mod=3en&id=$data[id]'
              data-toggle='tooltip' title='Update Notes'><i class='fa fa-sticky-note-o'></i>
              </a>";
}

//echo $row['n_post'];


   $data[] = array(
"pono"=>htmlspecialchars($row['pono']),               
"revise"=>htmlspecialchars($row['revise']),
"podate"=>htmlspecialchars($row['podate']),
"supplier"=>htmlspecialchars($row['supplier']),
"nama_pterms"=>htmlspecialchars($row['nama_pterms']),
"buyer"=>htmlspecialchars($row['buyer']),
"kpno"=>htmlspecialchars($row['kpno']),
"styleno"=>htmlspecialchars($row['styleno']),
"notes"=>htmlspecialchars($row['notes']),
"n_kurs"=>htmlspecialchars($row['n_kurs']),
"app"=>htmlspecialchars($row['app']), //is_color
"app_by"=>htmlspecialchars($row['app_by']), 
"app_date"=>htmlspecialchars($row['app_date']), 
"is_color"=>htmlspecialchars($row['is_color']),
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