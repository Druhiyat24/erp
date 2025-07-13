<?PHP
$mod=$_GET['mod'];
if (isset($_GET['rptid'])) { $rpt=$_GET['rptid']; } else { $rpt=""; }
if (isset($_GET['mode'])) {	$mode=$_GET['mode']; } else {	$mode="";	}
$st_company=flookup("status_company","mastercompany","company!=''");
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
		echo "Costing"; break;
	case "5L":
		echo "Costing"; break;
	case "6":
		echo "Costing Detail"; break;
	case "7":
		echo "Sales Order"; break;
	case "7L":
		echo "Sales Order"; break;
	case "8":
		echo "Sales Order - Breakdown"; break;
	case "8d":
		echo "Sales Order - Multi Delete"; break;
	case "8mu":
		echo "Sales Order - Multi Update"; break;
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
	case "11v":
		echo "Laporan Costing"; break;
	case "12":
		echo "Work Sheet"; break;
	case "12a":
		echo "Work Sheet"; break;
	case "12L":
		echo "Work Sheet"; break;
	case "12v":
		echo "List Sales Order"; break;
	case "13":
		echo "Laporan Sales Order"; break;
	case "13v":
		echo "Laporan Sales Order"; break;
	case "14":
		echo "Bill Of Material"; break;
	case "14L":
		echo "Bill Of Material"; break;
	case "14d":
		echo "Bill Of Material - Detail"; break;
	case ($mod=="14e" || $mod=="14mu"):
		echo "Bill Of Material - Detail - Update"; break;
	case "15":
		echo "Purchase Requisition"; break;
	case "15L":
		echo "Purchase Requisition"; break;
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
	case "23":
		echo "Upload Costing"; break;
	case "23R":
		echo "Upload Costing"; break;
	case "24":
		echo "Material Additional Info"; break;
	case "25":
		echo "Unlock JO"; break;
	case "updsmv":
		echo "Update SMV"; break;
	case "updsmvl":
		echo "Update SMV"; break;
	case ($mod=="26" || $mod=="26L"):
		echo "Sales Order Sample"; break;
	default:
		break;
}
?>
