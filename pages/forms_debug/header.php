<?php
  if (empty($_SESSION['username'])) { header("location:../../"); }
  if (!isset($_SESSION['username'])) { header("location:../../"); }
  $rsComp=mysql_fetch_array(mysql_query("select * from mastercompany"));
    $nm_company=$rsComp["company"];
    $st_company=$rsComp["status_company"];
  if ($nm_company=="PT. Youngil Leather Indonesia")
  { $wip_cap="Chemical"; }
  else
  { $wip_cap=$c7; }
            
  if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../"); }
  if ($st_company=="KITE") { $captupl="Upload Data"; } else { $captupl="Upload Data Dari ModulTPB"; }
  $rsUser=mysql_fetch_array(mysql_query("select * from 
    userpassword where username='$user'"));
?>
<nav class="navbar navbar-static-top">
  <div class="container">
    <div class="navbar-header">
      <a href="?mod=1" class="navbar-brand"><b><?PHP echo $nm_company;?></b></a>
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
        <i class="fa fa-bars"></i>
      </button>
    </div>
    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
      <ul class="nav navbar-nav">
        <?php 
          $akses = $rsUser['menu_master'];
          if ($akses=="1") { 
        ?>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Master<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php
            $akses = $rsUser["mnuMasterBenang"];
            if ($akses) { echo "<li><a href='master_benang.php'>Jenis Benang</a></li>"; }
            $akses = $rsUser["mnuMasterWarna"];
            if ($akses=="1") {echo "<li><a href='master_warna.php'>Warna</a></li>";}
            $akses = $rsUser["mnuMasterItem"];
            if ($st_company=="GB" OR $st_company=="PLB" OR $st_company=="MULTI_WHS")
            { if ($akses=="1") {echo "<li><a href='?mod=2&mode=Bahan_Baku'>Barang</a></li>";} }
            else
            { if ($akses=="1") {echo "<li><a href='?mod=2L&mode=Bahan_Baku'><i class='fa fa-bars'></i>$c5</a></li>";}
              $akses = $rsUser["mnuMasterScrap"];
              if ($akses=="1") {echo "<li><a href='?mod=2L&mode=Scrap'>Scrap / Limbah</a></li>";}
              $akses = $rsUser["mnuMasterMesin"];
              if ($akses=="1") {echo "<li><a href='?mod=2L&mode=Mesin'>$caption[1]</a></a></li>";}
              $akses = $rsUser["mnuMasterWIP"];
              if ($akses=="1") {echo "<li><a href='?mod=2L&mode=WIP'>$wip_cap</a></li>";}
                $akses = $rsUser["M_D_Stock"];
              if ($akses=="1") {echo "<li><a href='?mod=mdeathstock'>Death Stock</a></li>";}          
            }
            $akses = $rsUser["konv_unit"];
            if ($akses=="1") {echo "<li><a href='?mod=22L'>Konversi Satuan</a></li>";}
            $akses = $rsUser["mnuMStyle"];
            if ($akses=="1") {echo "<li><a href='?mod=3L'>$c8</a></li>";}
            $akses = $rsUser["master_whs"];
            if ($akses=="1") {echo "<li><a href='?mod=4&mode=Gudang'>Gudang</a></li>";}
            if ($nm_company!="PT. Nirwana Alabare Garment")
            { $akses = $rsUser["mnuMasterSupplier"];
              if ($akses=="1") {echo "<li><a href='?mod=4&mode=Supplier'>Supplier</a></li>";}
            }
            $akses = $rsUser["bom"];
            if ($akses=="1") { echo "<li><a href='?mod=16'>Bill Of Material (BOM)</a></li>"; }
            ?>
            <!-- <li class="dropdown-submenu">
              <a href="#">More options</a>
              <ul class="dropdown-menu" role="menu">
                <li><a href='?mod=16'>Bill Of Material (BOM)</a></li>
              </ul>
            </li> -->
          </ul>
        </li>
        <?php } ?>
        <?php 
          $akses = $rsUser["menu_in"]; 
          if ($akses=="1") { 
        ?>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown"><?php echo $c3; ?><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?PHP
            $akses = $rsUser["mnuBPB"];
            if ($st_company=="GB" OR $st_company=="PLB" OR $st_company=="MULTI_WHS")
            { if ($akses=="1") {echo "<li><a href='?mod=51&mode=Bahan_Baku'>Barang</a></li>";} }
            else
            { $akses = $rsUser["mnuBPB"];
              if ($akses=="1") {echo "<li><a href='?mod=51v&mode=Bahan_Baku'>$c5</a></li>";}
              $akses = $rsUser["bpb_po"];
              if ($akses=="1") {echo "<li><a href='?mod=26v&mode=Bahan_Baku'>$c5 (PO)</a></li>";}
              $akses = $rsUser["bpb_jo"];
              if ($akses=="1") {echo "<li><a href='?mod=33v&mode=Bahan_Baku'>$c5 (JO)</a></li>";}
              $akses = $rsUser["bpb_consol"];
              if ($akses=="1") {echo "<li><a href='?mod=40v&mode=Bahan_Baku'>$c5 (Consolidate)</a></li>";}
              $akses = $rsUser["bpb_gen"];
              if ($akses=="1") {echo "<li><a href='?mod=26v&mode=General'>Item General (PO)</a></li>";}
              $akses = $rsUser["mnuBPBScrap"];
              if ($akses=="1") {echo "<li><a href='?mod=52v&mode=Scrap'>Scrap / Limbah</a></li>";}
              $akses = $rsUser["mnuBPBScrap_SJ"];
              if ($akses=="1") {echo "<li><a href='?mod=36v&mode=Scrap'>Scrap / Limbah (SJ)</a></li>";}
              $akses = $rsUser["mnuBPBMesin"];
              if ($akses=="1") {echo "<li><a href='?mod=53v&mode=Mesin'>$caption[1]</a></a></li>";}
              $akses = $rsUser["mnuBPBWIP"];
              if ($akses=="1") {echo "<li><a href='?mod=54v&mode=WIP'>$wip_cap</a></li>";}
              $akses = $rsUser["mnuBPBWIP_PO"];
              if ($akses=="1") {echo "<li><a href='?mod=26v&mode=WIP'>$wip_cap (PO)</a></li>";}
              $akses = $rsUser["mnuBPBWIP_JO"];
              if ($akses=="1") {echo "<li><a href='?mod=38v&mode=WIP'>$wip_cap Inhouse</a></li>";}
              $akses = $rsUser["mnuRI"];
              if ($akses=="1") {echo "<li><a href='?mod=20v&mode=Bahan_Baku'>Pengembalian Bahan Baku</a></li>";}
              if ($akses=="1") {echo "<li><a href='?mod=20v&mode=General'>Pengembalian Item General</a></li>";}
              $akses = $rsUser["mnuRI_FG"];
              if ($akses=="1") {echo "<li><a href='?mod=20v&mode=FG'>Pengembalian Barang Jadi</a></li>";}
              $akses = $rsUser["qc_pass"];
              if ($akses=="1") {echo "<li><a href='?mod=23v&mode=Bahan_Baku'>QC Pass Bahan Baku</a></li>";}
            }
            $akses = $rsUser["bpb_bom"];
            if ($akses=="1") {echo "<li><a href='?mod=51a&mode=Bahan_Baku'>$c5 (BOM)</a></li>";}
            $akses = $rsUser["mnuBPBFG"];
            if ($akses=="1") {echo "<li><a href='?mod=55v&mode=FG'>$c8</a></li>";}
            $akses = $rsUser["mnuBPBFG_so"];
            if ($akses=="1") {echo "<li><a href='?mod=31v'>$c8 (SO)</a></li>";}
            $akses = $rsUser["konfirmasi_sj"];
		if ($akses=="1") {echo "<li><a href='?mod=31v_bj_po'>$c8 (PO)</a></li>";}			
            $akses = $rsUser["konfirmasi_sj"];
            if ($akses=="1") {echo "<li><a href='?mod=17'>Konfirmasi Penerimaan</a></li>";}
            $akses = $rsUser["bpb_roll"];
            if ($akses=="1") {echo "<li><a href='?mod=18v'>Detail Pemasukan (Temporary)</a></li>";}
            $akses = $rsUser["bpb_roll"];
            if ($akses=="1") {echo "<li><a href='?mod=18LV'>Detail Pemasukan (Location)</a></li>";}
            $akses = $rsUser["transfer_post_app"];
            if ($akses=="1") {echo "<li><a href='?mod=tpapp'>Transfer Booking Stock</a></li>";}
            $akses = $rsUser["detail_pemasukan"];
            if ($akses=="1") {echo "<li><a href='?mod=29'>Detail Pemasukan</a></li>";}
            $akses = $rsUser["import_tpb_in"];
            if ($akses=="1") {echo "<li><a href='?mod=9&mode=In'>$captupl</a></li>";}
            ?>
          </ul>
        </li>
        <?php } ?>
        <?php 
          $akses = $rsUser["menu_out"]; 
          if ($akses=="1") { 
        ?>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown"><?php echo $c4; ?><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php
            $akses = $rsUser["mnuBPPB"];
            if ($st_company=="GB" OR $st_company=="PLB" OR $st_company=="MULTI_WHS")
            { if ($akses=="1") { echo "<li><a href='?mod=61&mode=Bahan_Baku'>Barang</a></li>"; } 
              $akses = $rsUser["bppb_antar_whs"];
              if ($akses=="1") { echo "<li><a href='?mod=67&mode=Bahan_Baku'>Barang Antar Gudang</a></li>"; }
            }
            else
            { $akses = $rsUser["req_mat"];
              if ($akses=="1") {echo "<li><a href='?mod=61rv&mode=Bahan_Baku'>Permintaan $c5</a></li>"; }
              $akses = $rsUser["picklist"];
              if ($akses=="1") {echo "<li><a href='?mod=61rvp&mode=Bahan_Baku'>Picklist $c5</a></li>"; }
              $akses = $rsUser["mnuBPPB"];
              if ($akses=="1") {echo "<li><a href='?mod=61v&mode=Bahan_Baku'>$c5</a></li>";}
              $akses = $rsUser["bppb_req"];
              if ($akses=="1") {echo "<li><a href='?mod=35v&mode=Bahan_Baku'>$c5 (Request)</a></li>";}
              $akses = "0"; #$rsUser["bppb_jo"];
              if ($akses=="1") {echo "<li><a href='?mod=34v&mode=Bahan_Baku'>$c5 (JO)</a></li>";}
              $akses = $rsUser["bppb_gen"];
              if ($akses=="1") {echo "<li><a href='?mod=61v&mode=General'>Item General</a></li>";}
              $akses = $rsUser["mnuBPPBScrap"];
              if ($akses=="1") {echo "<li><a href='?mod=62v&mode=Scrap'>Scrap / Limbah</a></li>";}
              $akses = $rsUser["mnuBPPBScrap_JO"];
              if ($akses=="1") {echo "<li><a href='?mod=37v&mode=Scrap'>Scrap / Limbah (JO)</a></li>";}
              $akses = $rsUser["mnuBPPBMesin"];
              if ($akses=="1") {echo "<li><a href='?mod=63v&mode=Mesin'>$caption[1]</a></a></li>";}
              $akses = $rsUser["mnuBPPBWIP"];
              if ($akses=="1") {echo "<li><a href='?mod=64v&mode=WIP'>$wip_cap</a></li>";}
              $akses = $rsUser["bppb_po"];
              if ($akses=="1") {echo "<li><a href='?mod=37v_bppb_po&mode=WIP'>$wip_cap (PO)</a></li>";}	
              $akses = $rsUser["mnuBPPBWIP_JO"];
              if ($akses=="1") {echo "<li><a href='?mod=37v&mode=WIP'>$wip_cap Inhouse</a></li>";}
			  
              $akses = $rsUser["mnuRO"];
              if ($akses=="1") 
              {
                echo "<li><a href='?mod=21v&mode=Bahan_Baku'>Retur Bahan Baku</a></li>";
                echo "<li><a href='?mod=21v&mode=General'>Retur Item General</a></li>";
              }
            }
            $akses = $rsUser["bppb_bom"];
            if ($akses=="1") {echo "<li><a href='?mod=61a&mode=Bahan_Baku'>$c5 (BOM)</a></li>";}
            $akses = $rsUser["BPPB_23"];
            if ($akses=="1") { echo "<li><a href='?mod=66&mode=Bahan_Baku'>Barang Per BC23</a></li>"; }
            $akses = $rsUser["mnuBPPBFG"];
            if ($akses=="1") {echo "<li><a href='?mod=65v&mode=FG'>$c8</a></li>";}
            $akses = $rsUser["mnuBPPBFG_so"];
            if ($akses=="1") {echo "<li><a href='?mod=32v&mode=FG'>$c8 (SO)</a></li>";}
            $akses = $rsUser["konfirmasi_sj_out"];
            if ($akses=="1") {echo "<li><a href='?mod=17O'>Konfirmasi Pengeluaran</a></li>";}
            $akses = $rsUser["import_tpb_out"];
            if ($akses=="1") {echo "<li><a href='?mod=9&mode=Out'>$captupl</a></li>";}
            ?>
          </ul>
        </li>
        <?php } ?>
        <?php 
          $akses = $rsUser['lap_inventory'];
          if ($akses=="1") { 
        ?>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Laporan<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php
            echo "<li><a href='?mod=39'>Material Status</a></li>";
            echo "<li><a href='?mod=ovtinv'>Over Tollerance</a></li>";
            echo "<li><a href='?mod=7&rptid=reqmat'>Permintaan Bahan Baku</a></li>";
            echo "<li><a href='?mod=7&rptid=rkonf'>Konfirmasi</a></li>";
            ?>
          </ul>
        </li>
        <?php } ?>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown"><?php echo $c9; ?><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?PHP
            $akses = $rsUser["list_detail"];
            if ($akses=="1")
            { echo "<li><a href='?mod=71&mode=In'>List $c3</a></li>";
              echo "<li><a href='?mod=71&mode=Detail_In'>Detail $c3</a></li>";
            }
            if ($st_company=="GB" OR $st_company=="MULTI_WHS")
            { echo "<li>"; echo "<a href='?mod=7&rptid=bc23'>BC 2.3</a>"; echo "</li>";
              echo "<li><a href='?mod=7&rptid=bc27msk'>BC 2.7</a></li>";
            }
            else if ($st_company=="PLB")
            { echo "<li>"; echo "<a href='?mod=7&rptid=bc16'>BC 1.6</a>"; echo "</li>";
              echo "<li><a href='?mod=7&rptid=bc27msk'>BC 2.7</a></li>";
            }
            else if ($st_company=="KITE")
            { echo "
                <li><a href='?mod=7&rptid=kite_dl'>Hasil Produksi (Lokal)</a></li>
                <li><a href='?mod=7&rptid=kite_a'>Bahan Baku (Import)</a></li>
                <li><a href='?mod=7&rptid=kite_al'>Bahan Baku (Lokal)</a></li>
                <li><a href='?mod=7&rptid=kite_d'>Hasil Produksi</a></li>";
            }
            else
            { echo "
                <li><a href='?mod=7&rptid=bc23'>BC 2.3 Impor</a></li>
                <li><a href='?mod=7&rptid=bc23pjt'>BC 2.3 Impor PJT</a></li>
                <li><a href='?mod=7&rptid=bc262msk'>BC 2.6.2</a></li>
                <li><a href='?mod=7&rptid=bc27msk'>BC 2.7</a></li>
                <li><a href='?mod=7&rptid=bc27msksub'>BC 2.7 Subkon</a></li>
                <li><a href='?mod=7&rptid=bc40lkl'>BC 4.0</a></li>
                <li><a href='?mod=7&rptid=bc40sewa'>BC 4.0 Sewa Mesin</a></li>
                <li><a href='?mod=7&rptid=bc40subkon'>BC 4.0 Subkon</a></li>";
              if ($nm_company!="PT. Nirwana Alabare Garment")
              { echo "
                  <li><a href='?mod=7&rptid=bc20pibbyr'>BC 2.0 PIB Bayar</a></li>
                  <li><a href='?mod=7&rptid=bc21pibk'>BC 2.1 PIB Khusus</a></li>";
              }
              echo "<li><a href='?mod=7&rptid=bc24kitte'>BC 2.4 KITE</a></li>";
              $akses = $rsUser["in_rekap"];
              if ($akses=="1") { echo "<li><a href='?mod=7&rptid=inrekap'>Rekap</a></li>"; }
              $akses = $rsUser["r_devisa"];
              if ($akses=="1") { echo "<li><a href='?mod=7&rptid=in_dev'>Devisa</a></li>"; }
            }
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown"><?php echo $c10; ?><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?PHP
            $akses = $rsUser["list_detail"];
            if ($akses=="1")
            { echo "<li><a href='?mod=72&mode=Out'>List $c4</a></li>";
              echo "<li><a href='?mod=72&mode=Detail_Out'>Detail $c4</a></li>";
            }
            if ($st_company=="GB" OR $st_company=="MULTI_WHS")
            { #GB
              echo "<li>"; echo "<a href='?mod=7&rptid=bc25lkl'>BC 2.5 Keluar</a>"; echo "</li>";
              echo "
                <li><a href='?mod=7&rptid=bc27keluar'>BC 2.7 Keluar</a></li>
                <li><a href='?mod=7&rptid=bc27subkon'>BC 2.7 Subkon</a></li>";
              echo "<li><a href='?mod=7&rptid=bc30'>BC 3.0 Export</a></li>";
            }
            else if ($st_company=="PLB")
            { #PLB
              echo "<li>"; echo "<a href='?mod=7&rptid=bc28'>BC 2.8</a>"; echo "</li>";
              echo "
                <li><a href='?mod=7&rptid=bc27keluar'>BC 2.7 Keluar</a></li>
                <li><a href='?mod=7&rptid=bc27subkon'>BC 2.7 Subkon</a></li>";
              echo "<li><a href='?mod=7&rptid=bc33'>BC 3.3</a></li>";
            }
            else if ($st_company=="KITE")
            { echo "
              <li><a href='?mod=7&rptid=kite_b'>Bahan Baku</a></li>
              <li><a href='?mod=7&rptid=kite_c'>Barang Dalam Proses Dalam Rangka Kegiatan Subkontrak</a></li>
              <li><a href='?mod=7&rptid=kite_e'>Hasil Produksi</a></li>
              <li><a href='?mod=7&rptid=kite_e24'>BC 2.4</a></li>
              <li><a href='?mod=7&rptid=kite_elkl'>Jual Lokal</a></li>";
            }
            else
            { #KB
              echo "
                <li><a href='?mod=7&rptid=bc30'>BC 3.0 Export</a></li>
                <li><a href='?mod=7&rptid=bc261keluar'>BC 2.6.1</a></li>
                <li><a href='?mod=7&rptid=bc27keluar'>BC 2.7</a></li>
                <li><a href='?mod=7&rptid=bc27subkon'>BC 2.7 Subkon</a></li>
                <li><a href='?mod=7&rptid=bc41lkl'>BC 4.1 Lokal</a></li>
                <li><a href='?mod=7&rptid=bc41sewa'>BC 4.1 Sewa Mesin</a></li>
                <li><a href='?mod=7&rptid=bc41subkon'>BC 4.1 Subkon</a></li>
                <li><a href='?mod=7&rptid=bc25scrap'>BC 2.5 Scrap</a></li>
                <li><a href='?mod=7&rptid=bc25lkl'>BC 2.5 Jual Lokal</a></li>";
              $akses = $rsUser["out_rekap"];
              if ($akses=="1") { echo "<li><a href='?mod=7&rptid=outrekap'>Rekap</a></li>"; }
              $akses = $rsUser["r_devisa"];
              if ($akses=="1") { echo "<li><a href='?mod=7&rptid=out_dev'>Devisa</a></li>"; }
            }
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown"><?php echo $c11; ?><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?PHP
            if ($st_company=="GB" OR $st_company=="PLB" OR $st_company=="MULTI_WHS")
            { #GB
              echo "<li>"; echo "<a href='?mod=7&rptid=gb_perdokumen'>Posisi Barang Per Dokumen Pabean</a>"; echo "</li>";
              echo "<li>"; echo "<a href='?mod=7&rptid=gb_bahanbaku'>Pertanggungjawaban Mutasi Barang</a>"; echo "</li>";
            }
            else if ($st_company=="KITE")
            { echo "<li>"; echo "<a href='?mod=7&rptid=bahanbaku'>Bahan Baku</a>"; echo "</li>";
              echo "<li>"; echo "<a href='?mod=7&rptid=barangjadi'>Hasil Produksi</a>"; echo "</li>";
              echo "<li>"; echo "<a href='?mod=7&rptid=kite_h'>Penyelesaian Waste/Scrap</a>";
              echo "<li><a href='?mod=7&mode=mut&rptid=mesin'>$caption[1]</a></a></li>";
            }
            else
            { if ($nm_company=="PT. Buntara Multi Industry")
              { echo "<li><a href='?mod=7&mode=mut&rptid=bahanbakupoitem'>Bahan Baku & Bahan Penolong</a></li>"; }
              else
              { echo "<li><a href='?mod=7&mode=mut&rptid=bahanbaku'>$c20</a></li>"; }
              echo "<li><a href='?mod=7&mode=mut&rptid=barangjadi'>$c8</a></li>";
              echo "<li><a href='?mod=7&mode=mut&rptid=barangsisa'>$c21</a></li>";
              echo "<li><a href='?mod=7&mode=mut&rptid=mesin'>$c22</a></li>";
              $akses = $rsUser["mutasi_wip"];
              $cekmutwip = flookup("username","userpassword","mutasi_wip='1'");
              if ($akses=="1")
              { echo "<li><a href='?mod=7&mode=mut&rptid=mwiptot'>$c7</a></li>"; 
                echo "<li><a href='?mod=7&mode=mut&rptid=mwipdet'>Detail $c7</a></li>";
              }
              else
              { if ($cekmutwip=="")
                { echo "<li><a href='?mod=7&rptid=wip'>$c7</a></li>"; }
                else
                { echo "<li><a href='?mod=7&mode=mut&rptid=mwiptot'>$c7</a></li>"; }
              }
              $akses = $rsUser["mnuPAdjust"];
              if ($akses=="1") {echo "<li><a href='?mode=Add_Adj'><span>Adjustment</span></a></li>";}
              echo "<li><a href='?mod=8&mode=Bahan_Baku'><span>Stock Bahan Baku</span></a></li>";
              echo "<li><a href='?mod=8&mode=General'><span>Stock Item General</span></a></li>";
			  $akses = $rsUser["mnuPStockOpname"];
              if ($akses=="1") 
              { echo "<li><a href='?mod=8&mode=Bahan_Baku'><span>Stock Non FG</span></a></li>";
                echo "<li><a href='?mod=8&mode=FG'><span>Stock FG</span></a></li>";
              }
            }
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown"><?php echo $c12; ?><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?PHP
            echo "<li><a href='?mod=7_n'>$c23</a></li>";
            $akses = $rsUser["cancel_all_in"];
            if ($akses=="1") {echo "<li><a href='?mod=cai'>Cancel Pemasukan</a></li>";}
            $akses = $rsUser["mut_rak"];
      		  if ($akses=="1") {echo "<li><a href='?mod=mr'>Mutasi Rak</a></li>";}
      		  $akses = $rsUser["cancel_req"];
            if ($akses=="1") {echo "<li><a href='?mod=car'>Cancel Perimintaan</a></li>";}
            $akses = $rsUser["cancel_all_out"];
            if ($akses=="1") {echo "<li><a href='?mod=cao'>Cancel Pengeluaran</a></li>";}
            $akses = $rsUser["bom"];
            if ($akses=="1") { echo "<li><a href='?mod=15'>Upload BOM</a></li>"; }
            $akses = $rsUser["calc_stock"];
            if ($akses=="1") { echo "<li><a href='calc_stock.php?mode=0'>Kalkulasi Stock</a></li>"; }
            if ($akses=="1") { echo "<li><a href='calc_stock.php?mode=1'>Kalkulasi Stock Minus</a></li>"; }
            if ($akses=="1") { echo "<li><a href='calc_stock_nol.php'>Kalkulasi Stock Nol</a></li>"; }
            $akses = $rsUser["bppb_prob"];
            if ($akses=="1") { echo "<li><a href='?mod=73&mode=Out_Prob'>Pengeluaran Problem</a></li>"; }
            $akses = $rsUser["rubah_type"];
            if ($akses=="1") {echo "<li><a href='?mod=11'>Rubah Type Transaksi</a></li>";}
            $akses = $rsUser["update_tgl_trans"];
            if ($akses=="1") {echo "<li><a href='?mod=111'>Rubah Tanggal Transaksi</a></li>";}
            // $akses = $rsUser["rubah_type"];
            // if ($akses=="1") {echo "<li><a href='?mod=12'>Rubah Type Barang</a></li>";}
            $akses = $rsUser["rubah_seri"];
            if ($akses=="1") {echo "<li><a href='?mod=13'>Rubah Seri Barang</a></li>";}
            $akses = $rsUser["link_to_bom"];
            if ($akses=="1") {echo "<li><a href='?mod=28'>Link Pemasukan BOM</a></li>";}
            $akses = $rsUser["input_stock_opname"];
            if ($akses=="1") 
            { echo "
              <li><a href='?mod=7&mode=hasil&rptid=hasil'>Adjustment Bahan Baku</a></li>
              <li><a href='?mod=7&mode=hasilsl&rptid=hasilsl'>Adjustment Scrap / Limbah</a></li>
              <li><a href='?mod=7&mode=hasilmes&rptid=hasilmes'>Adjustment $caption[1]</a></li>
              <li><a href='?mod=7&mode=hasilfg&rptid=hasilfg'>Adjustment FG</a></li>";
            }
            if ($nm_company=="Training Zast Systems") 
            { echo "
              <li><a href='logout.php?mode=PLB'>Rubah ke PLB</a></li>
              <li><a href='logout.php?mode=GB'>Rubah ke GB</a></li>
              <li><a href='logout.php?mode=KB'>Rubah ke KB</a></li>
              <li><a href='logout.php?mode=KT'>Rubah ke KITE</a></li>
              <li><a href='logout.php?mode=MW'>Rubah ke Multi Warehouse</a></li>
              <li><a href='logout.php?mode=ELG'>Rubah ke ERP Elekronik Part</a></li>
              <li><a href='logout.php?mode=EGM'>Rubah ke ERP Garment</a></li>";
            }
            #echo "<li><a href='logout.php'>Logout</a></li>";
            ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="../"><i class='fa fa-home'></i></a>
        </li>
    </div>
  </div>
</nav>
    