<?PHP
$mod=$_GET['mod'];
$nm_company=flookup("company","mastercompany","company!=''");
$st_company=flookup("status_company","mastercompany","company!=''");
if (isset($_GET['rptid'])) { $rpt=$_GET['rptid']; } else { $rpt=""; }
if (isset($_GET['mode'])) {	$mode=$_GET['mode']; } else {	$mode="";	}
if ($mod=="1")
{	echo "Master Data"; }
else if ($mod=="2")
{	echo "Master Season"; }
else if ($mod=="3")
{	echo "Master Group"; }
else if ($mod=="4")
{	echo "Master Sub Group"; }
else if ($mod=="5")
{	echo "Master Type"; }
else if ($mod=="6")
{	echo "Master Contents"; }
else if ($mod=="7")
{	echo "Master Width"; }
else if ($mod=="8")
{	echo "Master Length"; }
else if ($mod=="9")
{	echo "Master Weight"; }
else if ($mod=="10")
{	echo "Master Color"; }
else if ($mod=="11")
{	echo "Master Description"; }
else if ($mod=="12")
{	echo "Closing Periode"; }
else if ($mod=="13")
{	echo "Payment Terms"; }
else if ($mod=="14")
{	echo "Master Bank"; }
else if ($mod=="15")
{	echo "Master Rate Currency"; }
else if ($mod=="16")
{	echo "Master Product"; }
else if ($mod=="17")
{	echo "Master Ship Mode"; }
else if ($mod=="18")
{	echo $caption[6]; }
else if ($mod=="19")
{	echo "Others Cost"; }
else if ($mod=="20")
{	echo "Master Customer"; }
else if ($mod=="21")
{	echo "Master Supplier"; }
else if ($mod=="22")
{	echo "Master Allowance"; }
else if ($mod=="23")
{	echo "Master HS & Tarif"; }
else if ($mod=="24")
{	echo "Master Defect"; }
else if ($mod=="25")
{	echo "Master Urutan Size"; }
else if ($mod=="26")
{	echo "Master Unit"; }
else if ($mod=="27")
{	echo "Master Rak"; }
else if ($mod=="mpanel")
{	echo "Master Panel"; }
else if ($mod=="28" or $mod=="28R" or $mod=="28L" or $mod=="28LD")
{	echo "Product - BOM"; }
else if ($mod=="29")
{	echo "Master Item ODO"; }
?>