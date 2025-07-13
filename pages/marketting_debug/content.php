<?PHP
$mod=$_GET['mod'];
$grafik=flookup("grafik","mastercompany","company!='' ");
switch ($mod) 
{	case "1":
		include "dashboard2.php"; break;
	case "monorder":
		include "lap_mon_order.php";
		break;
	case "monorderv":
		include "lap_mon_ord_view.php";
		break;
	case "2":
		include "m_bom.php";
		break;
	case "3":
		include "quote_inq.php";
		break;
	case "4":
		include "precost.php";
		break;
	case "5":
		if ($jenis_company=="VENDOR LG")
		{	include "actcost_lg.php"; break;	}
		else
		{	include "actcost.php"; break;	}
	case "5L":
		if ($jenis_company=="VENDOR LG")
		{	include "actcost_lg.php"; break;	}
		else
		{	include "actcost.php"; break;	}
	case "5e":
		include "pdfCost.php"; break;
	case "5x":
		include "xlsCost.php"; break;
	case "6":
		include "add_item_cs.php";
		break;
	case ($mod=="7" || $mod=="7L"):
		if ($jenis_company=="VENDOR LG")
		{	include "sales_ord_lg.php"; break;	}
		else
		{	include "sales_ord.php"; break;	}
	case "8d":
		if ($jenis_company=="VENDOR LG")
		{	include "sales_ord_lg.php"; break;	}
		else
		{	include "sales_ord.php"; break;	}
	case "8mu":
		include "sales_ord_mul_update.php"; break;
	case "7p":
		include "pdfSO.php";
		break;
	case "8":
		include "sales_ord_det.php";
		break;
	case "9":
		include "laporan.php";
		break;
	case "9v":
		include "lap_quote.php";
		break;
	case "10":
		include "laporan.php";
		break;
	case "10v":
		include "lap_pre.php";
		break;
	case "11":
		include "laporan.php";
		break;
	case "11v":
		include "lap_cost.php";
		break;
	case "12":
		include "ws.php"; break;
	case "12a":
		include "ws_upl.php"; break;
	case "cekclose":
	  include "cek_close_ws.php"; break;
	 case "vclose":
	  include "view_cek_close_ws.php"; break;
	 case "12L":
		include "ws.php"; break;
	case "12v":
		include "list_so.php";
		break;
	case "13":
		include "ws.php";
		break;
	case "13v":
		include "list_so.php";
		break;
	case "14":
		include "bom_jo.php"; break;
	case "14md":
		include "bom_jo.php"; break;
	case "14mu":
		include "bom_jo_mul_upd.php"; break;
	case "14L":
		include "bom_jo.php"; break;
	case "14d":
		if ($jenis_company=="VENDOR LG")
		{include "bom_jo_item_lg.php";break;}
		else
		{include "bom_jo_item.php";break;}
	case "14e":
		include "bom_jo_item_ed.php";break;
	case "15":
		if ($jenis_company=="VENDOR LG")
		{include "pr_lg.php"; break;}
		else
		{include "pr.php"; break;}
	case "15L":
		include "pr.php"; break;
	case "15LC":
		include "pr.php"; break;
	case "15C":
		include "pr_cons.php"; break;
	case "16":
		include "brosur.php";
		break;
	case "17":
		include "laporan.php";
		break;
	case "17v":
		include "lap_so.php";
		break;
	case "18":
		include "laporan.php"; break;
	case "18v":
		include "lap_cost_vs_so.php"; break;
	case "19":
		include "laporan.php"; break;
	case "19v":
		include "lap_hist.php"; break;
	case "20":
		include "un_cost.php"; break;
	case "21":
		include "un_so.php"; break;
	case "22L":
		include "lap_mst_bb.php"; break;
	case "23":
		include "upload_costing.php"; break;
	case "23U":
		include "pro_upload_costing.php"; break;
	case "23R":
		include "upload_costing.php"; break;
	case "23S":
		include "s_upload_costing.php"; break;
	case "24":
		include "m_desc_add.php"; break;
	case "25":
		include "un_jo.php"; break;
	case "updsmv":
		include "upd_smv.php"; break; 
	case "updsmvl":
		include "upd_smv.php"; break; 
	#TAUFAN
	 case "22DEV":
  include "bom_jo_dev.php"; break;
 case "22LL":
  include "bom_jo_dev.php"; break;
 case "22d":
  include "bom_jo_dev_item.php";break;
 case "22e":
  include "bom_jo_item_ed_dev.php";break;
 case "23DEV":
  include "ws_dev.php"; break;
 case "23L":
  include "ws_dev.php"; break;
 case "23v":
  include "list_so_dev.php"; break;
 case "24DEV":
  include "pr_dev.php"; break;
 case "24L":
  include "pr_dev.php"; break;
 case "x9":
  include "pr_dev.php"; break;
 case "request1v":
  include "request_dev.php"; break; 
 case "request1":
  include "request_dev.php"; break; 

 case "2X":
  include "sample_development.php"; break;
 case "1X":
  include "sample_development.php"; break;
 case "12SO":
  include "so_development.php"; break;
 case "12vSO":
  include "so_development.php"; break;
 case "12A":
  include "sales_ord_dev.php";
  break;  
  default:
		echo "<h1>Halaman tidak tersedia</h1>";
		break;
}
?>