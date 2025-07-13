<?PHP
$mod = $_GET['mod'];
$nm_company = flookup("company", "mastercompany", "company!=''");
$st_company = flookup("status_company", "mastercompany", "company!=''");

if ($mod == "in_material") {
	echo "Penerimaan Material";
} else if ($mod == "in_material_new") {
	echo "Penerimaan Material New";
} else if ($mod == "retur_material") {
	echo "Penerimaan Retur Material";
} else if ($mod == "retur_material_new") {
	echo "Penerimaan Retur Material New";
} else if ($mod == "in_material_item") {
	echo "Penerimaan Material Detail";
} else if ($mod == "m_rak") {
	echo "Master Rak";
} else if ($mod == "edit_m_rak") {
	echo "Master Rak Edit";
} else if ($mod == "m_unit") {
	echo "Master Unit";
} else if ($mod == "edit_m_unit") {
	echo "Master Unit Edit";
} else if ($mod == "req_material" or $mod == "new_req_material") {
	echo "Req Material";
} else if ($mod == "edit_req_material") {
	echo "Req Material Edit";
} else if ($mod == "out_material") {
	echo "Pengeluaran Material";
} else if ($mod == "new_out_material") {
	echo "Pengeluaran Material New";
} else if ($mod == "laporan_rak") {
	echo "Laporan Rak";
} else if ($mod == "laporan_master") {
	echo "Laporan Data";
} else if ($mod == "laporan_mutasi_rak") {
	echo "Laporan Mutasi Rak";
} else if ($mod == "laporan_stok_rak") {
	echo "Laporan Stok Rak";
} else {
	if ($_SESSION['bahasa'] == "Indonesia") {
		$menu = flookup("nama_pilihan", "masterpilihan", "kode_pilihan='Menu$mod'");
	} else {
		$mod = $mod . 'en';
		$menu = flookup("nama_pilihan", "masterpilihan", "kode_pilihan='Menu$mod'");
	}
	echo $menu;
}
