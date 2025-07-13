<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
//$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = 'id'; // Column name
//$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(
SELECT MASTER.* FROM(
		 SELECT
		  		 'Y' is_po 
				,a.id_draft
				,if(id_draft ='0' AND a.app = 'W',1,0)is_old
		  		,t_it_po_cx
				,t_it_po
				,if(t_it_po_cx=t_it_po,'Cancelled','') status_po
				,UN_PO.c_id_po
				,if(c_id_po > 0,'',CONCAT(a.app_by,'(',a.app_date,')'))app_po
				,CONCAT(DRAFT.app_by,'(',DRAFT.app_date,')')app_draft
				,a.app
				,a.app_by
				,a.app_date
				,a.id
				,a.pono
				,a.podate
				,s.supplier
				,d.nama_pterms
				,tmppoit.buyer
				,a.notes
				,a.n_kurs
				,a.revise
				,tmppoit.kpno
				,tmppoit.jo_no
				,tmppoit.styleno
				,tmppoit.tqtypo
				,tmppoit.tqtybpb
				,IF( tmppoit.tqtybpb IS NOT NULL
				,IF(tmppoit.tqtybpb < tmppoit.tqtypo,'YELLOW','GREEN'),'0' )is_color
				,'X' pono_filter
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

          ) tmppoit on tmppoit.id_po=a.id
 			left join (
				Select id,app,app_by,app_date FROM po_header_draft WHERE app = 'A'
			)DRAFT ON DRAFT.id = a.id_draft 
			LEFT JOIN(
				SELECT COUNT(id_po)c_id_po,id_po FROM unapp_po WHERE 1=1 GROUP BY id_po
			)UN_PO ON a.id = UN_PO.id_po
			 where a.jenis!='N'         
          #order by podate desc 
		  
		  UNION ALL
		  /* DRAFT PO */ 
SELECT 	 
		 'N' is_po
		 ,'' id_draft
		 ,'' is_old
		,'' t_it_po_cx
		,'' t_it_po
		,IF(C_PO.c_cancel = '0' OR C_PO.c_cancel IS NULL OR C_PO.c_cancel = '','cancelled',IF(A.app='A','APPROVED',if(A.app='W','WAITING','CANCELED/REJECT'))  )status_po
		,'' c_id_po
		,'' app_po
		,'' app_draft		
		,IF(C_PO.c_cancel = '0' OR C_PO.c_cancel IS NULL OR C_PO.c_cancel = '','C',A.app)app
		,A.app_by
		,A.app_date
		,A.id
		,'' pono
		,A.draftdate podate
		,F_F.Supplier supplier
		,G.nama_pterms
		,F.Supplier buyer
		,A.notes
		,A.n_kurs
		,A.revise
		,E.kpno
		,'' jo_no
		,E.styleno
		,'' tqtypo
		,'' tqtybpb 
		,'' is_color
		,IF(C_PO.c_cancel = '0' OR C_PO.c_cancel IS NULL OR C_PO.c_cancel = '','X','')pono_filter
			FROM po_header_draft A
			INNER JOIN(SELECT * FROM po_item_draft GROUP BY id_jo,id)B ON A.id = B.id_po_draft
			INNER JOIN(
SELECT A.id,B.jlh_app FROM po_header_draft A LEFT JOIN(
SELECT COUNT(*)jlh_app,id_po_draft FROM po_item_draft WHERE cancel = 'N' GROUP BY id_po_draft)B
ON A.id = B.id_po_draft)B_CANCEL ON B_CANCEL.id = A.id
			INNER JOIN(SELECT * FROM jo_det WHERE cancel = 'N')C ON B.id_jo = C.id_jo
			INNER JOIN(SELECT * FROM so )D ON C.id_so = D.id
			INNER JOIN(SELECT * FROM act_costing)E ON E.id = D.id_cost
			INNER JOIN(SELECT * FROM mastersupplier WHERE tipe_sup = 'C')F ON E.id_buyer = F.Id_Supplier
			INNER JOIN(SELECT * FROM mastersupplier WHERE tipe_sup = 'S')F_F ON A.id_supplier = F_F.Id_Supplier
			INNER JOIN(SELECT * FROM masterpterms)G ON G.id = A.id_terms
			LEFT JOIN(SELECT COUNT(cancel)c_cancel,id_po_draft FROM po_item_draft WHERE cancel ='N' GROUP BY id_po_draft )C_PO ON A.id =C_PO.id_po_draft
			WHERE A.jenis IN ('M','P')	AND A.app !='A'
			GROUP BY A.id
	
			)MASTER
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (
			 X.id					LIKE'%".$searchValue."%'
			OR X.is_po              LIKE'%".$searchValue."%'
			OR X.pono               LIKE'%".$searchValue."%'    
			OR X.revise            	LIKE'%".$searchValue."%'
			OR X.podate             LIKE'%".$searchValue."%'    	
			OR X.supplier           LIKE'%".$searchValue."%'       	
			OR X.nama_pterms        LIKE'%".$searchValue."%'      	
			OR X.buyer              LIKE'%".$searchValue."%'
			OR X.kpno               LIKE'%".$searchValue."%'     	
			OR X.styleno            LIKE'%".$searchValue."%'       	
			OR X.notes              LIKE'%".$searchValue."%'
			OR X.n_kurs             LIKE'%".$searchValue."%'
			OR X.app		        LIKE'%".$searchValue."%'
			OR X.app_by		        LIKE'%".$searchValue."%'
			OR X.app_date	        LIKE'%".$searchValue."%'
			OR X.is_color           LIKE'%".$searchValue."%'
			OR X.status_po          LIKE'%".$searchValue."%'
			OR X.app_po             LIKE'%".$searchValue."%'
			OR X.app_draft          LIKE'%".$searchValue."%'
			OR X.c_id_po            LIKE'%".$searchValue."%'
			OR X.is_old             LIKE'%".$searchValue."%'
			OR X.pono_filter        LIKE'%".$searchValue."%'
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
$colomn = 	"	
			 X.id
			,X.is_po
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
			,X.is_color
			,X.status_po
			,X.app_po
			,X.app_draft
			,X.c_id_po
			,X.is_old
			,X.pono_filter
			";
$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery. " ORDER BY X.pono_filter ASC,X.podate DESC limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
// print_r($empQuery);
// die();
$data = array();
 //echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
$button = '';
if($row['is_po']  == 'N' ){
		if($row['app'] =='C' ){
			$button .="Cancelled";
			$row['is_color'] = 'RED';
			
		}else{
			$button .="                  <a href='?mod=draft_po_bW_form&id=$row[id]'
                    data-toggle='tooltip' title='View'><i class='fa fa-pencil'></i>

                  </a>";   
				$button .="                  <a href='?mod=33z&id=$row[id]'
		
							data-toggle='tooltip' title='View'><i class='fa fa-pencil-square'></i>
		
						</a>";                                 
		$button .="<a href='pdfPO_Draft.php?id=$row[id]'
                  data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i>
                </a>";				
		}
	
				  
	
	
}else{
	if(ISSET($row['status_po'])){
		if($row['status_po'] == 'Cancelled' ){
			$button = $row['status_po'];
			$row['is_color'] = 'RED';
		}else{
			if($row['app'] == 'A' ){
				$button .="                  <a href='?mod=3z&id=$row[id]'
		
							data-toggle='tooltip' title='View'><i class='fa fa-eye'></i>
		
						</a>";
				$button .="<a href='pdfPO.php?id=$row[id]'
		
						data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i>
		
						</a>";		
			}else{
				if($row['c_id_po'] > 0){
					$button .="<a href='?mod=3e&id=$row[id]'
								data-toggle='tooltip' title='View'><i class='fa fa-pencil'></i>
							</a>";
				}
				
				if($row['is_old'] =='1' ){
					$button .="<a href='?mod=3e&id=$row[id]'
								data-toggle='tooltip' title='View'><i class='fa fa-pencil'></i>
							</a>";					
				}
					if($row['app'] =='W' ){
					$button .="<a href='?mod=33x&id=$row[id]'
								data-toggle='tooltip' title='View'><i class='fa fa-pencil-square'></i>
							</a>";					
				}					
			}
			$button .="<a href='?mod=3en&id=$row[id]'
					data-toggle='tooltip' title='Update Notes'><i class='fa fa-sticky-note-o'></i>
					</a>";
		}	
	}	
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
exit();
?>