<?php 
$mod=$_GET['mod'];
$grafik=flookup("grafik","mastercompany","company!='' ");
$logo_company=flookup("logo_company","mastercompany","company!=''");

if ($mod==1)
{	
}
else if ($mod==2)
{	include "m_season.php"; }
else if ($mod=="3")
{	include "m_group.php"; }
else if ($mod=="4")
{	include "m_subgroup.php"; }
else if ($mod=="5")
{	include "m_type.php"; }
else if ($mod=="6")
{	include "m_contents.php"; }
else if ($mod=="7")
{	include "m_width.php"; }
else if ($mod=="8")
{	include "m_length.php"; }
else if ($mod=="9")
{	include "m_weight.php"; }
else if ($mod=="10")
{	include "m_color.php"; }
else if ($mod=="11")
{	include "m_desc.php"; }
else if ($mod=="12")
{	include "m_close.php"; }
else if ($mod=="13")
{	if($logo_company=="S")
	{	include "m_pterms2.php"; }
	else
	{	include "m_pterms.php"; }
}
else if ($mod=="14")
{	include "m_bank.php"; }
else if ($mod=="mtax")
{	include "mtax.php"; }
else if ($mod=="MasterCurrency")
{	  include "MasterCurrency/HeaderPage.php"; break;	 }
else if ($mod=="15")
{//	include "m_rate.php"; 
	include "MasterCurrency/HeaderPage.php";
}
else if ($mod=="16")
{	include "m_product.php"; }
else if ($mod=="17")
{	include "m_smode.php"; }
else if ($mod=="18")
{	include "m_cf.php"; }
else if ($mod=="19")
{	include "m_othcst.php"; }
else if ($mod=="20")
{	include "../forms/master_supcust.php"; }
else if ($mod=="21")
{	include "../forms/master_supcust.php"; }
else if ($mod=="200")
{	include "m_agent.php"; }
else if ($mod=="22")
{	include "m_allow.php"; }
else if ($mod=="23")
{	include "m_hs.php"; }
else if ($mod=="24")
{	include "m_defect.php"; }
else if ($mod=="25")
{	include "m_size.php"; }
else if ($mod=="26")
{	include "m_unit.php"; }
else if ($mod=="27")
{	include "m_rak.php"; }
else if ($mod=="28" or $mod=="28R" or $mod=="28L" or $mod=="28LD")
{	include "upload_prod_det.php"; }
else if ($mod=="28U")
{	include "pro_upload_prod_det.php"; }
else if ($mod=="28U")
{	include "pro_upload_prod_det.php"; }
else if ($mod=="28S")
{	include "s_upload_prod_det.php"; }
else if ($mod=="mpanel")
{	include "m_panel.php"; }
else if ($mod=="29")
{	include "m_odo.php"; }
else if ($mod=="block_customer")
{	include __DIR__ ."/../marketting/BlockCustomerPage/HeaderPage.php"; }
else
{	echo "<h1>Halaman tidak tersedia</h1>";	}
?>