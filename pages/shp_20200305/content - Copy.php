<?PHP
$mod=$_GET['mod'];
$grafik=flookup("grafik","mastercompany","company!='' ");
if ($mod==1)
{	
}
else if ($mod=="2")
{	include "upd_dok_pab.php"; }
else if ($mod=="2L" or $mod=="2U")
{	include "list_dok_pab.php"; }
else if ($mod=="3" or $mod=="3v")
{	include "inv.php"; }
else if ($mod=="3_L" or $mod=="3v_L")
{	include "inv.php"; }
else if ($mod=="4")
{	include "m_port.php"; }
else if ($mod=="5")
{	include "m_route.php"; }
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
else
{	echo "<h1>Halaman tidak tersedia</h1>";	}

/*
$mod=$_GET['mod'];
$grafik=flookup("grafik","mastercompany","company!='' ");
if ($mod==1)
{	
}
else if ($mod=="2")
{	include "upd_dok_pab.php"; }
else if ($mod=="2L" or $mod=="2U")
{	include "list_dok_pab.php"; }
else if ($mod=="3" or $mod=="3v")
{	include "inv.php"; }
else if ($mod=="4")
{	include "m_port.php"; }
else if ($mod=="5")
{	include "m_route.php"; }
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
else
{	echo "<h1>Halaman tidak tersedia</h1>";	}
*/
?>