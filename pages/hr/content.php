<?PHP
$mod=$_GET['mod'];
$grafik=flookup("grafik","mastercompany","company!='' ");
switch ($mod)
{	case "1":
		break;
	case "2":
		include "emp.php"; break;
	case "2L":
		include "emp.php"; break;
	case "3":
		include "m_lokasi.php"; break;
	case "3L":
		include "m_lokasi.php"; break;
	case "4":
		include "m_jabat.php"; break;
	case "5":
		include "emp_fam.php"; break;
	case "5L":
		include "emp_fam.php"; break;
	case "6":
		include "ptkp.php"; break;
	case "7":
		include "import_abs.php"; break;
	case "8":
		include "laporan.php"; break;
	case "9":
		include "lap_abs.php"; break;
	case "10":
		include "m_abs.php"; break;
	case "11":
		include "m_hol.php"; break;
	case "12":
		include "m_sal.php"; break;
	case "13":
		include "manual_abs.php"; break;
	case "13L":
		include "manual_abs.php"; break;
	case "14":
		include "emp_out.php"; break;
	case "15":
		include "rekal.php"; break;
	case "16":
		include "m_dept.php"; break;
	case "16L":
		include "m_dept.php"; break;
	case "17":
		include "m_line.php"; break;
	case "17L":
		include "m_line.php"; break;
	case "18":
		include "spl.php"; break;
	case "18e":
		include "spl_ed.php"; break;
	case "18L":
		include "spl.php"; break;
	case "19":
		include "m_bagian.php"; break;
	case "20":
		include "ijin_kary.php"; break;
	case "20L":
		include "ijin_kary.php"; break;
	case "21":
		include "lap_slip.php"; break;
	case "22":
		include "lap_ot.php"; break;
	case "23L":
		include "deduct.php"; break;
	case "23":
		include "deduct.php"; break;
	case "24L":
		include "backpay.php"; break;
	case "24":
		include "backpay.php"; break;
	case "25":
		include "lap_rabs.php"; break;
	case "26":
		include "set_pay.php"; break;
	case "27":
		include "lap_slip2.php"; break;
	case "28":
		include "param_pay.php"; break;
	case "29":
		include "apply_emp.php"; break;
	case "29a":
		include "apply_emp.php"; break;
	case "29i":
		include "apply_emp.php"; break;
	case "29p":
		include "apply_emp.php"; break;
	case "30":
		include "lap_spl.php"; break;
	case "31A":
		include "KontrakKerjaForm/HeaderPage.php"; break;		
	case "31P":
		include "KontrakKerjaForm/pdf/index.php"; break;			
	case "31":
		include "KontrakKerjaForm/HeaderForm.php"; break;	
	case "32A":
		include "SuratPengunduranDiriForm/HeaderPage.php"; break;	
	case "32P":
		include "SuratPengunduranDiriForm/pdf/index.php"; break;			
	case "32":
		include "SuratPengunduranDiriForm/HeaderForm.php"; break;	
	case "33A":
		include "SuratKeteranganKerjaForm/HeaderPage.php"; break;	
	case "33P":
		include "SuratKeteranganKerjaForm/pdf/index.php"; break;		
	case "33":
		include "SuratKeteranganKerjaForm/HeaderForm.php"; break;
	case "34A":
		include "PerjanjianKerjaForm/HeaderPage.php"; break;	
	case "34P":
		include "PerjanjianKerjaForm/pdf/index.php"; break;			
	case "34":
		include "PerjanjianKerjaForm/HeaderForm.php"; break;			
	case "35DP":
	  include "datajam.php"; break;
	 case "35DPL":
	   include "datajam.php"; break;
	 case "36t":
	   include "d_tmk.php"; break;
	 case "36TL":
	   include "d_tmk.php"; break;
	 case "37":
	   include "d_grade.php"; break;  
	 case "37L":
	   include "d_grade.php"; break;  
   case "m17":
   include "mutemply.php"; break;#
 case "m17a":
   include "mutemply.php"; break;
 case "m17p":
   include "mutemply.php"; break;
 case "m17i":
   include "mutemply.php"; break;
 case "f10p":
   include "ijin_kary.php"; break;
   default:
		echo "<h1>Halaman tidak tersedia</h1>"; break;
}
?>