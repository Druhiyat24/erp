<?php 
include __DIR__ .'/../../../include/conn.php';
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
//print_r($_POST);
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = "id"; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(SELECT a.*,s.goods_code,s.itemdesc itemdesc,supplier

            ,count(*) t_bpb_it,sum(if(isnull(brh.bpbno),0,1)) t_bpb_rl 

            FROM bpb a inner join masteritem s on a.id_item=s.id_item 

            inner join po_item poi on a.id_po_item=poi.id  

            inner join mastersupplier ms on a.id_supplier=ms.id_supplier 

            left join bpb_roll_h brh on a.bpbno=brh.bpbno and a.id_jo=brh.id_jo and a.id_item=brh.id_item   

            where  left(a.bpbno,1) in ('A','F','B') and left(a.bpbno,2)!='FG'  and a.id_po_item!='' and a.id_jo!='' 

            GROUP BY a.bpbno ASC order by bpbdate desc) AS X";


### Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (
   		X.bpbno_int			LIKE'%".$searchValue."%'
		OR X.bpbdate		LIKE'%".$searchValue."%'
		OR X.pono			LIKE'%".$searchValue."%'
		OR X.supplier		LIKE'%".$searchValue."%'
		OR X.invno			LIKE'%".$searchValue."%'
		OR X.jenis_dok	    LIKE'%".$searchValue."%'
		OR X.nomor_aju		LIKE'%".$searchValue."%'
		OR X.tanggal_aju	LIKE'%".$searchValue."%'
		OR X.nomor_kk_bc	LIKE'%".$searchValue."%'
		OR X.qty_reject		LIKE'%".$searchValue."%'
		OR X.username		LIKE'%".$searchValue."%'
		OR X.confirm_by		LIKE'%".$searchValue."%'
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
		  ,X.bpbno
		  ,X.bpbno_int
		  ,X.bpbdate
		  ,X.pono
		  ,X.supplier
		  ,X.invno
		  ,X.jenis_dok
		  ,X.nomor_aju
		  ,X.tanggal_aju
		  ,X.nomor_kk_bc
		  ,X.qty_reject
		  ,X.username
		  ,X.last_date_bpb original_date
		  ,X.confirm_by";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
 //echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
	// echo '123';die();
//echo $row['n_post'];
		$button = '';
		if(!ISSET($row['confirm_by'])/* $row['confirm_by'] === '' */){
			$button .="<a href='?mod=26e&mode=Bahan_Baku&noid=$row[bpbno]' data-toggle='tooltip' title='Ubah'><i class='fa fa-pencil'></i></a>";
			$button .="<a href='pdfPOG.php?id=$row[bpbno]' data-toggle='tooltip' title='Attach File'><i class='fa fa-paperclip'></i></a>";	
		}
		else{
			$button .="<a href='cetaksj.php?mode=In&noid=$row[bpbno]' data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i></a>";
			$button .="<a href='pdfPOG.php?noid=$row[bpbno]' data-toggle='tooltip' title='Attach File'><i class='fa fa-paperclip'></i></a>";
		}

			$data[] = array(
				"bpbno_int"=>htmlspecialchars($row['bpbno_int']), //original_date
				"bpbdate"=>htmlspecialchars($row['bpbdate']),
				"pono"=>htmlspecialchars($row['pono']),
				"supplier"=>htmlspecialchars($row['supplier']),
				"invno"=>htmlspecialchars($row['invno']),
				"jenis_dok"=>htmlspecialchars($row['jenis_dok']),
				"nomor_aju"=>htmlspecialchars($row['nomor_aju']),
				"tanggal_aju"=>htmlspecialchars($row['tanggal_aju']),
				"nomor_kk_bc"=>htmlspecialchars($row['nomor_kk_bc']),
				"qty_reject"=>htmlspecialchars($row['qty_reject']),
				"username"=>htmlspecialchars($row['username']),
				"confirm_by"=>htmlspecialchars($row['confirm_by']),
				"original_date"=>htmlspecialchars($row['original_date']),
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