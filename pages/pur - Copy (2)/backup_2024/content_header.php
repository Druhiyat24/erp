<?PHP
$mod=$_GET['mod'];
if (isset($_GET['mode'])) {$mode=$_GET['mode'];} else {$mode="";}

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
$nm_company=$rscomp['company'];
$st_company=$rscomp['status_company'];
$jenis_company=$rscomp['jenis_company'];

if (isset($_GET['rptid'])) { $rpt=$_GET['rptid']; } else { $rpt=""; }
if (isset($_GET['mode'])) {	$mode=$_GET['mode']; } else {	$mode="";	}
if ($mod=="1")
{	echo "Dashboard"; }
else if ($mod=="2")
{	echo "Upload PO"; }
else if ($mod=="3" OR $mod=="3L")
{	echo "Purchase Order"; }
else if ($mod=="3e" or $mod=="3ea" or $mod=="3ei" or $mod=="3en")
{	echo "Purchase Order (Change)"; }
else if ($mod=="4")
{	echo "Purchase Order - Item"; }
else if ($mod=="5" or $mod=="5L")
{	echo "Booking Stock"; }
else if (($mod=="6" or $mod=="7") and $mode=="PO")
{	echo "Laporan Purchase Order"; }
else if (($mod=="6" or $mod=="12") and $mode=="BPB")
{	echo "Laporan Penasukan Barang"; }
else if (($mod=="6" or $mod=="8") and $mode=="MCC")
{	echo "Laporan MCC"; }
else if ($mod=="8d")
{	echo "Laporan MCC Detail"; }
else if ($mod=="6mcc")
{	echo "Laporan MCC"; }
else if ($mod=="9" OR $mod=="9L")
{	echo "Purchase Order General"; }
else if ($mod=="poadd" OR $mod=="poaddL")
{	echo "Purchase Order Additional"; }
else if ($mod=="poaddOT")
{	echo "Purchase Order Additional - Over Tollerance"; }
else if ($mod=="9e" or $mod=="9ei")
{	echo "Purchase Order General (Change)"; }
else if ($mod=="10" or $mod=="9ei")
{	echo "Master Bahan Baku"; }
else if ($mod=="11")
{	echo "Pro Rata PO"; }
else if ($mod=="lap_gen_req")
{	echo "General Request"; }
else if (($mod=="det_dash") and $mode=="pow")
{	echo "PO Waiting"; }
else if (($mod=="det_dash") and $mode=="leta")
{	echo "Late ETA after 3 days"; }
else if (($mod=="det_dash") and $mode=="allpo")
{	echo "All PO List"; }
else if (($mod=="det_dash") and $mode=="latepo")
{	echo "Late PO Open by PR Date"; }
else if ($mod=="15")
{	echo "Over Tollerance Allocation"; }
else if ($mod=="po_header")
{	echo "List Purchase Order"; }
?>
