<?php
$mod=$_GET['mod'];
$grafik=flookup("grafik","mastercompany","company!='' ");
if ($mod==1)
{	if ($grafik=="Y")
	{	include "dashboard2.php";	}
	else
	{	include "dashboard.php";	}
}
else if ($mod==2)
{	$akses = flookup("generate_kode","userpassword","username='$user'");
	if ($akses=="1" AND $mode=="Bahan_Baku")
	{ include "master_item_nag.php"; }
	else
	{ include "master_item.php"; }
}
// else if ($mod=="7_n")
// {	include "HistoryPage/HeaderPage.php";	}
else if ($mod=="777")
{	include "history.php";	}
else if ($mod=="777_exc")
{	include "history_exc.php";	}

else if ($mod=="778")
{	include "lap_mut_brgjadi_new.php";	}
else if ($mod=="778_exc")
{	include "lap_mut_brgjadi_new_exc.php";	}

else if ($mod=="3L" or $mod=="3")
{	include "master_fg.php";	}
else if ($mod==4)
{	include "master_supcust.php";	}
else if ($mod>=91 AND $mod<=95)
{	include "bpb_sample.php";	}
else if ($mod>=51 AND $mod<=55)
{	include "bpb.php";	}
else if ($mod=="551e" or $mod=="553e")
{	include "ri_ed.php";	}
else if ($mod>=61 AND $mod<=65)
{	if ($mod=="61r" or $mod=="61rv" or $mod=="61rvp" or $mod=="61re")
	{include "bppb_req.php";}
	elseif ($mod=="61rs")
	{include "bppb_req_stat.php";}
	elseif ($mod=="61z")
	{include "bppb_gen_new.php";}			
	else
	{include "bppb.php";}	
}
else if ($mod=="list_bppb_req" or $mod=="new_bppb_req" or $mod=="det_bppb_req")
{	include "bppb_req_new.php";	}
else if ($mod=="list_bppb_out" or $mod=="new_bppb_out" or $mod=="det_bppb_out")
{	include "bppb_out_new.php";	}
else if ($mod==633)
{	include "bppb_gen_new.php";	}
// RMN
else if ($mod==66)
{	include "bppb_gb.php";	}
else if ($mod=="tampil_lap_bc")
{	include "tampil_lap_bc.php";	}
else if ($mod=="view_in")
{	include "tampil_masuk.php";	}
else if ($mod=="view_mut")
{	include "tampil_mutasi.php";	}
else if ($mod=="view_mut_lap_data")
{	include "tampil_mutasi2.php";	}
else if ($mod==67)
{	include "bppb.php";	}
else if ($mod==71 OR $mod==72 OR $mod==73)
{	include "laporan.php";	}
else if ($mod==711 OR $mod==722)
{	include "laporan_2_excel.php";	}
else if ($mod==7)
{	include "lap_in.php";	}
else if ($mod=="lap_bc")
{	include "lap_bc.php";	}
else if ($mod=="lap_mutasi_wip")
{	include "lap_data_mutasi_wip.php";	}
//==adyz=====================================================
else if ($mod==77)
{	include "lap_in_ig.php";	}
else if ($mod==78)
{	include "lap_in_fg.php";	}
else if ($mod==7888)
{	include "lap_in_fg_new.php";	}
else if ($mod==788)
{	include "lap_in_brgjadistok.php";	}
else if ($mod==789)
{	include "lap_out_fg_invoice.php";	}
else if ($mod==790)
{	include "lap_in_wip_cut.php";	}
else if ($mod==791)
{	include "lap_in_wip_dc.php";	}
else if ($mod==792)
{	include "lap_in_wip_subkon.php";	}
else if ($mod==793)
{	include "lap_in_wip_sewing.php";	}
else if ($mod==794)
{	include "lap_mut_bhnbaku2_det.php";	}
else if ($mod==79)
{	include "lap_in_new.php";	}
else if ($mod=="view_mut_new")
{	include "tampil_mutasi_new.php";	}
else if ($mod=="view_mut_bhnbaku2det")
{	include "tampil_mutasi_bhnbaku2det.php";	}
else if ($mod=="view_mut_ig")
{	include "tampil_mutasi_ig.php";	}
else if ($mod=="view_mut_fg")
{	include "tampil_mutasi_fg.php";	}
else if ($mod=="view_mut_brgjadistok")
{	include "tampil_mutasi_brgjadistok.php";	}
else if ($mod=="view_mut_fg_out_invoice")
{	include "tampil_mutasi_fg_out_invoice.php";	}
else if ($mod=="view_mut_wip_cut")
{	include "tampil_mutasi_wip_cut.php";	}
else if ($mod=="view_mut_wip_dc")
{	include "tampil_mutasi_wip_dc.php";	}
else if ($mod=="view_mut_wip_subkon")
{	include "tampil_mutasi_wip_subkon.php";	}
else if ($mod=="view_mut_wip_sewing")
{	include "tampil_mutasi_wip_sewing.php";	}
else if ($mod=="81")
{	include "mutasi_antarws.php";	}
else if ($mod=="82")
{	include "baso.php";	}
//----------------------------------------------------------
else if ($mod==8)
{	include "stock_opname.php";	}
//ADYZ====================================================================================
else if ($mod==81)
{	include "tampil_mutasi_gdfb.php";	}
//----------------------------------------------------------------------------------------
else if ($mod==9)
{	include "import_tpb.php";	}
else if ($mod==10)
{	include "rubah_pass.php";	}
else if ($mod==11)
{	include "rubah_type.php";	}
else if ($mod==111)
{	include "rubah_tgl.php";	}
else if ($mod==12)
{	include "rubah_type_item.php";	}
else if ($mod==13)
{	include "rubah_seri.php";	}
else if ($mod==14)
{	if ($mode=="FG" AND $nm_company=="PT. Tun Hong")
	{ include "kartu_stock_fg.php"; }
	else
	{ include "kartu_stock.php"; }
}
else if ($mod==15)
{	include "import_bom.php";	}
else if ($mod==16)
{	include "bom.php";	}
else if ($mod==17)
{	include "konfirmasi.php";	}
else if ($mod=="18" or $mod=="18v")
{	include "bpb_roll.php";	}
else if ($mod=="18e")
{	include "bpb_roll_ed.php";	}
else if ($mod=="18L" or $mod=="18LV")
{	include "bpb_roll_loc.php";	}
//ADYZ BARCODE==================================================================================
else if ($mod=="18z" or $mod=="18sb")
{	include "bpb_roll_barcode.php";	}
//--------------------------------------------------------------------------------------
else if ($mod=="bpb_global" or $mod=="bpb_global_v" or $mod=="bpb_global_item" or $mod=="bpb_global_item_lokasi")
{	include "bpb_global.php";	}

else if ($mod=="bpb_po" or $mod=="bpb_po_v" or $mod=="bpb_po_item" or $mod=="bpb_po_item_lokasi")
{	include "bpb_po_std.php";	}
else if ($mod=="bpb_po_std_exc")
{	include "bpb_po_std_exc.php";	}
else if ($mod=="konfirmasi_new")
{	include "konfirmasi_new.php";	}
else if ($mod=="lap_data")
{	include "lap_data.php";	}
else if ($mod=="lap_data_exc")
{	include "lap_data_exc.php";	}
else if ($mod=="outdet")
{	include "bppb_roll.php";	}
else if ($mod==19)
{	include "detail_trans.php";	}
else if ($mod=="20" or $mod=="20v")
{	include "ri.php";	}
else if ($mod=="20e" or $mod=="36e" or $mod=="38e" or $mod=="33e")
{	include "ri_ed.php";	}
else if ($mod=="21" or $mod=="21v" or $mod=="Retur_accs")
{	include "ro.php";	}
else if ($mod=="21e")
{	include "ro_ed.php";	}
else if ($mod==22)
{	include "konv.php";	}
else if ($mod=="23" or $mod=="23v")
{	include "qc_pass.php";	}
else if ($mod=="231" or $mod=="23z")
{	include "qc_pass_f.php";	}
else if ($mod==24)
{	include "bom_mon.php";	}
else if ($mod==25)
{	if($nm_company=="PT. Tun Hong")
	{	include "tampil_bom_mon.php";	}
	else
	{	include "tampil_bom_mon_erp.php";	}
}
else if ($mod=="26" or $mod=="26v")
{	include "bpb_po.php";	}
else if ($mod=="26_dev" or $mod=="26v_dev")
{	

 include "bpb_po_dev.php";	}
else if ($mod=="26e")
{	include "bpb_po_ed.php";	}
else if ($mod=="261" or $mod=="26z")
{	include "bpb_po_new.php";	}
else if ($mod==28)
{	include "bpb_to_bom.php";	}
else if ($mod==29)
{	include "bpb_detail.php";	}
else if ($mod==30)
{	include "r_devisa.php";	}
else if ($mod=="31" or $mod=="31v")
{	include "bpb_so.php";	}
else if ($mod=="311" or $mod=="31z")
{	include "bpb_so_switchin.php";	}
else if ($mod=="31e")
{	include "ri_ed.php";	}
else if ($mod=="32" or $mod=="32v")
{	include "bppb_so.php";	}
else if ($mod=="321" or $mod=="32z" or $mod=="321ed")
{	include "bppb_so_new.php";	}
else if ($mod=="322" or $mod=="32x")
{	include "bppb_so_switchout.php";	}
else if ($mod=="323" or $mod=="32y")
{	include "bppb_so_jdstok.php";	}
else if ($mod=="32e")
{	include "bppb_out_so.php";	}
else if ($mod=="33" or $mod=="33v")
{	include "bpb_jo.php";	}
else if ($mod=="40" or $mod=="40v")
{	include "bpb_consol.php";	}
else if ($mod=="34" or $mod=="34v")
{	include "bppb_jo.php";	}
else if ($mod=="35" or $mod=="35v" or $mod=="35e")
{	include "bppb_out_req.php";	}
else if ($mod=="355" or $mod=="355v" or $mod=="355e")
{	include "bppb_req_barcode_new.php";	}
else if ($mod=="36" or $mod=="36v")
{	include "bpb_sc_sj.php";	}
else if ($mod=="37" or $mod=="37v")
{	include "bppb_sc_jo.php";	}
else if ($mod=="37_bppb_po" or $mod=="37v_bppb_po")
{	include "bppb_po.php";	}
else if ($mod=="37e")
{	include "bppb_out_jo.php";	}
else if ($mod=="38" or $mod=="38v")
{	include "bpb_wip_jo.php";	}
else if ($mod=="39")
{	include "LaporanMaterialList/HeaderPage.php";	}
else if ($mod=="tpapp")
{	include "transfer_book.php";	}
else if ($mod=="mdeathstock")
{	include "DeathStockPage/HeaderPage.php"; }
else if ($mod=="mr_hist")
	{	include "mut_rak_hist.php";	}
else if ($mod=="mr")
	{	include "mut_rak.php";	}
else if ($mod=="mr_acc")
	{	include "mut_rak_acc.php";	}
else if ($mod=="adj_rak")
	{	include "adj_rak.php";	}
else if ($mod=="adj_rak_hist")
	{	include "adj_rak_hist.php";	}
else if ($mod=="cai")
{	include "cancel_in.php";	}
else if ($mod=="cao")
{	include "cancel_out.php";	}
else if ($mod=="car")
{	include "cancel_req.php";	}
else if ($mod=="31_bj_po" or $mod=="31v_bj_po")
{	include "bpb_so_po.php";	}
else if ($mod=="ovtinv" or $mod=="31v_bj_po")
{	include "../pur/over_ship_alloc.php";	}
else if ($mod=="rptreqmat")
{	include "rep_req_mat.php";	}
else if ($mod=="rptrkonf")
{	include "rep_konf.php";	}
else
{	echo "<h1>Halaman tidak tersedia</h1>";	}
