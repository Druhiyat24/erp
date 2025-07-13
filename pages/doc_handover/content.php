<?PHP
$mod=$_GET['mod'];
$grafik=flookup("grafik","mastercompany","company!='' ");
if ($mod==1)
{	
}
else if ($mod=="trans_bpb")
{	include "trans_bpb.php"; }
else if ($mod=="exc_trans_bpb")
{	include "exc_trans_bpb.php";	}
else if ($mod=="create_trans_bpb")
{	include "create_trans_bpb.php"; }
else if ($mod=="maintain_bpb")
{	include "maintain_bpb.php"; }
else if ($mod=="create_maintain_bpb")
{	include "create_maintain_bpb.php"; }
else
{	echo "<h1>Halaman tidak tersedia</h1>";	}
?>