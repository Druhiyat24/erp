<?php 
	function d_from($from){
		$d_from = explode("/",$from."/01");
		$d_from = $d_from[1]."-".$d_from[0]."-".$d_from[2];
		return $d_from;
	}
	function d_to($to){
		$d_to = explode("/",$to."/01");
		$d_to = $d_to[1]."-".$d_to[0]."-".$d_to[2];
		//$d_to = date("Y-m-d", strtotime("1 month",strtotime($d_to)));
		//$d_to = date("Y-m-d", strtotime("-1 days",strtotime($d_to)));
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
select a.bpbno_int as Nomor_BPB
,			a.bpbdate as Tanggal_BPB
,			b.Supplier as Nama_Supplier
,			c.goods_code as Kode_Barang
,			c.itemdesc as Nama_Barang
,			a.qty as Qty
,			a.unit as Unit
,			a.curr as Currency
,			a.price as Price
,			(a.qty*a.price) as Total
,			a.remark
,			a.invno
,			a.pono as Nomor_PO
,			a.username
,			a.bcno as Nomor_BC
,			a.bcdate as Tanggal_BC
,			a.jenis_dok as Jenis_Dokumen
,			a.confirm_by as Confirm_By
,			a.confirm_date as Tanggal_Confirm
from bpb a 
left join mastersupplier b on b.Id_Supplier=a.id_supplier
left join masteritem c on c.id_item=a.id_item

where a.bpbno_int like '%GEN/IN%' and a.bpbdate <= '$d_to' and a.bpbdate >= '$d_from'
)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

	X.Nomor_BPB            =  LIKE'%".$searchValue."%'
	X.Tanggal_BPB                   =  LIKE'%".$searchValue."%'
	X.Nama_Supplier                 =  LIKE'%".$searchValue."%'
	X.Kode_Barang              =  LIKE'%".$searchValue."%'
	X.Nama_Barang          	   =  LIKE'%".$searchValue."%'
	X.Qty	   =  LIKE'%".$searchValue."%'
	X.Unit =  LIKE'%".$searchValue."%'
	X.Currency    =  LIKE'%".$searchValue."%'
	X.Price =  LIKE'%".$searchValue."%'
	X.Total =  LIKE'%".$searchValue."%'
	X.remark =  LIKE'%".$searchValue."%'
	X.invno              =  LIKE'%".$searchValue."%'
	X.Nomor_PO                  =  LIKE'%".$searchValue."%'
	X.username                  =  LIKE'%".$searchValue."%'
	X.Nomor_BC                  =  LIKE'%".$searchValue."%'
	X.Tanggal_BC                  =  LIKE'%".$searchValue."%'
	X.Jenis_Dokumen                  =  LIKE'%".$searchValue."%'
	X.Confirm_By                  =  LIKE'%".$searchValue."%'
	X.Tanggal_Confirm                  =  LIKE'%".$searchValue."%'
	
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
	X.Nomor_BPB,
	X.Tanggal_BPB,
	X.Nama_Supplier,
	X.Kode_Barang,
	X.Nama_Barang,
	X.Qty,
	X.Unit,
	X.Currency,
	X.Price,
	X.Total,
	X.remark,
	X.invno,
	X.Nomor_PO,
	X.username,
	X.Nomor_BC,
	X.Tanggal_BC,
	X.Jenis_Dokumen,
	X.Confirm_By,
	X.Tanggal_Confirm
	
			";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
//echo $empQuery;
while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];


   $data[] = array(            
"Nomor_BPB"=>htmlspecialchars($row['Nomor_BPB']),
"Tanggal_BPB"=>htmlspecialchars($row['Tanggal_BPB']),
"Nama_Supplier"=>htmlspecialchars($row['Nama_Supplier']),
"Kode_Barang"=>htmlspecialchars($row['Kode_Barang']),
"Nama_Barang"=>htmlspecialchars($row['Nama_Barang']),
"Qty"=>htmlspecialchars($row['Qty']),
"Unit"=>htmlspecialchars($row['Unit']),
"Currency"=>htmlspecialchars($row['Currency']),
"Price"=>htmlspecialchars($row['Price']),
"Total"=>htmlspecialchars($row['Total']),
"remark"=>htmlspecialchars($row['remark']),
"invno"=>htmlspecialchars($row['invno']),
"Nomor_PO"=>htmlspecialchars($row['Nomor_PO']),
"username"=>htmlspecialchars($row['username']),
"Nomor_BC"=>htmlspecialchars($row['Nomor_BC']),
"Tanggal_BC"=>htmlspecialchars($row['Tanggal_BC']),
"Jenis_Dokumen"=>htmlspecialchars($row['Jenis_Dokumen']),
"Confirm_By"=>htmlspecialchars($row['Confirm_By']),
"Tanggal_Confirm"=>htmlspecialchars($row['Tanggal_Confirm']),

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