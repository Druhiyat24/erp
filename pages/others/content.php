<?PHP
$mod=$_GET['mod'];
$grafik=flookup("grafik","mastercompany","company!='' ");
if ($mod=="1L" or $mod=="1")
{	include "gen_req.php"; }
else if ($mod=="1a")
{	include "gen_req_det.php"; }
else if ($mod=="2L" or $mod=="2")
{	include "master.php"; }
else if ($mod=="3")
{	include "lap_mst_bb_non.php"; }
else if ($mod=="4")
{	include "un_reqnon.php"; }
else if ($mod=="14")
{	include "../forms/kartu_stock.php"; }
?>