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
/*
print_r($_POST);
die();  */
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
//$columnIndex = $_POST['order'][0]['column']; // Column index
//$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
//$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
SELECT MASTER.* FROM (
SELECT  
		'Y' is_po
		,a.id_draft
		,if(id_draft ='0' AND a.app = 'W',1,0)is_old		
		,a.app
		,a.app_by
		,a.app_date
		,a.id
		,pono
		,podate
		,supplier
        ,nama_pterms
		,n_kurs
		,tmppoit.buyer
		,tmppoit.t_row
		,tmppoit.t_rows_cancel 
		,if(t_row > 0
			,if(t_row = t_rows_cancel,'Cancelled','')
			,'')status_po
		,UN_PO.c_id_po
				,CONCAT(a.app_by,'(',a.app_date,')')app_po
				,CONCAT(DRAFT.app_by,'(',DRAFT.app_date,')')app_draft		
          from po_header a inner join 
          mastersupplier s on a.id_supplier=s.id_supplier inner join 
          masterpterms d on a.id_terms=d.id
          inner join 
          (select poit.id_jo,poit.id_po,group_concat(distinct reqno) buyer,
            count(*) t_row,sum(if(cancel='Y',1,0)) t_rows_cancel from po_item poit 
            inner join reqnon_header rnh on poit.id_jo=rnh.id  group by poit.id_po) 
          tmppoit on tmppoit.id_po=a.id 
 			left join (
				Select id,app,app_by,app_date FROM po_header_draft WHERE app = 'A'
			)DRAFT ON DRAFT.id = a.id_draft 
			LEFT JOIN(
				SELECT COUNT(id_po)c_id_po,id_po FROM unapp_po WHERE 1=1 GROUP BY id_po
			)UN_PO ON a.id = UN_PO.id_po			
          where a.jenis='N'
		 UNION ALL 
	SELECT 	
		 'N' is_po 
		 ,'' id_draft
		 ,'' is_old
		,IF(C_PO.c_cancel = '0' OR C_PO.c_cancel IS NULL OR C_PO.c_cancel = '','C',A.app)app
		,A.app_by
		,A.app_date
		,A.id
		,'' pono
		,A.draftdate podate
		,F_F.Supplier supplier
		,G.nama_pterms
		,A.n_kurs
		,GROUP_CONCAT(C.reqno) buyer
		,'' t_row
		,'' t_rows_cancel
		,IF(C_PO.c_cancel = '0' OR C_PO.c_cancel IS NULL OR C_PO.c_cancel = '','cancelled',IF(A.app='A','APPROVED',if(A.app='W','WAITING','CANCELED/REJECT'))  )status_po
		,'' c_id_po
		,'' app_po
		,IF(C_PO.c_cancel = '0' OR C_PO.c_cancel IS NULL OR C_PO.c_cancel = '','C',A.app)app_draft	
			FROM po_header_draft A
			INNER JOIN(SELECT * FROM po_item_draft GROUP BY id_jo,id)B ON A.id = B.id_po_draft
			INNER JOIN(
SELECT A.id,B.jlh_app FROM po_header_draft A LEFT JOIN(
SELECT COUNT(*)jlh_app,id_po_draft FROM po_item_draft WHERE cancel = 'N' GROUP BY id_po_draft)B
ON A.id = B.id_po_draft)B_CANCEL ON B_CANCEL.id = A.id
			INNER JOIN(SELECT * FROM reqnon_header WHERE cancel_h = 'N')C ON B.id_jo = C.id
			INNER JOIN(SELECT * FROM mastersupplier WHERE tipe_sup = 'S')F_F ON A.id_supplier = F_F.Id_Supplier
			INNER JOIN(SELECT * FROM masterpterms)G ON G.id = A.id_terms
			LEFT JOIN(SELECT COUNT(cancel)c_cancel,id_po_draft FROM po_item_draft WHERE cancel ='N' GROUP BY id_po_draft )C_PO ON A.id =C_PO.id_po_draft
			WHERE A.jenis IN ('N') AND A.app != 'A' GROUP BY A.id
)MASTER
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (
X.id  				LIKE'%".$searchValue."%'
OR X.pono           LIKE'%".$searchValue."%'
OR X.podate        	LIKE'%".$searchValue."%'
OR X.supplier       LIKE'%".$searchValue."%'
OR X.nama_pterms    LIKE'%".$searchValue."%'
OR X.buyer          LIKE'%".$searchValue."%'
OR X.n_kurs	     	LIKE'%".$searchValue."%'
OR X.status_po	    LIKE'%".$searchValue."%'
OR X.app			LIKE'%".$searchValue."%'
OR X.app_by			LIKE'%".$searchValue."%'
OR X.app_date		LIKE'%".$searchValue."%'
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
			,X.podate                 	
			,X.supplier                  	
			,X.nama_pterms              	
			,X.buyer                   
			,X.n_kurs	
			,X.app			 
			,X.app_by		
			,X.status_po								
			,X.app_date	
			,X.is_po
			,X.app_po
			,X.app_draft	
			,X.c_id_po
			,X.is_old			
			";
$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." ORDER BY X.podate DESC limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
	$button = '';
if($row['is_po']  == 'N' ){
		if($row['app'] =='C' ){
			$button .="Cancelled";
		}else{
			$button .="                  <a href='?mod=draft_po_gen_form&id=$row[id]'
                    data-toggle='tooltip' title='View'><i class='fa fa-pencil'></i>

                  </a>";
		$button .="<a href='pdfPOG_Draft.php?id=$row[id]'
                  data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i>
                </a>";				
		}
	
				  
	
	
}else{
	if(ISSET($row['status_po'])){
		if($row['status_po'] == 'Cancelled' ){
			$button = $row['status_po'];
		}else{
			if($row['app'] == 'A' ){
				$button .="                    <a href='?mod=9z&id=$row[id]'
		
							data-toggle='tooltip' title='View'><i class='fa fa-eye'></i>
		
						</a>";
				$button .="<a href='pdfPOG.php?id=$row[id]'
		
						data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i>
		
						</a>";		
			}else{
				if($row['c_id_po'] > 0){
					$button .="<a href='?mod=9e&id=$row[id]'
								data-toggle='tooltip' title='View'><i class='fa fa-pencil'></i>
							</a>";
				}
				
				if($row['is_old'] =='1' ){
					$button .="<a href='?mod=9e&id=$row[id]'
								data-toggle='tooltip' title='View'><i class='fa fa-pencil'></i>
							</a>";					
				}
				
			}
			$button .="<a href='?mod=9en&id=$row[id]'
					data-toggle='tooltip' title='Update Notes'><i class='fa fa-sticky-note-o'></i>
					</a>";
		}	
	}	
}
	
	
////echo $row['n_post'];
//$button = '';
///* echo $row['status_po'];
//die(); */
//if($row['status_po'] == 'Cancelled' ){
//	$button = $row['status_po'];
//}else{
//	if($row['is_po'] == 'Y' ){
//		if($row['app'] == 'A' ){
//			$button .="<a href='?mod=9z&id=$row[id]'
//	
//						data-toggle='tooltip' title='View'><i class='fa fa-eye'></i>
//	
//					</a>";
//			$button .="<a href='pdfPOG.php?id=$row[id]'
//					data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i>
//					</a>";				  
//						
//		}else{
//			$button .="<a href='?mod=9e&id=$row[id]'
//	
//						data-toggle='tooltip' title='edit'><i class='fa fa-pencil'></i>
//	
//					</a>";
//	
//		}		
//	}else{
//		if($row['app'] == 'A' ){
//	/* 		$button .="                  <a href='?mod=draft_po_gen_form&id=$row[id]'
//						data-toggle='tooltip' title='View'><i class='fa fa-eye'></i>
//					</a>"; */
//			$button .="<a href='pdfPOG_Draft.php?id=$row[id]'
//					data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i>
//					</a>";		
//		}else{
//			$button .="<a href='?mod=draft_po_gen_form&id=$row[id]'
//						data-toggle='tooltip' title='View'><i class='fa fa-pencil'></i>
//					</a>";
//			$button .="<a href='pdfPOG_Draft.php?id=$row[id]'
//					data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i>
//					</a>";						
//		}
//	}
//}
   $data[] = array(
"pono"=>htmlspecialchars($row['pono']),        
"podate"=>htmlspecialchars($row['podate']),
"supplier"=>htmlspecialchars($row['supplier']),
"nama_pterms"=>htmlspecialchars($row['nama_pterms']),
"buyer"=>htmlspecialchars($row['buyer']),
"n_kurs"=>htmlspecialchars($row['n_kurs']),
"app"=>htmlspecialchars($row['app']),
"app_by"=>htmlspecialchars($row['app_by']),
"app_date"=>htmlspecialchars($row['app_date']),
"app_po"=>htmlspecialchars($row['app_po']),
"app_draft"=>htmlspecialchars($row['app_draft']),
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