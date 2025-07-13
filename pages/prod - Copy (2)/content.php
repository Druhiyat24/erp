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
else if ($mod=="7s")
{	
	//include "laporan.php";
	include "LaporanStatusSummary/HeaderPage.php";
}
else if ($mod=="7")
{	
	//include "lap_prod_status.php";
	include "LaporanStatusDetail/HeaderPage.php";
}
else if ($mod=="7v" or $mod=="7sv")
{	include "lap_prod_status.php"; }
else if ($mod=="8" or $mod=="8L")
{	include "sew_in.php"; }
else if ($mod=="9" or $mod=="8L")
{	include "un_prd.php"; }


else if ($mod=="AllokasilinesewingPage") //12
{	include "Allokasilinesewing/HeaderPage.php"; }
else if ($mod=="AllokasilinesewingForm") //12ML
{	include "Allokasilinesewing/HeaderForm.php"; }

else if ($mod=="PW")
{	include "MarkerEntry/HeaderPage.php"; }
else if ($mod=="WL")
{	include "MarkerEntry/HeaderPage2.php"; }
else if ($mod=="AL")
{	include "MarkerEntry/HeaderForm.php"; }

else if ($mod=="mRoll")
{	include "ManagementRoll/HeaderPage.php"; }
else if ($mod=="mRollForm")
{	include "ManagementRoll/HeaderForm.php"; }

else if ($mod=="2WP") // 13
{	include "CuttingInput/HeaderPage.php"; }
else if ($mod=="2LA") // 13ML
{	include "CuttingInput/HeaderForm.php"; }
else if ($mod=="3WP") // 13
{	include "CuttingOutput/HeaderPage.php"; }
else if ($mod=="3LA") // 13ML
{	include "CuttingOutput/HeaderForm.php"; }

else if ($mod=="numCut")
{	include "CuttingNumbering/HeaderPage.php"; }
else if ($mod=="numCutColor")
{	include "CuttingNumbering/HeaderPage2.php"; }
else if ($mod=="numCutForm")
{	include "CuttingNumbering/HeaderForm.php"; }

else if ($mod=="cutQC")
{	include "CuttingQC/HeaderPage.php"; }
else if ($mod=="cutQCColor")
{	include "CuttingQC/HeaderPage2.php"; }
else if ($mod=="cutQCForm")
{	include "CuttingQC/HeaderForm.php"; }

else if ($mod=="4WP")
{	include "SecondaryInput/HeaderPage.php"; }
else if ($mod=="4LA")
{	include "SecondaryInput/HeaderForm.php"; }
else if ($mod=="5WP")
{	include "SecondaryOutput/HeaderPage.php"; }
else if ($mod=="5LA")
{	include "SecondaryOutput/HeaderForm.php"; }
else if ($mod=="secQC")
{	include "SecondaryQC/HeaderPage.php"; }
else if ($mod=="secQCForm")
{	include "SecondaryQC/HeaderForm.php"; }
else if ($mod=="6WP")

{	include "DCJoin/HeaderPage.php"; }
else if ($mod=="6LA")
{	include "DCJoin/HeaderForm.php"; }
else if ($mod=="7WP")
{	include "DCSet/HeaderPage.php"; }
else if ($mod=="7LA")
{	include "DCSet/HeaderForm.php"; }
else if ($mod=="8WP")

{	include "SewingInput/HeaderPage.php"; }
else if ($mod=="8LA")
{	include "SewingInput/HeaderForm.php"; }
else if ($mod=="9WP")
{	include "SewingOutput/HeaderPage.php"; }
else if ($mod=="9LA")
{	include "SewingOutput/HeaderForm.php"; }
else if ($mod=="10WP")
{	include "SewingQcInput/HeaderPage.php"; }
else if ($mod=="10LA")
{	include "SewingQcInput/HeaderForm.php"; }
else if ($mod=="11WP")
{	include "SewingQcOutput/HeaderPage.php"; }
else if ($mod=="11LA")
{	include "SewingQcOutput/HeaderForm.php"; }
else if ($mod=="PackingInputForm")
{	include "PackingInput/HeaderForm.php"; }
else if ($mod=="PackingInputForm")
{	include "PackingInput/HeaderForm.php"; }
else if ($mod=="QcInputPage")
{	include "QcInput/HeaderPage.php"; }
else if ($mod=="QcInputForm")
{	include "QcInput/HeaderForm.php"; }

else if ($mod=="SpreadingReport")
{	include "SpreadingReport/HeaderPage.php"; }
else if ($mod=="SpreadingReport_list")
{	include "SpreadingReport/HeaderPage2.php"; }
else if ($mod=="SpreadingReportForm")
{	include "SpreadingReport/HeaderForm.php"; }
else if ($mod=="SpreadingReportBySo")
{	include "SpreadingReport/HeaderPageBySo.php"; }
else if ($mod=="SpreadingReportBySoColor")
{	include "SpreadingReport/HeaderPageBySoColor.php"; }

else if ($mod=="mRollListWs")
{	include "ManagementRoll/HeaderListWSPage.php"; }
else if ($mod=="mRollListWsSoColorPage")
{	include "ManagementRoll/HeaderListWSSoColorPage.php"; }


else if ($mod=="11L" )
{	include "SteamOutput/HeaderPage.php"; }

else if ($mod=="11" )
{	include "SteamOutput/HeaderForm.php"; }


else if ($mod=="QC_F_I_Page")
{	include "QcFinalInput/HeaderPage.php"; }

else if ($mod=="QC_F_I_Form")
{	include "QcFinalInput/HeaderForm.php"; }

else if ($mod=="QC_F_O_Page")
{	include "QcFinalOutput/HeaderPage.php"; }

else if ($mod=="QC_F_O_Form")
{	include "QcFinalOutput/HeaderForm.php"; }

else if ($mod=="Pack_Page")
{	include "Packing/HeaderPage.php"; }

else if ($mod=="Pack_Form")
{	include "Packing/HeaderForm.php"; }

else if ($mod=="Met_Dec_Page")
{	include "MetalDetector/HeaderPage.php"; }

else if ($mod=="Met_Dec_Form")
{	include "MetalDetector/HeaderForm.php"; }

else if ($mod=="QC_A_Page")
{	include "QcAuditBuyer/HeaderPage.php"; }

else if ($mod=="QC_A_Form")
{	include "QcAuditBuyer/HeaderForm.php"; }

else if ($mod=="FG_Page")
{	include "FinishGood/HeaderPage.php"; }

else if ($mod=="FG_Form")
{	include "FinishGood/HeaderForm.php"; }

else if ($mod=="SHP_Page")
{	include "Shipping/HeaderPage.php"; }

else if ($mod=="SHP_Form")
{	include "Shipping/HeaderForm.php"; }


else
{	echo "<h1>Halaman tidak tersedia</h1>";	}
?>