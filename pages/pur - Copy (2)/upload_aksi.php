<!-- import excel ke mysql -->
<!-- www.malasngoding.com -->

<?php
// menghubungkan dengan koneksi
include "../../include/conn.php";
// menghubungkan dengan library excel reader
include "excel_reader.php";
session_start();
$dateinput		= date('Y-m-d H:i:s');
$id = $_GET['id_po'];
$user = $_SESSION['username'];
?>

<?php
// upload file xls
$target = basename($_FILES['fileimport']['name']);
move_uploaded_file($_FILES['fileimport']['tmp_name'], $target);

// beri permisi agar file xls dapat di baca
chmod($_FILES['fileimport']['name'], 0777);

// mengambil isi file xls
$data = new Spreadsheet_Excel_Reader($_FILES['fileimport']['name'], false);
// menghitung jumlah baris data yang ada
$jumlah_baris = $data->rowcount($sheet_index = 0);

mysql_query("update masteritem mi 
inner join 
(
select 
mi.id_gen,
concat(a.nama_group,' ',s.nama_sub_group,' ', d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ', g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc,' ',j.add_info) item

from  mastergroup a 					
inner join mastersubgroup s on a.id=s.id_group 					
inner join mastertype2 d on s.id=d.id_sub_group 					
inner join mastercontents e on d.id=e.id_type 					
inner join masterwidth f on e.id=f.id_contents 					
inner join masterlength g on f.id=g.id_width 					
inner join masterweight h on g.id=h.id_length 					
inner join mastercolor i on h.id=i.id_weight 
inner join masterdesc j on i.id=j.id_color
inner join masteritem mi on j.id = mi.id_gen
) b on mi.id_gen = b.id_gen
set 
mi.nm_bom = b.item");



// jumlah default data yang berhasil di import
// $berhasil = 0;
for ($i = 2; $i <= $jumlah_baris; $i++) {

	// menangkap data dan memasukkan ke variabel sesuai dengan kolumnya masing-masing
	$jo     = $data->val($i, 1);
	$itemname     = $data->val($i, 2);
	$qty   = $data->val($i, 3);
	$unit  = $data->val($i, 4);
	$price  = $data->val($i, 5);

	if ($jo != "" && $itemname != "" && $qty != "" && $unit != "" && $price != "") {
		// input data ke database (table data_pegawai)
		mysql_query("INSERT into po_item_draft_upload (jo,itemname,qty,unit,price,user,id_po_draft) values('$jo','$itemname','$qty','$unit','$price','$user','$id')");
		$berhasil++;
	}
}

$jumlah_baris_fix = $jumlah_baris - 1;

// hapus kembali file .xls yang di upload tadi
unlink($_FILES['fileimport']['name']);

// alihkan halaman ke index.php
$_SESSION['msg'] = "Data Berhasil di Import: $jumlah_baris_fix Item";
echo "<script>window.location.href='../pur/?mod=33z&id=$id';</script>";
?>