<?PHP
$mod=$_GET['mod'];
$nm_company=flookup("company","mastercompany","company!=''");
$st_company=flookup("status_company","mastercompany","company!=''");
if (isset($_GET['rptid'])) { $rpt=$_GET['rptid']; } else { $rpt=""; }
if (isset($_GET['mode'])) {	$mode=$_GET['mode']; } else {	$mode="";	}
if ($mod=="1")
{	echo "Dasbboard"; }
else if ($mod=="2" or $mod=="2L" or $mod=="2U")
{	echo "Update Dokumen Pabean"; }
else if ($mod=="3" or $mod=="3v")
{	echo "Packing List"; }
else if ($mod=="3_L" or $mod=="3v_L")
{	echo "Delivery Order"; }
else if ($mod=="DeliveryOrderPage" or $mod=="DeliveryOrderForm")
{	echo "Delivery Order"; }
else if ($mod=="PackingListPage" or $mod=="PackingListForm")
{	echo "Packing List"; }
else if ($mod=="4")
{	echo "Master Port"; }
else if ($mod=="5")
{	echo "Master Route"; }
else if ($mod=="EstimatePackingListPage")
{	echo "Estimate Packing List Page"; }
else if ($mod=="EstimatePackingListForm") //InvoiceCommercialPage
{	echo "Estimate Packing List Form"; }
else if ($mod=="InvoiceCommercialPage") //InvoiceCommercialPage
{	echo "Invoice"; }
else if ($mod=="InvocieScrapForm") //InvoiceCommercialPage
{	echo "Invoice Scrap Form"; }
else if ($mod=="InvocieScrapPage") //InvoiceCommercialPage
{	echo "List Invoice Scrap"; }
else if ($mod=="InvocieMaterialPage")
{	echo "List Invoice Material"; }
else if ($mod=="InvocieMaterialForm")
{	echo "Invoice Material Form"; }
?>
