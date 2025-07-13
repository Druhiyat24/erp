<?php
$mod=$_GET['mod'];
if (isset($_GET['rptid'])) { $rpt=$_GET['rptid']; } else { $rpt=""; }
if (isset($_GET['mode'])) {	$mode=$_GET['mode']; } else {	$mode="";	}
switch ($mod) 
{	case "1":
		echo "Dashboard"; break;
	case "2":
		echo "BOM Color And Size Breakdown"; break;
	case "3":
		echo "Quotation Inquire"; break;
	case "4":
		echo "Pre Costing"; break;
	case "5":
		echo "<i class='fa fa-calculator'></i> Costing"; break;
	case "5L":
		echo "<i class='fa fa-calculator'></i> Costing"; break;
	case "6":
		echo "Costing Detail"; break;
	case "7":
		echo "<i class='fa fa-line-chart'></i> Sales Order"; break;
	case "7L":
		echo "<i class='fa fa-line-chart'></i> Sales Order"; break;
	case "8":
		echo "<i class='fa fa-line-chart'></i> Sales Order - Breakdown"; break;
	case "8d":
		echo "<i class='fa fa-line-chart'></i> Sales Order - Multi Delete"; break;
	case "8mu":
		echo "<i class='fa fa-line-chart'></i> Sales Order - Multi Update"; break;
	case "9":
		echo "Laporan Quotation Inquire"; break;
	case "9v":
		echo "Laporan Quotation Inquire"; break;
	case "10":
		echo "Laporan Pre-Costing"; break;
	case "10v":
		echo "Laporan Pre-Costing"; break;
	case "11":
		echo "Laporan Costing"; break;
	case "CSO_Global":
		echo "Laporan Costing vs Sales Order Global"; break;
	case "CSO_Detail":
		echo "Laporan Costing vs Sales Order Detail"; break;
	case "11v":
		echo "Laporan Costing"; break;
	case "12":
		echo "<i class='fa fa-tasks'></i> Work Sheet"; break;
	case "12L":
		echo "<i class='fa fa-tasks'></i> Work Sheet"; break;
	case "12a":
		echo "<i class='fa fa-tasks'></i> Work Sheet"; break;
	case "12v":
		echo "List Sales Order"; break;
	case "13":
		echo "Laporan Sales Order"; break;
	case "13v":
		echo "Laporan Sales Order"; break;
	case "14":
		echo "<i class='fa fa-navicon'></i> Bill Of Material"; break;
	case "14L":
		echo "<i class='fa fa-navicon'></i> Bill Of Material"; break;
	case "14d":
		echo "<i class='fa fa-navicon'></i> Bill Of Material - Detail"; break;
	case "14e":
		echo "<i class='fa fa-navicon'></i> Bill Of Material - Detail - Update"; break;

	case "144":
		echo "<i class='fa fa-globe'></i> Bill Of Material Global"; break;
	case "144L":
		echo "<i class='fa fa-globe'></i> Bill Of Material Global"; break;
	case "144d":
		echo "<i class='fa fa-globe'></i> Bill Of Material Global - Detail"; break;
	case "144e":
		echo "<i class='fa fa-globe'></i> Bill Of Material Global - Detail - Update"; break;
	case "144t":
		echo "<i class='fa fa-globe'></i> Bill Of Material Global Tracking"; break;	
	case "144add_c":
		echo "<i class='fa fa-globe'></i> Bill Of Material Global Add Ws Child"; break;				


	case "15":
		echo "<i class='fa fa-shopping-cart'></i> Purchase Requisition"; break;
	case "15L":
		echo "<i class='fa fa-shopping-cart'></i> Purchase Requisition"; break;
	case "15LC":
		echo "Update Fabric Consumption"; break;
	case "15C":
		echo "Update Fabric Consumption"; break;
	case "16":
		echo "Brochure"; break;
	case "17":
		echo "Laporan Sales Order"; break;
	case "17v":
		echo "Laporan Sales Order"; break;
	case "177":
		echo "Laporan Sales Order New"; break;
	case "177v":
		echo "Laporan Sales Order New"; break;
	case "177x":
		echo "Laporan Sales Order New"; break;					
	case "27":
		echo "Laporan Sales Order Detail"; break;
	case "27v":
		echo "Laporan Sales Order Detail"; break;
	case "29":
		echo "Laporan Sales Order Detail Per Warna"; break;
	case "29v":
		echo "Laporan Sales Order Detail Per Warna"; break;				
	case "18":
		echo "Laporan Costing vs Sales Order"; break;
	case "18v":
		echo "Laporan Costing vs Sales Order"; break;
	case "19":
		echo "Laporan History Order"; break;
	case "19v":
		echo "Laporan History Order"; break;
	case "20":
		echo "Unlock Costing"; break;
	case "21":
		echo "Unlock Sales Order"; break;
	case "22L":
		echo "Master Bahan Baku"; break;
	case "22Z":
		echo "Master Content"; break;		
	case "23":
		echo "Upload Costing"; break;
	case "23R":
		echo "Data Costing"; break;
	case "24":
		echo "<i class='fa fa-info'></i> Material Additional Info"; break;
	case "25":
		echo "Unlock JO"; break;
	case "26":
		echo "Laporan Costing Vs SO Per Buyer"; break;	
	case "28":
		echo "Laporan BOM Detail"; break;
	case "28v":
		echo "Laporan BOM Detail"; break;								
	case "updsmv":
		echo "Update SMV"; break;
	case "updsmvl":
		echo "Update SMV"; break;
	case ($mod=="26" || $mod=="26L"):
		echo "Sales Order Sample"; break;
	case "23DEV":
	  echo "Work Sheet Development"; break;  
	case ($mod=="cek_data_ws"):
		echo "Check Data WS"; break;	  
	 default:
		break;
}
?>
