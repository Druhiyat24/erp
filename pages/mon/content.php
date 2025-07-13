<?php
$mod=$_GET['mod'];
$grafik=flookup("grafik","mastercompany","company!='' ");
$user=$_SESSION['username'];
if ($mod==24)
{	include "../forms/bom_mon.php"; }
else if ($mod==25)
{	if($nm_company=="PT. Tun Hong")
	{	include "../forms/tampil_bom_mon.php";	}
	else
	{	include "../forms/tampil_bom_mon_erp.php";	}
}
?>