<?PHP
$mod=$_GET['mod'];
$nm_company=flookup("company","mastercompany","company!=''");
$st_company=flookup("status_company","mastercompany","company!=''");
if (isset($_GET['rptid'])) { $rpt=$_GET['rptid']; } else { $rpt=""; }
if (isset($_GET['mode'])) {	$mode=$_GET['mode']; } else {	$mode="";	}
if ($mod=="1" or $mod=="1L")
{	echo "General Request"; }
else if ($mod=="1a")
{	echo "General Request Item"; }
else if ($mod=="2" or $mod=="2L")
{	echo "Master Non Production"; }
else if ($mod=="4")
{	echo "Unapprove General Request"; }
?>
