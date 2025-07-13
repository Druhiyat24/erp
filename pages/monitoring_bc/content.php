<?PHP
$mod=$_GET['mod'];
$grafik=flookup("grafik","mastercompany","company!='' ");
if ($mod==1)
{	
}
else if ($mod=="lap_mon")
{	include "lap_monitoring.php"; }
else if ($mod=="exc_data_monitoring")
{	include "exc_data_monitoring.php";	}
else if ($mod=="create_trans_bpb")
{	include "create_trans_bpb.php"; }
else
{	echo "<h1>Halaman tidak tersedia</h1>";	}
?>