<?PHP
$mod=$_GET['mod'];
$nm_company=flookup("company","mastercompany","company!=''");
$st_company=flookup("status_company","mastercompany","company!=''");
if (isset($_GET['rptid'])) { $rpt=$_GET['rptid']; } else { $rpt=""; }
if (isset($_GET['mode'])) {	$mode=$_GET['mode']; } else {	$mode="";	}
if ($mod==2)
{	if ($mode=="Bahan_Baku")
	{	if ($st_company=="MULTI_WHS" OR $st_company=="GB")
		{	echo "Master Barang"; }
		else
		{	echo $ch1; }
	}
	else if ($mode=="Scrap")
	{	echo "Master Scrap";	}
	else if ($mode=="Mesin")
	{	echo "Master ".$caption[1];	}
	else if ($mode=="WIP")
	{	echo $ch3;	}
	else
	{	echo "Halaman Tidak Tersedia";	}
}
else if ($mod==3)
{	echo "Master Barang Jadi";	}	
else if ($mod=="view_in")
{	echo "Laporan";	}	
else if ($mod==8)
{	if ($mode=="Bahan_Baku")
	{	echo "Lap. Stock Non FG";	}
	else if ($mode=="FG")
	{	echo "Lap. Stock FG";	}
	else
	{	echo "Halaman Tidak Tersedia";	}
}
else if ($mod==9)
{	if ($mode=="In")
	{	echo "Upload Data Pemasukan";	}
	else if ($mode=="Out")
	{	echo "Upload Data Pengeluaran";	}
	else
	{	echo "Halaman Tidak Tersedia";	}
}
else if ($mod==4)
{	if ($mode=="Supplier")
	{	echo "Master Supplier";	}
	else if ($mode=="Customer")
	{	echo "Master Customer";	}
	else if ($mode=="Gudang")
	{	echo "Master Gudang";	}
	else
	{	echo "Halaman Tidak Tersedia";	}
}
else if ($mod==7)
{	if ($rpt=='bc23') {$header_cap="BC 2.3 Impor";} elseif ($rpt=='bc23pjt') {$header_cap="BC 2.3 Impor PJT";} 
	elseif ($rpt=='bc16') {$header_cap="BC 1.6";} elseif ($rpt=='bc28') {$header_cap="BC 2.8";}
	elseif ($rpt=='bc33') {$header_cap="BC 3.3";} elseif ($rpt=='bc262msk') {$header_cap="BC 2.6.2 Masuk";} 
	elseif ($rpt=='bc27msk') {$header_cap="BC 2.7 Masuk";} elseif ($rpt=='bc27msksub') {$header_cap="BC 2.7 Masuk Subkon";}
	elseif ($rpt=='bc40lkl') {$header_cap="BC 4.0 Lokal";} 
	elseif ($rpt=='bc40sewa') {$header_cap="BC 4.0 Sewa Mesin";}
	elseif ($rpt=='bc40subkon') {$header_cap="BC 4.0 Subkon";} 
	elseif ($rpt=='bc20pibbyr') {$header_cap="BC 2.0 PIB Bayar";}
	elseif ($rpt=='bc21pibk') {$header_cap="BC 2.1 PIB Khusus";} 
	elseif ($rpt=='bc24kitte') {$header_cap="BC 2.4 KITE";}
	elseif ($rpt=='bc30') {$header_cap="BC 3.0 Export";} 
	elseif ($rpt=='bc261keluar') {$header_cap="BC 2.6.1 Keluar";} 
	elseif ($rpt=='bc27keluar') {$header_cap="BC 2.7 Keluar";} 
	elseif ($rpt=='bc27subkon') {$header_cap="BC 2.7 Subkon";} 
	elseif ($rpt=='bc41lkl') {$header_cap="BC 4.1 Lokal";}
	elseif ($rpt=='bc41sewa') {$header_cap="BC 4.1 Sewa Mesin";} 
	elseif ($rpt=='bc41subkon') {$header_cap="BC 4.1 Subkon";}
	elseif ($rpt=='bc25scrap') {$header_cap="BC 2.5 Scrap";} 
	elseif ($rpt=='bc25lkl') {$header_cap="BC 2.5 Jual Lokal";}
	elseif ($rpt=='bahanbaku') {$header_cap="Mutasi Bahan Baku";}
	elseif ($rpt=='barangjadi') {$header_cap="Mutasi Barang Jadi";}
	elseif ($rpt=='barangsisa') {$header_cap="Mutasi Barang Sisa";}
	elseif ($rpt=='gb_perdokumen') {$header_cap="Posisi Barang Per Dokumen Pabean";}
	elseif ($rpt=='gb_bahanbaku') {$header_cap="Pertanggungjawaban Mutasi Barang";}
	elseif ($rpt=='mesin') {$header_cap="Mutasi Mesin dan Peralatan Kantor";}
	elseif ($rpt=='inrekap') {$header_cap="Rekap Pemasukan";}
	elseif ($rpt=='outrekap') {$header_cap="Rekap Pengeluaran";}
	elseif ($rpt=='wip' OR $rpt=='mwiptot' OR $rpt=='mwipdet') {$header_cap="WIP";}
	elseif ($rpt=='listhist') {$header_cap="Riwayat Aktivitas";}
	elseif ($rpt=='kite_a') {$header_cap="Pemasukan Bahan Baku (Import)";}
	elseif ($rpt=='kite_al') {$header_cap="Pemasukan Bahan Baku (Lokal)";}
	elseif ($rpt=='kite_b') {$header_cap="Pemakaian Bahan Baku";}
	elseif ($rpt=='kite_c') {$header_cap="Pemakaian Barang DAlam Proses Dalam Rangka Kegiatan Subkontrak";}
	elseif ($rpt=='kite_d') {$header_cap="Pemasukan Hasil Produksi";}
	elseif ($rpt=='kite_dl') {$header_cap="Pemasukan Hasil Produksi (Lokal)";}
	elseif ($rpt=='kite_e') {$header_cap="Pengeluaran Hasil Produksi";}
	elseif ($rpt=='kite_e25') {$header_cap="Pengeluaran BC 2.5";}
	elseif ($rpt=='kite_e24') {$header_cap="Pengeluaran BC 2.4";}
	elseif ($rpt=='kite_elkl') {$header_cap="Pengeluaran Jual Lokal";}
	elseif ($rpt=='kite_f') {$header_cap="Mutasi Bahan Baku";}
	elseif ($rpt=='kite_g') {$header_cap="Mutasi Hasil Produksi";}
	elseif ($rpt=='kite_h') {$header_cap="Penyelesaian Waste/Scrap";}
	elseif ($rpt=='out_dev') {$header_cap="Kegiatan Pengeluarab";}
	elseif ($rpt=='in_dev') {$header_cap="Kegiatan Pemasukan";}
	elseif ($rpt=='hasil' OR $rpt=='hasilfg'
		OR $rpt=='hasilsl' OR $rpt=='hasilmes') {$header_cap="Periode Stock Opname";}
	else { $header_cap="Halaman tidak tersedia"; }
	if ($rpt!='hasil' AND $rpt!='hasilfg' AND $rpt!='hasilsl' AND $rpt!='hasilmes') 
	{ echo "Laporan ".$header_cap; }
	else
	{ echo "Masukan ".$header_cap; }
}
else if ($mod==61 AND ($st_company=="GB" OR $st_company=="MULTI_WHS"))
{	echo "Pengeluaran Barang"; }
else if ($mod==67)
{	echo "Pengeluaran Barang Antar Gudang"; }
else if ($mod==51 AND ($st_company=="GB" OR $st_company=="MULTI_WHS"))
{	echo "Pemasukan Barang"; }
else if ($mod==14)
{	if ($excel=="Y")
  { echo "$nm_company<br>"; 
    echo "Laporan Kartu Stock <br><br>";
  }
  else
  { echo "<h1>$nm_company</h1>"; 
    echo "Laporan Kartu Stock";
  }
}
else if ($mod==15)
{	echo "Upload Bill Of Material (BOM)"; }
else if ($mod==16)
{	echo "Bill Of Material (BOM)"; }
else if ($mod=="17")
{	echo "Konfirmasi Penerimaan"; }
else if ($mod=="17O")
{	echo "Konfirmasi Pengeluaran"; }
else if ($mod=="51a" and $mode=="Bahan_Baku")
{	echo "Pemasukan Bahan Baku (BOM)"; }
else if ($mod=="51v" and $mode=="Bahan_Baku")
{	echo "Pemasukan Bahan Baku"; }
else if ($mod=="52v" and $mode=="Scrap")
{	echo "Pemasukan Scrap"; }
else if ($mod=="53v" and $mode=="Mesin")
{	echo "Pemasukan Mesin"; }
else if (($mod=="54v" and $mode=="WIP") or $mod=="38v" or $mod=="38")
{	echo "Pemasukan WIP"; }
else if ($mod=="55v" and $mode=="FG")
{	echo "Pemasukan Barang Jadi"; }
else if ($mod=="551e" and $mode=="FG")
{	echo "Pemasukan Barang Jadi (Change)"; }
else if ($mod=="553e" and $mode=="Mesin")
{	echo "Pemasukan Barang Modal (Change)"; }
else if ($mod=="61v" and $mode=="Bahan_Baku")
{	echo "Pengeluaran Bahan Baku"; }
else if (($mod=="61v" and $mode=="General") or ($mod=="61" and $mode=="General"))
{	echo "Pengeluaran Item General"; }
else if ($mod=="62v" and $mode=="Scrap")
{	echo "Pengeluaran Scrap"; }
else if ($mod=="63v" and $mode=="Mesin")
{	echo "Pengeluaran Mesin"; }
else if ($mod=="63" and $mode=="General")
{	echo "Pengeluaran Item General"; }
else if ($mod=="64v" and $mode=="WIP")
{	echo "Pengeluaran WIP"; }
else if (($mod=="65v" or $mod=="65rv") and $mode=="FG")
{	echo "Pengeluaran Barang Jadi"; }
else if ($mod=="51r" and $mode=="Bahan_Baku")
{	echo "Pengembalian Bahan Baku"; }
else if ($mod=="61a" and $mode=="Bahan_Baku")
{	echo "Pengeluaran Bahan Baku (BOM)"; }
else if (($mod=="61r" or $mod=="61rv" or $mod=="61re") and ($mode=="Bahan_Baku" or $mode==""))
{	echo "Permintaan Pengeluaran Bahan Baku"; }
else if (($mod=="61rvp") and ($mode=="Bahan_Baku" or $mode==""))
{	echo "Picklist Bahan Baku"; }
else if ($mod=="18" or $mod=="18v" or $mod=="18e")
{	echo "Detail Penerimaan (Temporary)"; }
else if ($mod=="18L")
{	echo "Detail Penerimaan (Location)"; }
else if ($mod=="outdet")
{	echo "Detail Pengeluaran"; }
else if ($mod==19)
{	if ($mode=="Hist") {$jdl="Riwayat Aktivitas";} else {$jdl="Laporan Detail Transaksi";}
	if ($excel=="Y")
  { echo "$nm_company<br>"; 
    echo $jdl." <br><br>";
  }
  else
  { echo "<h1>$nm_company</h1>"; 
    echo $jdl;
  } 
}
else if ($mod=="20" or $mod=="20v")
{	if($mode=="FG")
	{	echo "Pengembalian Barang Jadi"; }
	else
	{	echo "Pengembalian Bahan Baku"; }
}
else if ($mod=="20e")
{	echo "Pengembalian Bahan Baku (Change)"; }
else if ($mod=="38e")
{	echo "Pemasukan WIP (JO) (Change)"; }
else if ($mod=="36e")
{	echo "Pemasukan Scrap (SJ) (Change)"; }
else if ($mod=="21" or $mod=="21v" or $mod=="21e")
{	echo "Retur Bahan Baku"; }
else if ($mod==22)
{	echo "Konversi Satuan Bahan Baku"; }
else if ($mod=="23" or $mod=="23v")
{	echo "QC Pass Bahan Baku"; }
else if ($mod==24)
{	echo "Laporan Monitoring"; }
else if ($mod==25)
{	if ($excel=="Y")
  { #$id_item_fg=$_GET['frexc'];
		#$stylenya=flookup("styleno","masterstyle","id_item='$id_item_fg'");
		echo "<h1>$nm_company"; 
    echo "<br>Laporan Monitoring";
    #echo " Style : ".$stylenya."</h1>";
  }
  else if (isset($_GET['noid'])) 
  { #$id_item_fg=$_GET['noid'];
		#$stylenya=flookup("styleno","masterstyle","id_item='$id_item_fg'");
		echo "<h1>$nm_company"; 
    echo "<br>Laporan Monitoring";
    #echo " Style : ".$stylenya."</h1>";
  }
  else
  { #$id_item_fg=$_POST['txtkpno'];
		#$stylenya=flookup("styleno","masterstyle","id_item='$id_item_fg'");
		echo "<h1>$nm_company"; 
    echo "<br>Laporan Monitoring";
    #echo " Style : ".$stylenya."</h1>";
  } 
}
else if ($mod=="26" or $mod=="26v")
{	if($mode=="General")
	{	echo "Pemasukan Item General (PO)"; }
	else if($mode=="WIP")
	{	echo "Pemasukan Barang Dalam Proses (PO)"; }
	else
	{	echo "Pemasukan Bahan Baku (PO)"; }
}
else if ($mod=="26e")
{	if($mode=="General")
	{	echo "Pemasukan Item General (PO) (Change)"; }
	else
	{	echo "Pemasukan Bahan Baku (PO) (Change)"; }
}
else if ($mod==27)
{	echo "Update Dokumen Pabean"; }
else if ($mod==28)
{	echo "Link Pemasukan BOM"; }
else if ($mod==29)
{	echo "Detail Pemasukan"; }
else if ($mod==30)
{	echo "Laporan Devisa"; }
else if ($mod=="31" or $mod=="31v")
{	echo "Pemasukan Barang Jadi (SO)"; }
else if ($mod=="31e")
{	echo "Pemasukan Barang Jadi (SO) (Change)"; }
else if ($mod=="32" or $mod=="32v")
{	echo "Pengeluaran Barang Jadi (SO)"; }
else if ($mod=="32e")
{	echo "Pengeluaran Barang Jadi (SO) (Change)"; }
else if ($mod=="33" or $mod=="33v")
{	echo "Pemasukan Bahan Baku (JO)"; }
else if ($mod=="40" or $mod=="40v")
{	echo "Pemasukan Bahan Baku (Consolidate)"; }
else if ($mod=="33e")
{	echo "Pemasukan Bahan Baku (JO) (Change)"; }
else if ($mod=="34" or $mod=="34v")
{	echo "Pengeluaran Bahan Baku (JO)"; }
else if ($mod=="35" or $mod=="35v" or $mod=="35e")
{	echo "Pengeluaran Bahan Baku (Request)"; }
else if ($mod=="36" or $mod=="36v")
{	echo "Pemasukan Scrap (SJ)"; }
else if ($mod=="37" or $mod=="37v")
{	if($mode=="Scrap")
	{	echo "Pengeluaran Scrap (JO)"; }
	else
	{	echo "Pengeluaran WIP (JO)"; } 
}
else if ($mod=="37e")
{	if($mode=="Scrap")
	{	echo "Pengeluaran Scrap (JO) (Change)";	} 
	else
	{	echo "Pengeluaran WIP (JO) (Change)";	}
}
else if ($mod=="mdeathstock")
{	echo "Master Death Stock"; }
else if ($mod=="39")
{	echo "Laporan Material Status"; }
else if ($mod=="tpapp")
{	echo "Transfer Booking Stock"; }
else if ($mod=="cai")
{	echo "Cancel Pemasukan"; }
else if ($mod=="cao")
{	echo "Cancel Pengeluaran"; }
else
{	if ($_SESSION['bahasa']=="Indonesia")
	{	$menu=flookup("nama_pilihan","masterpilihan","kode_pilihan='Menu$mod'"); }
	else
	{	$mod=$mod.'en';
		$menu=flookup("nama_pilihan","masterpilihan","kode_pilihan='Menu$mod'"); 
	}
	echo $menu;
}
?>
