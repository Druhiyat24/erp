<?PHP
$mod=$_GET['mod'];
$grafik=flookup("grafik","mastercompany","company!='' ");
if ($mod>=2 and $mod<=6)
{	include "setting.php"; }
else if ($mod=="7")
{	include "un_po.php"; }
else if ($mod=="sampledev1")
{	include "setting_sample_development.php"; }
else if ($mod=="sampledev1" or $mod=="sampledev2" or $mod=="sampledev3")
{ include "setting_sample_development.php"; }
else if ($mod=="ApprovalListPaymant")
{ include "Approval/HeaderPage.php"; }
else if ($mod=="bpb_cancel")
{ include "BpbCancel/HeaderPage.php"; }
?>