<?php
$mod = $_GET['mod'];
$grafik = flookup("grafik", "mastercompany", "company!='' ");
if ($mod == 1) { {
		include "js_dashboard1.php";
	}
} else if ($mod == "in_material" or $mod == "in_material_new" or $mod == "in_material_item") {
	
} else if ($mod == "retur_material" or $mod == "retur_material_new") {
	
} else if ($mod == "inmaterial_excel") {
	
} else if ($mod == "reqmaterial_excel") {
	
} else if ($mod == "m_rak" or $mod == "edit_m_rak") {
	
} else if ($mod == "m_unit" or $mod == "edit_m_unit") {
	
} else if ($mod == "req_material" or $mod == "edit_req_material" or $mod == "new_req_material") {
	
} else {
	echo "";
}
