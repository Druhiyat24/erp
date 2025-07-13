<?PHP

if (empty($_SESSION['username'])) { header("location:../../"); }

if (!isset($_SESSION['username'])) { header("location:../../"); }

$nm_company=flookup("company","mastercompany","company<>''");

$st_company=flookup("status_company","mastercompany","company<>''");

if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../"); }

if ($st_company=="KITE") { $captupl="Upload Data"; } else { $captupl="Upload Data Dari ModulTPB"; }

$rsUser=mysql_fetch_array(mysql_query("select * from 

  userpassword where username='$user'"));



  ?>

  <style type="text/css">
    .dropdown-submenu {
      position: relative;
    }

    .dropdown-submenu>.dropdown-menu {
      top: 0;
      left: 100%;
      margin-top: -6px;
      margin-left: -1px;
      -webkit-border-radius: 0 6px 6px 6px;
      -moz-border-radius: 0 6px 6px;
      border-radius: 0 6px 6px 6px;
    }

    .dropdown-submenu:hover>.dropdown-menu {
      display: block;
    }

    .dropdown-submenu>a:after {
      display: block;
      content: " ";
      float: right;
      width: 0;
      height: 0;
      border-color: transparent;
      border-style: solid;
      border-width: 5px 0 5px 5px;
      border-left-color: #ccc;
      margin-top: 5px;
      margin-right: -10px;
    }

    .dropdown-submenu:hover>a:after {
      border-left-color: #fff;
    }

    .dropdown-submenu.pull-left {
      float: none;
    }

    .dropdown-submenu.pull-left>.dropdown-menu {
      left: -100%;
      margin-left: 10px;
      -webkit-border-radius: 6px 0 6px 6px;
      -moz-border-radius: 6px 0 6px 6px;
      border-radius: 6px 0 6px 6px;
    }
  </style>

  <nav class="navbar navbar-static-top">

    <div class="container">

      <div class="navbar-header">

        <a href="?mod=1" class="navbar-brand"><b><?php $nm_company  //echo 'XYZ Garment';$nm_company;?></b></a>

        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">

          <i class="fa fa-bars"></i>

        </button>

      </div>

      <div class="collapse navbar-collapse pull-left" id="navbar-collapse">

        <ul class="nav navbar-nav">

          <li class="dropdown">

            <a href="" class="dropdown-toggle" data-toggle="dropdown">Master<span class="caret"></span></a>

            <ul class="dropdown-menu" role="menu">

              <?php

            // TODO: link dengan akses yang sesuai

              $akses = $rsUser["finance"];



              if ($rsUser["F_M_Acc_Period"]=="1"){ 

               echo "<li><a href='?mod=accperiod'>Accounting Period</a></li>";          

             }

             if ($rsUser["F_M_Cost_Cent"]=="1"){ 

               echo "<li><a href='?mod=cost'>Cost Center</a></li>";          

             }

             if ($rsUser["F_M_Department"]){ 

               echo "<li><a href='?mod=costdept'>Department</a></li>                                  ";          

             }

             if ($rsUser["F_M_Sub_Dep"]=="1"){ 

               echo "<li><a href='?mod=costsubdept'>Sub Department</a></li>";          

             }

      //   if ($rsUser["F_M_Sub_Dep"]=="1"){ echo "       <!-- <li><a href='?mod=3'>Value Sets</a></li> -->                                  ";         }

      //   if ($rsUser["F_M_COA_Segmen"]=="1"){ echo "       <!-- <li><a href='?mod=4'>Chart of Account Config</a></li> -->              ";         }

             if ($rsUser["F_M_COA_Segmen"]=="1"){ 

               echo "<li><a href='?mod=coas'>Chart of Account Segments</a></li>";

             }

             if ($rsUser["F_M_COA"]=="1"){ 

               echo "<li><a href='?mod=coa'>Chart of Accounts</a></li>";         

             }

             if ($rsUser["F_M_Tipe_Aktiva"]=="1"){ 

               echo "<li><a href='?mod=MasterAktivaTetap'>Master Tipe Aktiva</a></li>";

             }

	//	if ($akses=="1"){ echo " <!--		<li><a href='?mod=MasterCurrency'>Master Currency</a></li> -->      ";         }

             if ($rsUser["F_M_Bank"]=="1")

               { echo "<li><a href='?mod=14'>Master Bank</a></li> ";}

             if ($rsUser["F_M_Tax__Rate"]=="1")  

              { echo "  <li><a href='?mod=mtax'>Master TAX</a></li> ";}

            

            ?>

          </ul>

        </li>

        <li class="dropdown">

          <a href="" class="dropdown-toggle" data-toggle="dropdown">Proses<span class="caret"></span></a>

          <ul class="dropdown-menu" role="menu">

            <?php 

            $akses = $rsUser["finance"];

            if ($rsUser["F_P_List_Jurnal"]=="1"){ echo " <li><a href='?mod=je'>List Journal</a></li>";}

//            if ($akses=="1"){ echo "    <li><a href='?mod=lminv'>List Manual Invoice</a></li>";}

            if ($rsUser["F_P_Rekap_AR"]=="1"){ echo "    <li><a href='?mod=rekarlist'>List Rekap AR</a></li>";}

            if ($rsUser["F_P_List_Pay"]=="1"){ echo "    <li><a href='?mod=listpayment'>List Payment</a></li>";}

            if ($akses=="1"){ echo "    <li><hr style='padding:0;margin:0;'></li>";}

//            if ($akses=="1"){ echo "    <li><a href='?mod=minv'>Create Manual Invoice</a></li>						";}

            if ($rsUser["F_P_Kontrabon"]=="1"){ echo "    <li><a href='?mod=kb'>Kontra Bon</a></li>						";}

            //if ($akses=="1"){ echo "    <li><a href='?mod=js'>Account Receivable</a></li>				";}

            if ($rsUser["F_P_J_Umum"]=="1"){ echo "    <li><a href='?mod=jg'>Entry Journal Umum</a></li>               ";}

            if ($rsUser["F_P_Adjustment"]=="1"){ echo "    <li><a href='?mod=ja'>Entry Journal Penyesuaian</a></li>		";}	

            if ($rsUser["F_P_Pembayaran"]=="1"){ echo "    <li><a href='?mod=jpay'>Entry Journal Pembayaran</a></li>		";}

            if ($rsUser["F_P_Penerimaan"]=="1"){ echo "    <li><a href='?mod=jrcp'>Entry Journal Penerimaan</a></li>";}			

            if ($rsUser["F_P_Alokasi_AR"]=="1"){ echo "    <li><a href='?mod=jallocar'>Entry Journal Alokasi AR</a></li>		";}

            if ($rsUser["F_P_Fixed_Asset"]=="1"){ echo "    <li><a href='?mod=jact'>Entry Journal Aktiva Tetap</a></li>		";}

            if(($rsUser["F_P_Cash_Pabrik"]) || ($rsUser["F_P_Cash_Kantor"]) || ($rsUser["F_P_Cash_Besar"])){

              echo "	<li><a href='?mod=EntryCashBank'>Entry Journal Cash</a></li>	";	

            }			

            if ($rsUser["F_P_Bank"]=="1"){

              echo "	<li><a href='?mod=EntryBank'>Entry Journal Bank</a></li>	";	

            }
            if ($rsUser["F_P_Debit_Note"]=="1"){ echo "    <li><a href='?mod=debit_note'>Entry Debit Note</a></li>		";}

                 //EntryCashBank
            /*$akses = $rsUser["mnuMasterItem"];

            if ($akses=="1") 

            { echo "<li><a href='?mod=7'>Parked Journal</a></li>"; }*/

            ?>

          </ul>

        </li>

        <li class="dropdown">

          <a href="" class="dropdown-toggle" data-toggle="dropdown">Laporan<span class="caret"></span></a>

          <ul class="dropdown-menu" role="menu">

            <?php 

            $akses = $rsUser["mnuMasterItem"];

         //   if ($akses=="1")

		//		 { echo "<li><a href='?mod=5&mode=PC'>Pety Cash</a></li>"; }

            if ($rsUser["FIN_L_T_BPB"]=="1") 

             { echo "<li><a href='?mod=TrackBpb'>Track Bpb</a></li>"; }

           if ($rsUser["FIN_L_T_INVOICE"]=="1") 

             { echo "<li><a href='?mod=TrackInvoice'>Track Invoice</a></li>"; }


          /*if ($rsUser["F_L_Bagan_Akun"]=="1") 

             { echo "<li><a href='?mod=repcoa'>Bagan Akun</a></li>"; }

           if ($rsUser["F_L_T_Balance"]=="1") 

            { echo "<li><a href='?mod=reptb'>Trial Balance</a></li>"; }
*/
          if ($rsUser["F_L_Neraca"]=="1") 

            { echo "<li><a href='?mod=repneraca_'>Neraca</a></li>"; }
			
			
          if ($rsUser["F_L_PEN_AT"]=="1") 

            { echo "<li><a href='?mod=penyusutan_at'>Penyusutan AT</a></li>"; }			
/*
          if ($rsUser["F_L_Laba_Rugi"]=="1") 

            { echo "<li><a href='?mod=reppnl'>Laba Rugi</a></li>"; }

          if ($rsUser["F_L_Buku_Besar"]=="1") 

            { echo "<li><a href='?mod=BukuBesar'>Buku Besar</a></li>"; }		

          if ($akses=="1") 

            { //echo "<li><a href='?mod=Ejournal'>Ejournal</a></li>";

        }
/*
        if ($rsUser["F_L_Umur_Utang"]=="1")

          { echo "<li><a href='?mod=agingap'>Umur Hutang</a></li>"; }

        if ($rsUser["F_L_Utang"]=="1")

          { echo "<li><a href='?mod=ap'>Hutang</a></li>"; }

        if ($rsUser["F_L_Piutang"]=="1")

          { echo "<li><a href='?mod=piutang'>Piutang</a></li>"; }

        if ($rsUser["F_L_Umur_AR"]=="1")

          { echo "<li><a href='?mod=agingar'>Umur Piutang</a></li>"; }
*/

         # if ($rsUser["F_L_Aktiva"]=="1")

         #   { echo "<li><a href='?mod=LaporanAktivaTetap'>Laporan Aktiva</a></li>"; } ?>
          <li class="dropdown-submenu">
            <a tabindex="-1" href="#">Laporan AP</a> <!-- 10 Desember 2019 - Daniel -->
            <ul class="dropdown-menu">
              <?php
				if ($rsUser["F_L_AP_RIN_MUT_HUT_DAG_LOK"]=="1")
					{ 
						echo "<li><a href='?mod=rincmuthutdaglok'>Laporan AP - Rincian Mutasi Hutang Dagang  Lokal</a></li>"; 
					}			  
				if ($rsUser["F_L_AP_MUT_HUT_DAG_LOK"]=="1")
					{ 
						echo "<li><a href='?mod=muthutdaglok'>Laporan AP - Mutasi Hutang Dagang Lokal</a></li>";
					}						  
				if ($rsUser["F_L_AP_RIN_MUT_HUT_DAG_IMP"]=="1")
					{ 
						echo "<li><a href='?mod=rincmuthutdagimp'>Laporan AP - Rincian Mutasi Hutang Dagang Impor</a></li>";
					}				
				if ($rsUser["F_L_AP_MUT_HUT_DAG_IMP"]=="1")
					{ 
						echo "<li><a href='?mod=muthutdagimp'>Laporan AP - Mutasi Hutang Dagang Impor</a></li>";
					}/*		
				if ($rsUser["F_L_AP_HUT_TER_BAY"]=="1")
					{ 
						echo "<li><a href='?mod=hutdagterbay'>Laporan AP - Laporan Hutang Terlambat Bayar</a></li>";
					}*/	
				if ($rsUser["F_L_AP_KAR_HUT"]=="1")
					{ 
						echo "<li><a href='?mod=kartuAP'>Laporan AP - Kartu Hutang</a></li>";
					}			
				if ($rsUser["F_L_AP_REK_KON_BON"]=="1")
					{ 
						echo "<li><a href='?mod=lapKontraBon'>Laporan AP - Rekap Kontra Bon</a></li>";
					}					
              #echo "<li><a href='?mod=rimutpii'>Laporan AP - Rincian Mutasi Piutang Impor</a></li>";
				if ($rsUser["F_L_AP_RIN_KON_BON"]=="1")
					{ 
						echo "<li><a href='?mod=rikontrabon'>Laporan AP - Rincian Kontra Bon</a></li>";
					}	
				if ($rsUser["F_L_Umur_Utang"]=="1")
					{ 
						echo "<li><a href='?mod=umurutang'>Laporan AP - Aging Vs Jatuh Tempo Hutang Dagang</a></li>";
					}						
 				if ($rsUser["F_L_Umur_Utang"]=="1")
					{ 
						echo "<li><a href='?mod=utjatem'>Laporan AP - Hutang Jatuh Tempo</a></li>";
					}    				
				if ($rsUser["F_L_MUT_DP_CBD"]=="1")
					{ 
						echo "<li><a href='?mod=mutdpcbd'>Laporan AP - Mutasi DP dan CBD</a></li>";
					}             
              ?>
            </ul>
          </li>
          <li class="dropdown-submenu" >
            <a tabindex="-1" href="#">Laporan AR</a>
            <ul class="dropdown-menu dropup" >
              <?php 
            //echo "<li><a href='?mod=kartuAR'>Laporan Kartu Piutang</a></li>";
 				if ($rsUser["F_L_AR_KAR_PIU"]=="1")
					{ 
						echo "<li><a href='?mod=lappiutang'>Laporan AR - Kartu Piutang</a></li>";
					}   			
 				if ($rsUser["F_L_AR_UAN_MUK_PEL"]=="1")
					{ 
						 echo "<li><a href='?mod=UMPelanggan'>Laporan AR - Uang Muka Pelanggan</a></li>";
					}  
 	              
 				if ($rsUser["F_L_AR_RIN_MUT_PIU_LOK"]=="1")
					{ 
						 echo "<li><a href='?mod=rimutpilok'>Laporan AR - Rincian Mutasi Piutang Lokal</a></li>";
					}     
 				if ($rsUser["F_L_AR_MUT_PIU_LOK"]=="1")
					{ 
						 echo "<li><a href='?mod=mutpilok'>Laporan AR - Mutasi Piutang Dagang Lokal</a></li>";
					} 
				if ($rsUser["F_L_AR_MUT_PIU_EXP"]=="1")
					{ 
						 echo "<li><a href='?mod=mutpiek'>Laporan AR - Mutasi Piutang Dagang Ekspor</a></li>";
					} 					
              #echo "<li><a href='?mod=mutpilok'>Laporan AR - Mutasi Piutang Lokal</a></li>";
  				if ($rsUser["F_L_AR_RIN_MUT_PIU_EXP"]=="1")
					{ 
						 echo "<li><a href='?mod=rimutpiek'>Laporan AR - Rincian Mutasi Piutang Dagang Ekspor</a></li>";
					} 	             
  				if ($rsUser["F_L_Umur_AR"]=="1")
					{ 
						 echo "<li><a href='?mod=agingARjatuhtempo'>Laporan AR - Aging AR dan Jatuh Tempo</a></li>";
					}
              #echo "<li><a href='?mod=aractualvsjt'>Laporan AR - Actual VS Jatuh Tempo</a></li>";
  				if ($rsUser["F_L_AR_TER_BAY"]=="1")
					{ 
						 echo "<li><a href='?mod=arterlambatbayar'>Laporan AR - Terlambat Bayar</a></li>";
					}			  
         
              #echo "<li><a href='?mod=pantauanpgm'>Laporan AR - Pantauan PGM</a></li>";
              #echo "<li><a href='?mod=proyeksivsrealisasi'>Laporan AR -  Proyeksi VS Realisasi Penerimaan AR</a></li>";
              #echo "<li><a href='?mod=rincmutar'>Laporan AR - Rincian Mutasi AR</a></li>";
              ?>
            </ul>
          </li>

          <li class="dropdown-submenu">
            <a tabindex="-1" href="#">Laporan Cash dan Bank</a>  <!-- 02 Maret 2020- Mario -->
            <ul class="dropdown-menu">

              <?php 

            //  echo "<li><a href='?mod=bukubank'>Laporan Cash dan Bank - Buku Bank </a></li>";
             // echo "<li><a href='?mod=bukukas'>Laporan Cash dan Bank - Buku Kas </a></li>";
           //   echo "<li><a href='?mod=bungaloanbank'>Laporan Cash dan Bank - Bunga Loan Bank </a></li>";
            //  echo "<li><a href='?mod=rekapbukubank'>Laporan Cash dan Bank - Rekap Buku Bank </a></li>";
            //  echo "<li><a href='?mod=rekapbukukas'>Laporan Cash dan Bank - Rekap Buku Kas </a></li>";


          if ($rsUser["F_L_Cash"]=="1")

            { echo "<li><a href='?mod=LaporanCash'>Laporan Cash</a></li>"; }

          if ($rsUser["F_L_Cash_Rekap"]=="1")	

            {echo "	<li><a href='?mod=LaporanCashRekap'>Laporan Cash Rekap</a></li>	";	}

         if ($rsUser["F_L_Bank"]=="1")

            { echo "<li><a href='?mod=LaporanBank'>Laporan Bank</a></li>"; }

          if ($rsUser["F_L_Bank_Rekap"]=="1")	

            {echo "	<li><a href='?mod=LaporanBankRekap'>Laporan Bank Rekap</a></li>	";	} 

              ?>
            </ul>
          </li>
		<?php
         
  				if ($rsUser["F_L_LAP_JOU"]=="1")
					{ 
						  echo "<li><a href='?mod=lapjur'>Laporan Jurnal</a></li>";
					}			  
		  
		  
		  ?>

		<li class="dropdown-submenu" >
            <a tabindex="-1" href="#">Laporan Pembelian</a>
            <ul class="dropdown-menu dropup" >
              <?php 
              
  				if ($rsUser["FIN_L_BAR_GEN"]=="1")
					{ 
						 echo "<li><a href='?mod=lapemgen'>Laporan Pembelian General</a></li>";
					}			  
             
  				if ($rsUser["F_L_PEM_REK_LOK"]=="1")
					{ 
						   echo "<li><a href='?mod=lapemlok'>Laporan Pembelian Lokal</a></li>";
					}			  
			 
  				if ($rsUser["F_L_PEM_REK_IMP"]=="1")
					{ 
						  echo "<li><a href='?mod=lapemim'>Laporan Pembelian Impor</a></li>";
					}			  
			
  				if ($rsUser["F_L_PEM_REK_LOK"]=="1")
					{ 
						   echo "<li><a href='?mod=repemlok'>Laporan Rekap Pembelian Lokal</a></li>";
					}			  
			  
  				if ($rsUser["F_L_PEM_REK_IMP"]=="1")
					{ 
						 echo "<li><a href='?mod=repemim'>Laporan Rekap Pembelian Impor</a></li>";
					}			
			//  echo "<li><a href='?mod=retpem'>Laporan Retur Pembelian</a></li>";
              ?>
            </ul>
        </li>
   
          <li class="dropdown-submenu">
           <a tabindex="-1" href="#">Laporan Penjualan</a> <!-- 26 Februari 2020 - Daniel -->
            <ul class="dropdown-menu"> 
              <?php 
/*               echo "<li><a href='?mod=realbiayaekspor'>Laporan Penjualan - Realisasi Biaya Ekspor</a></li>";
              echo "<li><a href='?mod=realbiayaekspor2'>Laporan Penjualan - Realisasi Biaya Ekspor 2</a></li>";
              echo "<li><a href='?mod=pantaudebitnote'>Laporan Penjualan - Pantauan Debit Note</a></li>";
              echo "<li><a href='?mod=pantaupiutangLC'>Laporan Penjualan - Pantauan Piutang LC</a></li>";*/
              
  				if ($rsUser["F_L_PAN_RET_PEN"]=="1")
					{ 
						echo "<li><a href='?mod=pantaureturj'>Laporan Penjualan - Pantauan Retur Penjualan</a></li>";
					}			  
             
  				if ($rsUser["F_L_REA_SAL_ORD"]=="1")
					{ 
						  echo "<li><a href='?mod=realSO'>Laporan Penjualan - Realisasi Sales Order (SO)</a></li>";
					}			  
              
  				if ($rsUser["F_L_RIN_PEN_LOK"]=="1")
					{ 
						 echo "<li><a href='?mod=rincjlok'>Laporan Penjualan - Rincian Penjualan Lokal</a></li>";
					}               
	
	
			   //echo "<li><a href='?mod=rincjeks'>Laporan Penjualan - Rincian Penjualan Ekspor</a></li>";
  				if ($rsUser["F_L_REK_PEN_LOK"]=="1")
					{ 
						echo "<li><a href='?mod=rekjlok'>Laporan Penjualan - Rekap Penjualan Lokal</a></li>";
					}			  
              
  				if ($rsUser["F_L_REK_PEN_EXP"]=="1")
					{ 
						 echo "<li><a href='?mod=rekjeks'>Laporan Penjualan - Rekap Penjualan Ekspor</a></li>";
					}			  
               
  				if ($rsUser["F_L_RET_DAN_POT_PEN"]=="1")
					{ 
						 echo "<li><a href='?mod=retpotj'>Laporan Penjualan - Retur & Potongan Penjualan</a></li>";
					}			  
			  
  				if ($rsUser["F_L_REK_AKU_PEN"]=="1")
					{ 
						echo "<li><a href='?mod=jualakumulasi'>Laporan Penjualan - Rekap Akumulasi Penjualan</a></li>";
					}			  
/*              echo "<li><a href='?mod=rekakj'>Laporan Penjualan - Rekap Akumulasi Penjualan</a></li>";
              echo "<li><a href='?mod=rekakpenerimaAR'>Laporan Penjualan - Rekap Akumulasi Penerimaan AR</a></li>"; */
              ?>
            </ul>
          </li>
		          <li class="dropdown-submenu">
            <a tabindex="-1" href="#">Laporan Penerimaan</a>
            <ul class="dropdown-menu">
              <?php 

            
  				if ($rsUser["F_L_PEN_MAK_INT"]=="1")
					{ 
						 echo "<li><a href='?mod=maklonpenerimaaninternal'>Laporan Penerimaan - Laporan Maklon Penerimaan Internal</a></li>";
					}			 
             
  				if ($rsUser["PEN_MAK_EXT"]=="1")
					{ 
						echo "<li><a href='?mod=maklonpenerimaanexternal'>Laporan Penerimaan - Laporan Maklon Penerimaan Eksternal</a></li>";
					}			 

              ?>
           </ul>
          </li> 
		  <li class="dropdown-submenu">
            <a tabindex="-1" href="#">Cost Accounting</a> <!-- 26 Mei 2020 - Daniel -->
            <ul class="dropdown-menu">
              <?php 

			  
  				if ($rsUser["FIN_L_WIP"]=="1")
					{ 
						 echo "<li><a href='?mod=mutasiwip'>Cost Accounting - Mutasi WIP</a></li>";
					}
              
  				if ($rsUser["FIN_L_FIN_GOOD"]=="1")
					{ 
						echo "<li><a href='?mod=lapagingfg'>Cost Accounting - Aging Finished Goods</a></li>";
					}				  
             					
				if ($rsUser["F_L_AGI_SPA"]=="1")
					{ 
						echo "<li><a href='?mod=lapagingspr'>Cost Accounting - Aging Spareparts</a></li>";
					}	

					
				if ($rsUser["F_L_AGI_BB"]=="1")
					{ 
						echo "<li><a href='?mod=lapagingbb'>Cost Accounting - Aging Bahan Baku</a></li>";
					}						

              ?>
            </ul>
          </li>


       <!--   <li class="dropdown-submenu">
            <a tabindex="-1" href="#">Laporan Produksi</a> <!-- 26 Mei 2020 - Daniel
            <ul class="dropdown-menu">
              <?php 

              //echo "<li><a href='?mod=lapagingfg'>Laporan Produksi - Laporan Finished Goods</a></li>";
             // echo "<li><a href='?mod=mutasiwip'>Laporan Produksi - Mutasi WIP</a></li>";

              ?>
            </ul>
          </li>
		-->  
        </ul>

      </li>

      <li class="dropdown">

        <a href="" class="dropdown-toggle" data-toggle="dropdown">Tools<span class="caret"></span></a>



     </li>

     <!-- Modul Report -->


    <!-- Modul Report -->

    <li class="dropdown">

      <a href="../">Main Menu</a>

    </li>



  </ul>

</div>

</div>

</nav>



