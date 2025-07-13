<?php 
	// function d_from($from){
	// 	$d_from = explode("/",$from."/01");
	// 	$d_from = $d_from[1]."-".$d_from[0]."-".$d_from[2];
	// 	return $d_from;
	// }
	// function d_to($to){
	// 	$d_to = explode("/",$to."/01");
	// 	$d_to = $d_to[1]."-".$d_to[0]."-".$d_to[2];
	// 	$d_to = date("Y-m-d", strtotime("1 month",strtotime($d_to)));
	// 	$d_to = date("Y-m-d", strtotime("-1 days",strtotime($d_to)));
	// 	return $d_to;
	// }



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
$table = "(SELECT JOD.id_so,POI.id_jo, 
ACT.id,ACT.kpno no_ws,ACT.styleno,SO.qty_pr,MI.itemdesc,SOD.deldate_det,SOD.min_deldate_det FROM act_costing ACT  
	
		LEFT JOIN(SELECT id
				,id_cost
				,buyerno
				,so_type
				,so_no
				,so_date
				,revise
				,qty qty_pr
				,unit
				,curr
				,fob
				,nm_file
				,username
				,tax
				,id_season
				,cancel_h
				,status_finish
				,so_no_int
				,id_terms
				,jml_pterms
				,d_insert
				FROM so
				)SO ON SO.id_cost = ACT.id
	
LEFT JOIN(SELECT id
				,id_so
				,dest
				,n_id_dest
				,color
				,size
				,qty
				,qty_add
				,unit
				,price
				,sku
				,barcode
				,notes
				,deldate_det
				,cancel
				,DATE_SUB(deldate_det, INTERVAL 50 DAY)min_deldate_det
				FROM so_det GROUP BY id_so) SOD 
				ON SOD.id_so = SO.id
				
LEFT JOIN(SELECT id
				,id_so
				,id_jo
				,cancel
				FROM jo_det
				)JOD ON JOD.id_so = SO.id

LEFT JOIN(SELECT id
				,jo_no
				,jo_date
				,revise
				,username
				,app
				,app_by
				,app_date
				,attach_file
				,konfirmasi_1
				,konfirmasi_2
				,konfirmasi_3
				,d_insert
				FROM jo
				)JO ON JO.id = JOD.id_jo
						

		LEFT JOIN(SELECT id
					,id_po
					,id_jo
					,id_gen
					,qty
					,unit
					,curr
					,price
					,cancel
		FROM po_item
				) POI ON  POI.id_jo = JO.id
	
		
	LEFT JOIN(SELECT id_item
				,mattype
				,n_code_category
				,matclass
				,itemdesc
				,color
				,size
				,stock_card
				,goods_code
				,non_aktif
				,goods_code2
				,brand
				,file_gambar
				,sn
				,thn_beli
				,hscode
				,id_gen
				,notes
				,tipe_item
				,min_stock
				,base_curr
				,base_price
				,base_supplier
				,add_info
				,id_item_odo
				,it_inv
				,moq
				,tipe_mut
				FROM masteritem
				) MI ON MI.id_gen = POI.id_gen
				
LEFT JOIN(SELECT id
				,pono
				,podate
				,id_supplier
				,id_terms
				,etd
				,eta
				,expected_date
				,notes
				,n_kurs
				,tax
				,jenis
				,ppn
				,pph
				,app
				,app_by
				,app_date
				,revise
				,username
				,discount
				,jml_pterms
				,id_dayterms
				,pono_int
				,fg_pkp
				,po_over
				,n_kurs2
				FROM po_header
				)POH ON POI.id_po = POH.id




)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (
   X.id     		 LIKE'%".$searchValue."%'
OR X.no_ws           LIKE'%".$searchValue."%'
OR X.styleno        LIKE'%".$searchValue."%'
OR X.itemdesc       LIKE'%".$searchValue."%'
OR X.qty_pr       LIKE'%".$searchValue."%'
OR X.deldate_det    LIKE'%".$searchValue."%'
OR X.min_deldate_det    LIKE'%".$searchValue."%'


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
			 X.no_ws                 	
			,X.styleno  
			,X.itemdesc                	
			,X.qty_pr              	
			,X.deldate_det 
			,X.min_deldate_det                  			 
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];
$button = '';
if($row['status_po'] === 'Cancelled' ){
	$button = $row['status_po'];
}else{
	if($row['app'] == 'A' ){
		$button .="<a href='?mod=9z&id=$data[id]'

                    data-toggle='tooltip' title='View'><i class='fa fa-eye'></i>

                  </a>";
		$button .="<a href='pdfPOG.php?id=$data[id]'
                  data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i>
                </a>";				  
                   	
	}else{
		$button .="<a href='?mod=9z&id=$data[id]'

                    data-toggle='tooltip' title='View'><i class='fa fa-eye'></i>

                  </a>";

	}
}



   $data[] = array(
"no_ws"=>htmlspecialchars($row['no_ws']),        
"styleno"=>htmlspecialchars($row['styleno']),
"itemdesc"=>htmlspecialchars($row['itemdesc']),
"qty_pr"=>htmlspecialchars($row['qty_pr']),
"deldate_det"=>htmlspecialchars($row['deldate_det']),
"min_deldate_det"=>htmlspecialchars($row['min_deldate_det']),

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