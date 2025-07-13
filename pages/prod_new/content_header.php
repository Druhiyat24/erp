<?PHP
$mod = $_GET['mod'];
$nm_company = flookup("company", "mastercompany", "company!=''");
$st_company = flookup("status_company", "mastercompany", "company!=''");

if ($mod == "cetak_qr") {
	echo "Cetak Qr";
} else if ($mod == "scan_numbering_input") {
	echo "Scan Numbering";
} else if ($mod == "lap_prod") {
	echo "Laporan Produksi";
} else if ($mod == "master_plan_h" or $mod == "master_plan_new") {
	echo "Master Plan";
} else if ($mod == "master_plan_edit") {
	echo "Master Plan Edit";
} else if ($mod == "stocker_h" or $mod == "stocker_new" or $mod == "stocker_preview") {
	echo "Stocker";
} else if ($mod == "master_part") {
	echo "Master Part";
} else {
	if ($_SESSION['bahasa'] == "Indonesia") {
		$menu = flookup("nama_pilihan", "masterpilihan", "kode_pilihan='Menu$mod'");
	} else {
		$mod = $mod . 'en';
		$menu = flookup("nama_pilihan", "masterpilihan", "kode_pilihan='Menu$mod'");
	}
	echo $menu;
}
