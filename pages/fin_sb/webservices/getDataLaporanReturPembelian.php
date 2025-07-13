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
			  SELECT mi.goods_code
                , mi.itemdesc
                , mi.matclass
                , ac.kpno 
                , mst.Styleno
                , s.so_no
                , b.bpbno_int
                , b.bpbdate
                , b.pono
                , b.bcno
                , b.bcdate
                , b.no_fp
                , b.tgl_fp
                , ms.Supplier
                , ms.supplier_code
                , ms.short_name
                , j.jo_no
                , j.jo_date
                , j.username
                , poi.qty AS qty_po_item
                , b.qty AS qty_bpb
                , ROUND(poi.qty-b.qty,2)qty_outstanding
                , b.qty
                , b.unit
                , b.price
                , b.curr
                , (b.price*b.qty)dpp
                , poh.ppn
                , poh.podate
                , ((b.price*b.qty)+((poh.ppn/100)*(b.price*b.qty)))after_ppn
                , byr1.Supplier AS buyer
                , byr1.supplier_code AS byr_code
				, b.invno

                FROM fin_journal_h fjh INNER JOIN (SELECT bpbno_int, 
                bpbdate, 
                id_supplier, 
                id_item,
                id_jo,
                id_po_item,
                qty,
                price,
                unit,
                curr,
                invno, 
                pono, 
                bcno, 
                bcdate,
                no_fp,
                tgl_fp

                FROM bpb WHERE jenis_dok='BC 4.0' AND id_jo!='')b ON b.bpbno_int=fjh.reff_doc

                INNER JOIN mastersupplier ms ON ms.Id_Supplier=b.id_supplier

                INNER JOIN masteritem mi ON mi.id_item=b.id_item

                INNER JOIN jo j ON j.id=b.id_jo

                INNER JOIN jo_det jod ON jod.id_jo=j.id

                INNER JOIN so s ON s.id=jod.id_so

                INNER JOIN so_det sod ON sod.id_so=s.id

                INNER JOIN act_costing ac ON ac.id=s.id_cost


                INNER JOIN (SELECT Supplier,
                Id_Supplier,
                tipe_sup,
                supplier_code
                FROM mastersupplier WHERE area='L' AND tipe_sup='C')byr1 ON byr1.Id_Supplier=ac.id_buyer 
                
                INNER JOIN po_item poi ON poi.id=b.id_po_item

                INNER JOIN po_header poh ON poh.id=poi.id_po

                INNER JOIN masterstyle mst ON mst.id_so_det=sod.id

                WHERE 1=1 AND (fjh.date_journal >='$d_from' AND fjh.date_journal <='$d_to' ) AND fjh.fg_post='2' AND fjh.type_journal='2' AND ms.area='L'

                ORDER BY b.bpbdate DESC
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	X.goods_code     =  LIKE'%".$searchValue."%'
	X.itemdesc       =  LIKE'%".$searchValue."%'
	X.matclass       =  LIKE'%".$searchValue."%'
	X.kpno           =  LIKE'%".$searchValue."%'
	X.Styleno        =  LIKE'%".$searchValue."%'
	X.supplier_code  =  LIKE'%".$searchValue."%'
	X.Supplier       =  LIKE'%".$searchValue."%'
	X.byr_code       =  LIKE'%".$searchValue."%'
	X.buyer          =  LIKE'%".$searchValue."%'
	X.bcno           =  LIKE'%".$searchValue."%'
	X.bcdate         =  LIKE'%".$searchValue."%'
	X.bpbno_int      =  LIKE'%".$searchValue."%'
	X.bpbdate        =  LIKE'%".$searchValue."%'
	X.pono           =  LIKE'%".$searchValue."%'
	X.podate         =  LIKE'%".$searchValue."%'
	X.invno			 =	LIKE'%".$searchValue."%'				
	X.qty_po_item    =  LIKE'%".$searchValue."%'
	X.qty_bpb        =  LIKE'%".$searchValue."%'
	X.qty_outstanding=  LIKE'%".$searchValue."%'
	X.jo_no          =  LIKE'%".$searchValue."%'
	X.jo_date        =  LIKE'%".$searchValue."%'
	X.username       =  LIKE'%".$searchValue."%'
	X.so_no          =  LIKE'%".$searchValue."%'
	X.no_fp          = 	LIKE'%".$searchValue."%'
	X.tgl_fp         =  LIKE'%".$searchValue."%'
	X.unit           =  LIKE'%".$searchValue."%'
	X.qty            =  LIKE'%".$searchValue."%'
	X.price          =  LIKE'%".$searchValue."%'
	X.dpp            =  LIKE'%".$searchValue."%'
	X.ppn            =  LIKE'%".$searchValue."%'
	X.after_ppn      =  LIKE'%".$searchValue."%'

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
				X.goods_code     
				,X.itemdesc       
				,X.matclass       
				,X.kpno           
				,X.Styleno        
				,X.supplier_code  
				,X.Supplier       
				,X.byr_code       
				,X.buyer          
				,X.bcno           
				,X.bcdate         
				,X.bpbno_int      
				,X.bpbdate        
				,X.pono           
				,X.podate         
				,X.qty_po_item    
				,X.qty_bpb        
				,X.qty_outstanding
				,X.jo_no          
				,X.jo_date        
				,X.username       
				,X.so_no 
				,X.invno				
				,X.no_fp          
				,X.tgl_fp         
				,X.unit           
				,X.qty            
				,X.price          
				,X.dpp            
				,X.ppn            
				,X.after_ppn    
				";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];


   $data[] = array(
"goods_code"=>htmlspecialchars($row['goods_code']),
"itemdesc"=>htmlspecialchars($row['itemdesc']),
"matclass"=>htmlspecialchars($row['matclass']),
"kpno"=>htmlspecialchars($row['kpno']),
"Styleno"=>htmlspecialchars($row['Styleno']),
"supplier_code"=>htmlspecialchars($row['supplier_code']),
"Supplier"=>htmlspecialchars($row['Supplier']),
"byr_code"=>htmlspecialchars($row['byr_code']),
"buyer"=>htmlspecialchars($row['buyer']),
"bcno"=>htmlspecialchars($row['bcno']),
"bcdate"=>htmlspecialchars($row['bcdate']),
"bpbno_int"=>htmlspecialchars($row['bpbno_int']),
"bpbdate"=>htmlspecialchars($row['bpbdate']),
"pono"=>htmlspecialchars($row['pono']),
"podate"=>htmlspecialchars($row['podate']),
"qty_po_item"=>htmlspecialchars($row['qty_po_item']),
"qty_bpb"=>htmlspecialchars($row['qty_bpb']),
"qty_outstanding"=>htmlspecialchars($row['qty_outstanding']),
"jo_no"=>htmlspecialchars($row['jo_no']),
"jo_date"=>htmlspecialchars($row['jo_date']),
"username"=>htmlspecialchars($row['username']),
"so_no"=>htmlspecialchars($row['so_no']),
"invno"=>htmlspecialchars($row['invno']),
"no_fp"=>htmlspecialchars($row['no_fp']),
"tgl_fp"=>htmlspecialchars($row['tgl_fp']),
"unit"=>htmlspecialchars($row['unit']),
"qty"=>htmlspecialchars($row['qty']),
"price"=>htmlspecialchars(number_format((float)$row['price'], 2, '.', ',')),
"dpp"=>htmlspecialchars(number_format((float)$row['dpp'], 2, '.', ',')),
"ppn"=>htmlspecialchars(number_format((float)$row['ppn'], 2, '.', ',')),
"after_ppn"=>htmlspecialchars(number_format((float)$row['after_ppn'], 2, '.', ',')),


/* "price"=>htmlspecialchars(number_format((float)$row['price'], 2, ',', '.')),
"dpp"=>htmlspecialchars(number_format((float)$row['dpp'], 2, ',', '.')),
"ppn"=>htmlspecialchars(number_format((float)$row['ppn'], 2, ',', '.')),
"after_ppn"=>htmlspecialchars(number_format((float)$row['after_ppn'] 2, ',', '.'))
 */
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