<?php
$mod = $_GET['mod'];
$grafik = flookup("grafik", "mastercompany", "company!='' ");
if ($mod == 1) {
	if ($grafik == "Y") {
		include "dashboard2.php";
	} else {
		include "dashboard.php";
	}
} else if ($mod == "cetak_qr") {
	include "cetak_qr.php";
} else if ($mod == "scan_numbering_input") {
	include "scan_numbering.php";
} else if ($mod == "lap_prod") {
	include "lap_prod.php";
} else if ($mod == "lap_prod_exc") {
	include "lap_prod_exc.php";
} else if ($mod == "master_plan_h" or $mod == "master_plan_new" or $mod == "master_plan_edit") {
	include "master_plan.php";
} else if ($mod == "master_plan_exc") {
	include "master_plan_exc.php";
} else if ($mod == "stocker_h" or $mod == "stocker_new" or $mod == "stocker_preview") {
	include "stocker.php";
} else if ($mod == "master_part" or $mod == "master_part_exc") {
	include "master_part.php";
} else {
	echo "<h1>Halaman tidak tersedia</h1>";
}
