<?PHP
$mod=$_GET['mod'];
$nm_company=flookup("company","mastercompany","company!=''");
$st_company=flookup("status_company","mastercompany","company!=''");
if (isset($_GET['rptid'])) { $rpt=$_GET['rptid']; } else { $rpt=""; }
if (isset($_GET['mode'])) {	$mode=$_GET['mode']; } else {	$mode="";	}
switch ($mod)
{	case "1":
		echo "HR"; break;
	case "2":
		echo "Data Karyawan"; break;
	case "2L":
		echo "Data Karyawan"; break;
	case "3":
		echo "Master Lokasi"; break;
	case "3L":
		echo "Master Lokasi"; break;
	case "4":
		echo "Master Jabatan"; break;
	case "5":
		echo "Data Keluarga Karyawan"; break;
	case "5L":
		echo "Data Keluarga Karyawan"; break;
	case "6":
		echo "Data PTKP"; break;
	case "7":
		echo "Upload Absensi"; break;
	case "8":
		if ($mode=="LapRek")
		{	echo "Laporan Rekalkulasi"; break;	}
		else if ($mode=="LapRekS")
		{	echo "Slip Gaji"; break;	}
		else if ($mode=="LapRAbs")
		{	echo "Laporan Absen"; break;	}
		else if ($mode=="LapOT")
		{	echo "Laporan Summary Lembur"; break;	}
		else if ($mode=="LapOTD")
		{	echo "Laporan Detail Lembur"; break;	}
		else if ($mode=="LapSPL")
		{	echo "Laporan SPL"; break;	}
		else
		{	echo "Laporan Absensi"; break;	}
	case "9":
		echo "Laporan Absensi"; break;
	case "10":
		echo "Master Kode Absen"; break;
	case "11":
		echo "Data Hari Libur"; break;
	case "12":
		echo "Data Salary Karyawan"; break;
	case "13":
		echo "Input Absensi Karyawan"; break;
	case "13L":
		echo "Input Absensi Karyawan"; break;
	case "14":
		echo "Data Karyawan Keluar"; break;
	case "15":
		echo "Rekalkulasi Gaji"; break;
	case "16":
		echo "Master Department"; break;
	case "16L":
		echo "Master Department"; break;
	case "17":
		echo "Master Line"; break;
	case "17L":
		echo "Master Line"; break;
	case "18":
		echo "SPL"; break;
	case "18e":
		echo "SPL"; break;
	case "18L":
		echo "SPL"; break;
	case "19":
		echo "Master Bagian"; break;
	case "20":
		echo "Input Ijin Karyawan"; break;
	case "20L":
		echo "Input Ijin Karyawan"; break;
	case "21":
		echo "Laporan Hasil Rekalkulasi"; break;
	case "22":
		echo "Laporan Lembur"; break;
	case "23L":
		echo "Input Potongan Lain Lain"; break;
	case "23":
		echo "Input Potongan Lain Lain"; break;
	case "24L":
		echo "Input Pendapatan Lain Lain"; break;
	case "24":
		echo "Input Pendapatan Lain Lain"; break;
	case "25":
		echo "Laporan Absen"; break;
	case "26":
		echo "Setting Payroll"; break;
	case "27":
		echo "Slip Gaji"; break;
	case "28":
		echo "Parameter Payroll"; break;
	case "29":
		echo "Apply Karyawan"; break;
	case "29a":
		echo "Form Document"; break;
	case "29i":
		echo "Form Interview Karyawan"; break;
	case "29p":
		echo "Form Permintaan TK"; break;
	case "30":
		echo "Laporan SPL"; break;
	case "35DP":
	  echo "Data Jam Kerja dan Shift"; break;
	 case "35DPL":
	   echo "Data Jam Kerja dan Shift"; break;
	 case "36t":
	   echo "Data Tunjangan Masa Kerja"; break;
	 case "36TL":
	   echo "Data Tunjangan Masa Kerja"; break;
	 case "37":
	   echo "Data Master Grade"; break;   
	 case "37L":
	   echo "Data Master Grade"; break; 
	 
 case "m17":
   echo "Data Mutasi Karyawan"; break;
 case "m17a":
   echo "Data Mutasi Karyawan"; break;
 case "m17i":
   echo "Data Mutasi Karyawan"; break;
 case "m17p":
   echo "Input Mutasi Karyawan"; break;

 case "f10p":
   echo "Data Ijin Karyawan"; break;
 
}
?>
