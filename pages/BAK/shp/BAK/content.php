<?PHP
$mod=$_GET['mod'];
$grafik=flookup("grafik","mastercompany","company!='' ");
if ($mod==1)
{	
}
else if ($mod=="2")
{	include "upd_dok_pab.php"; }
else if ($mod=="2L" or $mod=="2U" or $mod=="2U_new")
{	include "list_dok_pab.php"; }
else if ($mod=="3" or $mod=="3v")
{	include "inv.php"; }
else if ($mod=="3_L" or $mod=="3v_L")
{	include "inv.php"; }
else if ($mod=="DeliveryOrderPage")
{	
	include "PackingListPage/HeaderPage.php";
}
else if ($mod=="PackingListPage")
{	
	include "PackingListPage/HeaderPage.php";
}
else if ($mod=="DeliveryOrderForm")
{	
	include "PackingListPage/HeaderForm.php";
}
else if ($mod=="PackingListForm")
{	
	include "PackingListPage/HeaderForm.php";
}
else if ($mod=="4")
{	include "m_port.php"; }
else if ($mod=="5")
{	include "m_route.php"; }
else if ($mod=="memo_list" or $mod=="memo_new" or $mod=="memo_edit")
{	include "memo.php"; }
else if ($mod=="lap_memo_list")
{	include "lap_excel_memo.php"; }
else if ($mod=="stat_memo")
{	include "memo_status.php"; }
else if ($mod=="exc_stat_memo")
{	include "excel_memo_status.php";	}
else if ($mod=="konfirmasi_memo")
{	include "konfirmasi_memo.php"; }
else if ($mod=="lap_memo_summary")
{	include "lap_excel_memo_sum.php"; }
else if ($mod=="lap_data_exc_memo")
{	include "lap_data_exc_memo.php";	}
else if ($mod=="lap_data_exc_memo_sum")
{	include "lap_data_exc_memo_sum.php";	}
else if ($mod=="memo_list_non_inv" or $mod=="memo_new_non_inv" or $mod=="memo_edit_non_inv")
{	include "memo_non_inv.php"; }
else if ($mod=="InvoiceForm")
{	include "Invoice/HeaderForm.php"; }
else if ($mod=="InvoiceCommercialPage")
{	include "InvoiceCommercial/HeaderPage.php"; }
else if ($mod=="InvoiceCommercialForm")
{	include "InvoiceCommercial/HeaderForm.php"; }
else if ($mod=="EstimatePackingListPage")
{	include "EstimatePackingList/HeaderPage.php"; }
else if ($mod=="EstimatePackingListForm")
{	include "EstimatePackingList/HeaderForm.php"; }
else if ($mod=="ProformaInvoicePage")
{	include "ProformaInvoice/HeaderPage.php"; }
else if ($mod=="ProformaInvoiceForm")
{	include "ProformaInvoice/HeaderForm.php"; }
else if($mod=="InvocieScrapForm")
{	include "InvoiceScrap/HeaderForm.php"; }
else if ($mod=="InvocieScrapPage")
{	include "InvoiceScrap/HeaderPage.php"; }
else if ($mod=="InvocieMaterialPage")
{	include "InvoiceMaterial/HeaderPage.php"; }
else if ($mod=="InvocieMaterialForm")
{	include "InvoiceMaterial/HeaderForm.php"; }
else if ($mod=="upload_exim" OR $mod=="proses_upload_exim" OR $mod=="show_detail")
{	include "upload_exim.php"; }
else
{	echo "<h1>Halaman tidak tersedia</h1>";	}
?>