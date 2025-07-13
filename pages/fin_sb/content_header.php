<?PHP

$mod=$_GET['mod'];

$nm_company=flookup("company","mastercompany","company!=''");

$st_company=flookup("status_company","mastercompany","company!=''");

if (isset($_GET['rptid'])) { $rpt=$_GET['rptid']; } else { $rpt=""; }

if (isset($_GET['mode'])) {	$mode=$_GET['mode']; } else {	$mode="";	}

switch ($mod) 

{	case "1":

		echo "Dashboard"; break;

	case "cost":

		echo "Master Cost Center"; break;

	case "5":

		echo "Pety Cash"; break;		

	case "costdept":

		echo "Master Department"; break;

	case "costsubdept":

		echo "Master Sub Department"; break;

	case "4":

		echo "Chart of Account Config"; break;

	case "coas":

		echo "Chart of Account Segments"; break;

	case "MasterCurrency":

		echo "Master Currency";break;

	case "MasterAktiva":

		echo "Master Aktiva";break;

	case "MasterAktivaTetap":

		echo "Master Tipe Aktiva";break;

	case "coa":

		echo "Chart of Accounts"; break;

	case "je":

		echo "Journal Entries"; break;

	case "jefh":

		echo "Create Journal Entry"; break;

	case "jefd":

		echo "Create Journal Entry Item"; break;

	case "EntryCashBank":

		echo "Create Journal Cash "; break;		

	case "EntryBank":

		echo "Create Journal Bank "; break;				

	case "repcoa":

		echo "Bagan Akun"; break;

	case "repgl":

		echo "Jurnal Umum"; break;

	case "reptb":

		echo "Trial Balance"; break;

	case "rincmuthutdaglok":

		echo "Laporan Rincian Mutasi Hutang Lokal "; break;			
		
		
	case "repbs":

		echo "Neraca"; break;

	case "reppnl":

		echo "Laba Rugi"; break;

	case "BukuBesar":

		echo "Buku Besar"; break;		

	case "Ejournal":

		echo "Ejournal"; break;			

	case "accperiod":

		echo "Create Accounting Period"; break;

	case "jp":

		echo "Create Journal Pembelian"; break;

	case "jg":

		echo "Create General Journal"; break;

	case "ja":

		echo "Create Adjustment Journal"; break;

	case "js":

		echo "Create Account Receivable"; break;

	case "rekarlist":

		echo "Rekap AR"; break;

	case "rekar":

		echo "Create Rekap AR"; break;

	case "minv":

		echo "Create Manual Invoice"; break;

	case "jpay":

		echo "Create Payment Journal"; break;

	case "jrcp":

		echo "Create Receipt Journal"; break;

	case "jallocar":

		echo "Create Allocation AR Journal"; break;

	case "jact":

		echo "Create Activa Journal"; break;

	case "agingap":

		echo "Umur Hutang"; break;

	case "piutang":

		echo "Piutang"; break;		

	case "ap":

		echo "Hutang"; break;

    case "agingar":

        echo "Umur Piutang"; break;

	case "LaporanCash":

        echo "Laporan Cash "; break;

	case "LaporanCashRekap":

        echo "Laporan Cash Rekap"; break;		

	case "LaporanBank":

        echo "Laporan Bank "; break;	

	case "LaporanBankRekap":

        echo "Laporan Bank Rekap"; break;

	case "listpayment":

        echo "List Payment"; break;

	case "kartuAR": //9Des19

        echo "Laporan Kartu Piutang"; break;

	case "agingARjatuhtempo":

        echo "Laporan Aging AR dan Jatuh Tempo"; break;

	case "UMPelanggan":

        echo "Laporan Mutasi Uang Muka Pelanggan"; break;

	case "mutpilok":

        echo "Laporan Mutasi Piutang Lokal"; break;

	case "rimutpilok":

        echo "Laporan Rincian Mutasi Piutang Lokal"; break;

	case "mutpiek":

        echo "Laporan Mutasi Piutang Ekspor"; break;

	case "rimutpiek":

        echo "Rincian Laporan Mutasi Piutang Ekspor"; break; //end 9Des19 - Daniel

	case "kartuAP": //10Des19

        echo "Laporan Kartu Hutang"; break; 

	case "lapKontraBon":

        echo "Laporan Kontra Bon"; break; 

	case "rimutpii":

        echo "Laporan Rincian Mutasi Piutang Impor"; break; 

	case "rikontrabon":

        echo "Laporan Rincian Kontra Bon"; break; 

	case "umurutang":

        echo "Laporan Umur Hutang"; break; 

	case "utjatem":

        echo "Laporan Hutang Jatuh Tempo"; break;  //end 10Des19 - Daniel

	case "getjournal":

        echo "Tarik Journal Pembelian manual"; break;  

	case "lapemgen":

        echo "Laporan Pembelian General"; break;  //7Jan20 - Daniel
		
	case "lapemlok":

        echo "Laporan Pembelian Lokal"; break;  //7Jan20 - Daniel

	case "lapemim":

        echo "Laporan Pembelian Impor"; break;  //7Jan20 - Daniel

	case "repemlok":

        echo "Rekap Pembelian Lokal"; break;  //7Jan20 - Daniel

	case "repemim":

        echo "Rekap Pembelian Impor"; break;  //7Jan20 - Daniel

	case "retpem":

        echo "Retur Pembelian"; break;  //7Jan20 - Daniel


	case "rincjlok":

        echo "Laporan Rincian Penjualan Lokal"; break;  //19Mei20 - Daniel

	case "rincjeks":

        echo "Laporan Rincian Penjualan Ekspor"; break;  //19Mei20 - Daniel

	case "lapjur":

        echo "Laporan Jurnal"; break;  //14Jan20 - Daniel
		
	case "TrackBpb":

        echo "Track Bpb"; break;  //7Jan20 - Daniel

	case "TrackInvoice":

        echo "Track Invoice"; break;  //14Jan20 - Daniel		
		
	case "arterlambatbayar":

         echo "Laporan Piutang Dagang Terlambat Bayar"; break;  //14Jan20 - Daniel	  

        break;	
	
		case "muthutdaglok":

         echo "Laporan Mutasi Hutang Dagang Lokal"; break;  //14Jan20 - Daniel	  

        break;	


		case "rincmuthutdagimp":

         echo "Laporan Rinciang Mutasi Hutang Dagang Import"; break;  //14Jan20 - Daniel	  

        break;			
		
		case "muthutdagimp":

         echo "Laporan Mutasi Hutang Dagang Import"; break;  //14Jan20 - Daniel	  

        break;		

case "hutdagterbay": 

        echo "Laporan Hutang Terlambat Bayar"; break;  //14Jan20 - Daniel	  
    
        break;				
		
    default:

		break;

}

?>

