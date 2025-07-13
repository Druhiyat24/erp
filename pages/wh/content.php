<?php
$mod = $_GET['mod'];
$grafik = flookup("grafik", "mastercompany", "company!='' ");
if ($mod == 1) { {
		include "dashboard.php";
	}
} else if ($mod == "in_material" or $mod == "in_material_new" or $mod == "in_material_item") {
	// include "in_material.php";
	include "bpb_po.php";
} else if ($mod == "edit_penerimaan") {
	// include "in_material.php";
	include "bpb_po_ed.php";
} else if ($mod == "retur_material" or $mod == "retur_material_new") {
	// include "retur_material.php";
	include "ri.php";
} else if ($mod == "edit_pengembalian") {
	// include "in_material.php";
	include "ri_ed.php";
} else if ($mod == "inmaterial_excel") {
	include "inmaterial_excel.php";
} else if ($mod == "reqmaterial_excel") {
	include "reqmaterial_excel.php";
} else if ($mod == "m_rak" or $mod == "edit_m_rak") {
	include "m_rak.php";
} else if ($mod == "m_unit" or $mod == "edit_m_unit") {
	include "m_unit.php";
} else if ($mod == "req_material" or $mod == "edit_req_material" or $mod == "new_req_material") {
	// include "req_material.php";
	include "bppb_req_new.php";
} else if ($mod == "out_material" or $mod == "new_out_material") {
	// include "out_material.php";
	include "bppb_req_barcode_new.php";
} else if ($mod == "laporan_rak") {
	include "laporan_rak.php";
} else if ($mod == "laporan_rak_excel") {
	include "laporan_rak_excel.php";
} else if ($mod == "laporan_master") {
	include "laporan_master.php";
} else if ($mod == "laporan_master_excel") {
	include "laporan_master_excel.php";
} else if ($mod == "laporan_mutasi_rak") {
	include "laporan_mutasi_rak.php";
} else if ($mod == "laporan_mutasi_rak_excel") {
	include "laporan_mutasi_rak_excel.php";
} else if ($mod == "laporan_stok_rak") {
	include "laporan_stok_rak.php";
} else if ($mod == "laporan_stok_rak_excel") {
	include "laporan_stok_rak_excel.php";
} else {
	echo "<h1>Halaman tidak tersedia</h1>";
}
