<?PHP
if (isset($_GET['mode'])) { $mode=$_GET['mode']; } else { $mode=""; }
$mod=$_GET['mod'];
$nm_company=flookup("company","mastercompany","company!=''");
$st_company=flookup("status_company","mastercompany","company!=''");
if (isset($_GET['rptid'])) { $rpt=$_GET['rptid']; } else { $rpt=""; }
if (isset($_GET['mode'])) {	$mode=$_GET['mode']; } else {	$mode="";	}
if ($mod=="1")
{	echo "Dasbboard"; }
else if ($mod=="2" or $mod=="2L")
{	echo "Cutting Output"; }
else if ($mod=="3" or $mod=="3L")
{	echo "Secondary Process Output"; }
else if ($mod=="4" or $mod=="4L")
{	echo "Sewing Output"; }
else if ($mod=="5" or $mod=="5L")
{	echo $caption[7]." Output"; }
else if ($mod=="6" or $mod=="6L")
{	echo "Packing Output"; }
else if ($mod=="8" or $mod=="8L")
{	echo "Sewing Input"; }
else if ($mod=="7" or $mod=="7v" or $mod=="7s" or $mod=="7sv")
{	echo "Laporan Production Status"; }
else if ($mod=="10" or $mod=="10L")
{	echo "DC Output"; }
else if ($mod=="11" or $mod=="11L")
{	echo "Steam Output"; }
else if ($mod=="CuttingInputForm")
{	echo "List Input Cutting"; }
else if ($mod=="CuttingInput2")
{	echo "Cutting Input Form"; }
else if ($mod=="SecondaryInputForm")
{	echo "Secondary Input Form"; }
else if ($mod=="PackingInputForm")
{	echo "Packing Input Form"; }
else if ($mod=="CuttingInputForm")
{	echo "Cutting Input Form"; }
?>
