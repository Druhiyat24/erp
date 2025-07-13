<?PHP

$mod=$_GET['mod'];

$grafik=flookup("grafik","mastercompany","company!='' ");

switch ($mod) 

{	case "1":

		include "dashboard.php";

		break;
case "GenerateSaldoAwalByAccountAllDay": 

        include "GenerateSaldoAwalByAccountAllDay/HeaderPage.php";        

        break;	
case "14":

		include "m_bank.php";

		break;	

case "99":

        include "m_inv_period.php";

        break;          	

	case "cost":

		include "cost_center.php";

		break;

	case "costdept":

		include "dept.php";

		break;

	case "costsubdept":

		include "subdept.php";

		break;

	/*case "3":

		include "value_sets.php";

		break;

	case "4":

		include "coa_config.php";

		break;*/

	case "coas":

		include "coa_segments.php"; break;

    case "coa":

		include "coa.php"; break;

    case "je":

		include "journal_entry.php"; break;

    case "lminv":

		include "manual_invoice_entry.php"; break;

    case "jefh":

        include "journal_entry_form.php"; break;

    case "jefd":

        include "journal_entry_form_item.php"; break;

    case "jefdcashbank":

        include "journal_entry_form_item_cashbank.php"; break;		

    case "repcoa":

        include "coa_report.php"; break;

   /* case "reptb":

        include "tb_report.php"; break;
*/
    case "lapagingfg": 

        include "LaporanAgingFG/HeaderPage.php";        

        break; 

	case "reptb":

        include "LaporanTrialBalance/HeaderPage.php"; break;		
/* 
    case "repneraca_":

        include "LaporanNeraca/HeaderPage.php"; break; */

    case "repneraca_":

        include "LaporanNeraca2/HeaderPage.php"; break;

    case "penyusutan_at":

        include "LaporanPenyusutanAT/HeaderPage.php"; break;
    case "repbs":

        include "bs_report.php"; break;

    case "5":

        include "pettycash.php"; break;		

    case "reppnl":

        include "pnl_report.php"; break;

    case "BukuBesar":

        include "BukuBesar/HeaderPage.php"; break;			

	case "Ejournal":

        include "Ejournal/HeaderPage.php"; break;	

	case "piutang":

        include "LaporanAwalPiutang/HeaderPage.php"; break;			

    case "accperiod":

        include "accperiod.php"; break;

    case "MasterCurrency":

        include "MasterCurrency/HeaderPage.php"; break;		

    case "LaporanAktivaTetap":

        include "LaporanAktivaTetap/HeaderPage.php"; break;	

/*     case "LaporanCash":

        include "LaporanCashBank/HeaderPage.php"; break; */	
    case "LaporanCash":

       include "LaporanKas/HeaderPage.php"; break;	
	   break;
/*     case "LaporanCashRekap":

        include "LaporanCashBank/HeaderPage.php"; break;	
	   break; */
	       case "LaporanCashRekap":

        include "LaporanKasRekap/HeaderPage.php"; break;	
	   break;
/*     case "LaporanBankRekap":

        include "LaporanCashBank/HeaderPage.php"; break;		
 */
 
     case "LaporanBankRekap":

       include "LaporanBankRekap/HeaderPage.php"; break;		

/*     case "LaporanBank":

        include "LaporanCashBank/HeaderPage.php"; break;		
 */
    case "LaporanBank":

         include "LaporanBank/HeaderPage.php"; break;			

    case "MasterAktiva":

        include "MasterAktiva.php"; break;

    case "MasterAktivaTetap":

        include "MasterAktivaTetap.php"; break;

    case "jp":

        include "journal_purchase_form.php"; break;

    case "kb":

        include "journal_entry_kontra_bon_form.php"; break;		

    case "jg":

        include "journal_general_form.php"; break;

    case "ja":

        include "journal_adjustment_form.php"; break;

    case "js":

        include "journal_entry_form_penjualan.php"; break;

    case "rekarlist":

        include "journal_rekap_ar.php"; break;

    case "rekar":

        include "journal_rekap_ar_form.php"; break;

    case "minv":

        include "manual_invoice_form.php"; break;

    case "EntryCashBank":

        include "journal_entry_form_kash_bank.php"; break;		

    case "EntryBank":

        include "journal_entry_form_bank.php"; break;			

    case "jpay":

        include "journal_payment_form.php"; break;

    case "jrcp":

        include "journal_entry_form_penerimaan_bank.php"; break;

    case "jallocar":

        include "journal_entry_form_penerimaan.php"; break;

    case "jact":

        include "journal_entry_form_aktiva_tetap.php"; break;

    case "agingap":

        include "ap_aging_report.php"; break;

    case "ap":

        include "ap_report.php"; break;

    case "agingar":

        include "LaporanPiutang/HeaderPage.php"; break;

	case "listpayment":

        include "journal_rekap_ap.php"; break;

    case "rekap":

        include "journal_rekap_ap_form.php"; break;		

    case "mtax":

        include "mtax.php";		

		break;

    case "debit_note":

        include "DebitPage.php";		

		break;	

    case "debit2":

        include "DebitPage.php";		

		break;	

    //LAPORAN - LAPORAN AR - Design by MARIO   

    
    case "agingARjatuhtempo":

        include "LaporanAR/aging_jt_ar_bulanan.php";   //MARIO  

        break;

	case "lapagingspr": 

		include "LaporanAgingSPR/HeaderPage.php";       //Daniel 13 Jul 20 

		break; 
		
	case "mutdpcbd": 

		include "LaporanMutasiDPdanCBD/HeaderPage.php";       //Daniel 14 Jul 20 

		break; 
		
	case "lapagingbb": 

		include "LaporanAgingBB/HeaderPage.php";       //Anat 14 Jul 20 

		break; 

    case "aractualvsjt":

        include "LaporanAR/ar_actual_vs_jt.php";   //MARIO  

        break;      

/*     case "arterlambatbayar":

        include "LaporanAR/ar_terlambat_bayar.php";   //MARIO  

        break;      */ 

case "arterlambatbayar":

        include "LaporanPiutangTerlambatBayar/HeaderPage.php";  

        break;		
		
    case "pantauanpgm":

        include "LaporanAR/pantauan_pgm.php";   //MARIO  

        break;      

    case "proyeksivsrealisasi":

        include "LaporanAR/proyeksi_vs_realisasipenerimaan_ar.php";   //MARIO  

        break;      

    case "rincmutar":

        include "LaporanAR/rincian_mutasi_ar.php";   //MARIO  

        break;      

    case "umpelanggan":

        include "LaporanAR/um_pelanggan.php";   //MARIO  

        break;      

    // case "kartuAR": 

    //     include "lap_kartu_AR.php";        

    //     break; 
        

    case "lappiutang": 

        //include "LaporanAR/lap_kartu_AR.php";        
        include "LaporanPiutang/HeaderPage.php";        

        break; 
    

    case "UMPelanggan":

        include "lap_UM_pelanggan.php";        //lap_UM_pelanggan

        break;          

   
	case "mutpilok":

        // include "lap_mutasi_piutang_ekspor.php";        
      include "LaporanMutasiPiutangLokal/HeaderPage.php";     

        break;   
   // case "mutpilok":

     //   include "lap_mutasi_piutang_lokal.php";        

       // break;          

    case "rimutpilok":

        include "LaporanRincianMutasiPiutangLokal/HeaderPage.php";        

        break;              

/*     case "mutpiek":

        // include "lap_mutasi_piutang_ekspor.php";        
        include "LaporanAR/mutasi-piutang-dagang-ekspor.php";        

        break;       */    

    case "mutpiek":

        // include "lap_mutasi_piutang_ekspor.php";        
      include "LaporanMutasiPiutangExport/HeaderPage.php";     

        break;      		
		
	    case "rimutpiek":

       include "LaporanRincianMutasiPiutangExport/HeaderPage.php";        

        break;  	
		
/*     case "rimutpiek":

        // include "lap_rincian_mutasi_piutang_ekspor.php";        
        include "LaporanAR/rincian_mutasi_ar_ekspor.php";        

        break;   //end 9Des19 - Daniel   */     

    // case "kartuAP": //10Des19 - Laporan tambahan AP

    //     include "lap_kartu_AP.php";        

    //     break;       

    case "kartuAP": 

        include "LaporanAP/HeaderPage.php";        

        break;       

    case "lapKontraBon": 

        //include "lap_kontrabon.php";        
  include "LaporanRekapKontraBon/HeaderPage.php";        

        break;       

    case "rimutpii": 

        include "lap_rincian_mutasi_piutang_impor.php";        

        break;       

    case "rikontrabon": 
       // include "lap_rincKontra.php"; 
         include "LaporanRincianKontraBon/HeaderPage.php";         

        break;       

    case "umurutang": 

        //include "lap_umur_utang.php";        
    	include "LaporanAgingHutangJatuhTempo/HeaderPage.php";
        
        break;       

    case "utjatem": 

        include "lap_utang_jatuh_tempo.php";        

        break; //end 10Des19 - Daniel



    case "lapemlok": //

        //include "lap_pembelian_lokal.php";        
        include "LaporanPembelianLokal/HeaderPage.php";        

        break;     
		
	case "lapemgen": //
      
        include "LaporanPembelianGeneral/HeaderPage.php";        

        break; 

    case "lapemim": //

        //include "lap_pembelian_impor.php";        
		include "LaporanPembelianImpor/HeaderPage.php";  

        break; 

    case "repemlok": //

        //include "lap_rekap_pembelian_lokal.php";
		include "LaporanRekapPembelianLokal/HeaderPage.php";          

        break; 

    case "repemim": //

        //include "lap_rekap_pembelian_impor.php";
		include "LaporanRekapPembelianImpor/HeaderPage.php";  

        break; 

    case "retpem": //

        //include "lap_retur_pembelian.php";    
		include "LaporanReturPembelian/HeaderPage.php";  		

        break; 

    //LAPORAN - LAPORAN PENJUALAN ---------------------------------------------------------------

    case "realbiayaekspor": //

        include "LaporanPenjualan/LapRealisasiBiayaEkspor.php";        

        break; 

    case "realbiayaekspor2": //

        include "LaporanPenjualan/LapRealisasiBiayaEkspor2.php";        

        break; 

    case "pantaudebitnote": //

        include "LaporanPenjualan/pantauan_debit_note.php";        

        break; 

    case "pantaupiutangLC": //

        include "LaporanPenjualan/pantauan_piutang_lc.php";        

        break; 

	case "realSO":  // Daniel - 28 Mei 2020

        include "LaporanRealisasiSo/HeaderPage.php";        

        break;	

    case "pantaureturj": //

        include "LaporanPantauanReturPenjualan/HeaderPage.php";        

        break; 

    case "rincjlok": //

        include "LaporanRincianPenjualanLokal/HeaderPage.php";        

        break; 

    case "rincjeks": //

        include "LaporanRincianPenjualanExport/HeaderPage.php";        

        break; 

    case "rekjlok": //

        include "LaporanRekapPenjualanLokal/HeaderPage.php";        

        break; 

    case "rekjeks": //

        include "LaporanRekapPenjualanExport/HeaderPage.php";

        break; 

    case "retpotj": //

        include "LaporanReturPotonganPenjualan/HeaderPage.php";        

        break;  

    case "rekakj": //

        include "LaporanPenjualan/rekap_akumulasi_penjualan.php";        

        break;  


	case "jualakumulasi":

		include "LaporanRekapAkumulasiPenjualan/HeaderPage.php";
    
		break;		

    case "rekakpenerimaAR": //

        include "LaporanPenjualan/rekap_akumulasi_penerimaan_ar.php";        

        break; 
    //-------------------------------------------------------------------------------------------
    case "lapjur": //20200114

        include "LaporanJurnal/HeaderPage.php";        

        break; 

    case "TrackBpb": //20200114 UpdateKontraBon

        include "TrackBpb/HeaderPage.php";        

        break; 

    case "TrackInvoice": //20200114 UpdateKontraBon

        include "TrackInvoice/HeaderPage.php";        

        break; 


    case "UpdateKontraBon": //20200114 UpdateKontraBon

        include "UpdatekontraBon/HeaderPage.php";        

        break; 

    case "getjournal": 

        include "UpdatePembelian/HeaderPage.php";        

        break; 
    case "UpdatePenjualan": 

        include "UpdatePenjualan/HeaderPage.php";        

        break; 

    case "UpdateReturPengeluaran": 

        include "UpdateReturPengeluaran/HeaderPage.php";        

        break; 

		
    case "UpdateReverse": 

        include "UpdateReverse/HeaderPage.php";        

        break; 
        
//------------------------------ Laporan Produksi
     case "agingbb": 

        include "LaporanProduksi/aging-bb.php";        

        break; 
      
    case "agingbp": 

        include "LaporanProduksi/aging-bp.php";        

        break; 
    
    case "agingfg": 

        include "LaporanProduksi/aging-fg.php";        

        break; 

    case "agingsparepart": 

        include "LaporanProduksi/aging-sparepart.php";        

        break; 
        
    case "arusbarangbbdanbp": 

        include "LaporanProduksi/arus-barang-bbdanbp.php";        

        break;  
        
    case "arusfgharian": 

        include "LaporanProduksi/arus-fg-harian.php";        

        break; 
        
    case "arusfg": 

        include "LaporanProduksi/arus-fg.php";        

        break; 
        
    case "arussparepartatkdanbarangumum": 

        include "LaporanProduksi/arus-sparepat-atkdanbarangumum.php";        

        break;  

    case "biayacostcenterbudgetaktual": 

        include "LaporanProduksi/biaya-cost-center-budget-aktual.php";        

        break;  
        
    case "detailtransaksimutasiwip": 

        include "LaporanProduksi/detail-transaksimutasi-WIP.php";        

        break; 
     
    case "endingstockbbdanbpperpic": 

        include "LaporanProduksi/ending-stock-bb-dan-bp-per-pic.php";        

        break; 

    case "grossmarginperkp": 

        include "LaporanProduksi/gross-margin-per-kp.php";        

        break;   
        
    case "idproses": 

        include "LaporanProduksi/id-proses.php";        

        break; 
     
    case "labarugibersihperkp": 

        include "LaporanProduksi/laba-rugi-bersih-per-kp.php";        

        break; 
        
    case "lapbiayaallcostcenter": 

        include "LaporanProduksi/lap-biaya-all-cost-center.php";        

        break;   
        
    case "lappemakaianmaterialsisa": 

        include "LaporanProduksi/lap-pemakaian-material-sisa.php";        

        break;   
     
    case "lapswitchingbarangbooking": 

        include "LaporanProduksi/lap-switching-barang-booking.php";        

        break;       
     
    case "laporanhasilstokname": 

        include "LaporanProduksi/laporan-hasil-stokname.php";        

        break; 
        
    case "laporanwipakhir": 

        include "LaporanProduksi/laporan-wip-akhir.php";        

        break;  
        
    case "mutasibrgditempatmakloon": 

        include "LaporanProduksi/mutasi-brg-ditempat-makloon.php";        

        break;     

    case "outputprodukperkpperproses": 

        include "LaporanProduksi/output-produk-kp-per-proses.php";        

        break; 

    case "pantauanmakloonbelivsjual": 

        include "LaporanProduksi/pantauan-makloon-beli-vs-jual.php";        

        break; 

    case "pemakaianmaterialrmdandm": 

        include "LaporanProduksi/pemakaianmaterial-rm-dan-dm.php";        

        break; 
     
    case "pembebananfohperkp": 

        include "LaporanProduksi/pembebanan-foh-per-kp.php";        

        break;     

    case "pembebanantklperkp": 

        include "LaporanProduksi/pembebanan-tkl-per-kp.php";        

        break; 

    case "perhitungancogmperws": 

        include "LaporanProduksi/perhitungan-cogm-per-ws.php";        

        break; 
        
    case "rekaparusbarang": 

        include "LaporanProduksi/rekap-arusbarang.php";        

        break; 
        
    case "rincianpemakaianspatkumum": 

        include "LaporanProduksi/rincian-pemakaian-sp-atk-umum.php";        

        break;   
        
    case "rincianpemakaianspartspermesin": 

        include "LaporanProduksi/rincian-pemakaian-sparts-per-mesin.php";        

        break;  
        
    case "rmdanidmpersatuanoutput": 

        include "LaporanProduksi/rm-dan-idm-persatuan-output.php";        

        break; 
        
    case "standarwaktuproses": 

        include "LaporanProduksi/standar-waktu-proses.php";        

        break;     

//------------------------------ Laporan Produksi

//------------------------------ Laporan Cash dan Bank

case "bukubank": 

        include "LaporanCashdanBank/buku-bank.php";        

        break;  
 
case "bukukas": 

        include "LaporanCashdanBank/buku-kas.php";        

        break;  

 case "bungaloanbank": 

        include "LaporanCashdanBank/bunga-loan-bank.php";        

        break; 

case "rekapbukubank": 

        include "LaporanCashdanBank/rekap-buku-bank.php";        

        break;  
    
case "rekapbukukas": 

        include "LaporanCashdanBank/rekap-buku-kas.php";        

        break;          
    case "rincmuthutdaglok": 

        //include "LaporanAP/RincianMutasiHutangDagangLokal/HeaderPage.php";        
        include "LaporanRincianMutasiHutangLokal/HeaderPage.php"; 
break;


case "muthutdaglok": 

        include "LaporanMutasiHutangLokal/HeaderPage.php";        

        break;  
		
case "rincmuthutdagimp": 

        include "LaporanRincianMutasiHutangExport/HeaderPage.php";        

        break;   		
		
		
case "muthutdagimp": 

        include "LaporanMutasiHutangExport/HeaderPage.php";        

        break;   		
	
case "hutdagterbay": 

        include "LaporanHutangTerlambatBayar/HeaderPage.php";        

        break;			
case "MasterSaldoAwal": 

        include "MasterSaldoAwal/HeaderPage.php";        

        break;	
//------------------------------ Laporan Cash dan Bank
//------------------------------------------LAPORAN PENERIMAAN    -- Daniel -- 26 Mei 2020    

case "maklonpenerimaaninternal": 

        include "LaporanMaklonPenerimaanInternal/HeaderPage.php";        

        break;	

case "maklonpenerimaanexternal": 

        include "LaporanMaklonPenerimaanExternal/HeaderPage.php";        

        break;
//--------------------------------------------------------------


case "mutasiwip":

    include "LaporanMutasiWIP/HeaderPage.php";
     

        break; 

    default:

		echo "<h1>Halaman tidak tersedia</h1>";

		break;

}

?>
