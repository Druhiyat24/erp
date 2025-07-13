<?PHP

$mod=$_GET['mod'];

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));

	$grafik=$rscomp["grafik"];

	$logo_company=$rscomp["logo_company"];

if ($mod==1000)
{	include "dashboardv.php"; }
if ($mod==1001)
{	echo ""; }
// else if ($mod==1000)
// {	include "dashboardv.php"; }

else if ($mod==2)

{	include "import_po.php"; }

else if ($mod=="3" or $mod=="3L" or $mod=="3e")

{	include "po.php"; }

else if ($mod=="3z")

{	include "po_no_edit.php"; }

else if ($mod=="3ea")

{	include "po_edit_add.php"; }

else if ($mod=="3ei" or $mod=="9ei")

{	include "po_item_ed.php"; }

else if ($mod=="x3ei" or $mod=="x9ei")

{	include "po_item_ed_dev.php"; }

else if ($mod=="3en")

{	include "po_notes.php"; }

else if ($mod=="4")

{	include "po_item.php"; }

else if ($mod=="5" or $mod=="5L")

{	include "trans_post.php"; }

else if ($mod=="6")

{	include "laporan.php"; }

else if ($mod=="6mcc")

{	include "laporan_mcc.php"; }

else if ($mod=="7")

{	include "lap_po.php"; }

else if ($mod=="8")

{	include "lap_mcc.php"; }

else if ($mod=="8d")

{	include "lap_mcc_det.php"; }

else if ($mod=="9" or $mod=="9L" or $mod=="9e")

{	include "po_gen.php"; }
else if ($mod=="poadd" or $mod=="poaddL" or $mod=="poaddC" or $mod=="poaddOT" or $mod=="poadde")
{	include "po_add.php"; }
else if ($mod=="9z")

{	include "po_gen_no_edit.php"; }

else if ($mod=="10")

{	include "../marketting/lap_mst_bb.php"; }

else if ($mod=="11")

{	include "prorata.php"; }

else if ($mod=="12")

{	include "../forms/detail_trans.php";	}

else if ($mod=="det_dash")
{	include "detail_dashboard.php";	}
else if ($mod=="15" or $mod=="15L")
{	include "over_ship_alloc.php";	}
else if ($mod=="x3L" or $mod=="x3" or $mod=="x3e")

{	include "po_dev.php";	}

else if ($mod=="x3ea")

{	include "po_notes_dev.php";	}

else if ($mod=="x3en")

{	include "po_edit_add_dev.php";	}

else if ($mod=="x9L" or $mod=="x9e")

{	include "po_dev.php";	}

else if ($mod=="gendev9" or $mod=="gendev9L" or $mod=="gendev9e")

{   include "po_gen_dev.php"; }

else if ($mod=="gendev11")

{   include "prorata_dev.php"; }

else if ($mod=="gendev5" or $mod=="gendev5L")

{   include "trans_post_dev.php"; }
else if($mod=="draft_po_bW_page"){
	include "DraftPoBW/HeaderPage.php";
}
else if($mod=="draft_po_bW_form"){
	include "DraftPoBW/HeaderForm.php";
}
else if($mod=="draft_po_gen_page"){
	include "DraftPoGen/HeaderPage.php";
}
else if($mod=="draft_po_gen_form"){
	include "DraftPoGen/HeaderForm.php";
}
else

{	echo "<h1>Halaman tidak tersedia</h1>";	}

?>