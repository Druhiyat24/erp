<?PHP
$mod=$_GET['mod'];
$grafik=flookup("grafik","mastercompany","company!='' ");
if ($mod==1)
{	
}
else if ($mod=="2" or $mod=="2L")
{	include "cut_out.php"; }
else if ($mod=="3" or $mod=="3L")
{	include "mfg_out.php"; }
else if ($mod=="4" or $mod=="4L")
{	include "sew_out.php"; }
else if ($mod=="5" or $mod=="5L")
{	include "qc_out.php"; }
else if ($mod=="6" or $mod=="6L")
{	include "pack_out.php"; }
else if ($mod=="7" or $mod=="7s")
{	include "laporan.php"; }
else if ($mod=="7v" or $mod=="7sv")
{	include "lap_prod_status.php"; }
else if ($mod=="8" or $mod=="8L")
{	include "sew_in.php"; }
else if ($mod=="9" or $mod=="8L")
{	include "un_prd.php"; }
else if ($mod=="10" or $mod=="10L")
{	include "dc_out.php"; }
else if ($mod=="11" or $mod=="11L")
{	include "steam_out.php"; }
else if ($mod=="AllokasilinesewingPage") //12
{	include "Allokasilinesewing/HeaderPage.php"; }
else if ($mod=="AllokasilinesewingForm") //12ML
{	include "Allokasilinesewing/HeaderForm.php"; }
else if ($mod=="2WP") // 13
{	include "CuttingInput/HeaderPage.php"; }
else if ($mod=="2LA") // 13ML
{	include "CuttingInput/HeaderForm.php"; }
else if ($mod=="3WP") // 13
{	include "CuttingOutput/HeaderPage.php"; }
else if ($mod=="3LA") // 13ML
{	include "CuttingOutput/HeaderForm.php"; }
else if ($mod=="SecondaryInputForm")
{	include "SecondaryInput/HeaderForm.php"; }
else if ($mod=="SecondaryInputPage")
{	include "SecondaryInput/HeaderPage.php"; }
else if ($mod=="PackingInputForm")
{	include "PackingInput/HeaderForm.php"; }
else if ($mod=="PackingInputForm")
{	include "PackingInput/HeaderForm.php"; }
else if ($mod=="QcInputPage")
{	include "QcInput/HeaderPage.php"; }
else if ($mod=="QcInputForm")
{	include "QcInput/HeaderForm.php"; }
else
{	echo "<h1>Halaman tidak tersedia</h1>";	}
?>