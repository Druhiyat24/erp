<?PHP
if (isset($_GET['mode'])) { $mode=$_GET['mode']; } else { $mode=""; }
$mod=$_GET['mod'];
$nm_company=flookup("company","mastercompany","company!=''");
$st_company=flookup("status_company","mastercompany","company!=''");
if (isset($_GET['rptid'])) { $rpt=$_GET['rptid']; } else { $rpt=""; }
if (isset($_GET['mode'])) {	$mode=$_GET['mode']; } else {	$mode="";	}
if ($mod=="1")
{	echo "Dashboard"; }
else if ($mod=="2" or $mod=="2L")
{	echo "Cutting Output"; }
else if ($mod=="3" or $mod=="3L")
{	echo "Secondary Process Output"; }
else if ($mod=="4" or $mod=="4L")
{	echo "Sewing Output"; }
else if ($mod=="5" or $mod=="5L")
{	echo $caption[7]." Output"; }
else if ($mod=="6" or $mod=="6L")
{	echo "Packing Output"; }
else if ($mod=="8" or $mod=="8L")
{	echo "Sewing Input"; }
//mario
else if ($mod=="8" or $mod=="9L")
{	echo "Alokasi Line Sewing Input"; }
//
else if ($mod=="7" or $mod=="7v" or $mod=="7s" or $mod=="7sv")
{	echo "Laporan Production Status"; }

else if ($mod=="11" or $mod=="11L")
{	echo "Steam Output"; }

else if ($mod=="PW")
{	echo "Marker Entry"; }
else if ($mod=="WL")
{	echo "Marker Entry"; }
else if ($mod=="AL")
{	echo "Marker Entry Form"; }

else if ($mod=="SpreadingReport")
{	echo "Spreading Report"; }
else if ($mod=="SpreadingReportBySo")
{	echo "Spreading Report"; }
else if ($mod=="SpreadingReportBySoColor")
{	echo "Spreading Report"; }
else if ($mod=="SpreadingReport_list")
{	echo "Spreading Report"; }
else if ($mod=="SpreadingReportForm")
{	echo "Spreading Report Form"; }

else if ($mod=="mRollListWs")
{	echo "Management Roll"; }
else if ($mod=="mRollListWsSoColorPage")
{	echo "Management Roll"; }
else if ($mod=="mRollForm")
{	echo "Management Roll Form"; }

else if ($mod=="2WP")
{	echo "Cutting Input"; }
else if ($mod=="2LA")
{	echo "Cutting Input Form"; }
else if ($mod=="3WP")
{	echo "Cutting Output"; }
else if ($mod=="3LA")
{	echo "Cutting Output Form"; }

else if ($mod=="numCut")
{	echo "Cutting Numbering"; }
else if ($mod=="numCutColor")
{	echo "Cutting Numbering Color"; }
else if ($mod=="numCutForm")
{	echo "Cutting Numbering Form"; }

else if ($mod=="cutQC")
{	echo "Cutting QC"; }
else if ($mod=="cutQCColor")
{	echo "Cutting QC Color"; }
else if ($mod=="cutQCForm")
{	echo "Cutting QC Form"; }

else if ($mod=="4WP")
{	echo "Secondary Input"; }
else if ($mod=="4LA")
{	echo "Secondary Input Form"; }
else if ($mod=="5WP")
{	echo "Secondary Output"; }
else if ($mod=="5LA")
{	echo "Secondary Output Form"; }
else if ($mod=="secQC")
{	echo "Secondary QC"; }
else if ($mod=="secQCForm")
{	echo "Secondary QC Form"; }


else if ($mod=="6WP")
{	echo "Distribution Center Join"; }
else if ($mod=="6LA")
{	echo "Distribution Center Join Form"; }
else if ($mod=="7WP")
{	echo "Distribution Center Set"; }
else if ($mod=="7LA")
{	echo "Distribution Center Set Form"; }
else if ($mod=="8WP")
{	echo "Sewing Input"; }
else if ($mod=="8LA")
{	echo "Sewing Input Form"; }
else if ($mod=="9WP")
{	echo "Sewing Output"; }
else if ($mod=="9LA")
{	echo "Sewing Output Form"; }
else if ($mod=="10WP")
{	echo "QC Input"; }
else if ($mod=="10LA")
{	echo "QC Input Form"; }
else if ($mod=="11WP")
{	echo "QC Output"; }
else if ($mod=="11LA")
{	echo "QC Output Form"; }

else if ($mod=="PackingInputForm")
{	echo "Packing Input Form"; }




else if ($mod=="QC_F_I_Page")
{	echo "Qc Final Input Page"; }

else if ($mod=="QC_F_I_Form")
{	echo "Qc Final Input Form"; }

else if ($mod=="QC_F_O_Page")
{	echo "Qc Final Output Page"; }

else if ($mod=="QC_F_O_Form")
{	echo "QC Final Output Form"; }

else if ($mod=="Pack_Page")
{	echo "Packing Input Page"; }

else if ($mod=="Pack_Form")
{	echo "Packing Input Form"; }

else if ($mod=="Met_Dec_Page")
{	echo "Metal Detector Page"; }

else if ($mod=="Met_Dec_Form")
{	echo "Metal Detector Page Form"; }

else if ($mod=="QC_A_Page")
{	echo "QC Audit Page"; }

else if ($mod=="QC_A_Form")
{	echo "QC Audit Form"; }

else if ($mod=="FG_Page")
{	echo "Finish Good Page"; }

else if ($mod=="FG_Form")
{	echo "Finish Good Form"; }

else if ($mod=="SHP_Page")
{	echo "Shipping Page"; }

else if ($mod=="SHP_Form")
{	echo "Shipping Form"; }


//mario
else if ($mod=="AllokasilinesewinForm")
{ 	echo "Allokasi Line Sewing Form"; }
//
?>
