<?PHP
$mod=$_GET['mod'];
$nm_company=flookup("company","mastercompany","company!=''");
$st_company=flookup("status_company","mastercompany","company!=''");
if (isset($_GET['rptid'])) { $rpt=$_GET['rptid']; } else { $rpt=""; }
if (isset($_GET['mode'])) {	$mode=$_GET['mode']; } else {	$mode="";	}
if ($mod=="1" or $mod=="1L" or $mod=="1v")
{	echo "List In - Out Barang"; }
else if ($mod=="2")
{	echo "User Access"; }
?>
