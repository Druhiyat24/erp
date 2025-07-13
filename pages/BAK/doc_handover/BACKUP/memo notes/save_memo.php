<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$mod=$_GET['mod'];

if ($mod == 'simpan_header')
{
	$txtmemodate	=fd($_POST['txtmemodate']);
	$cbokpd			=nb($_POST['cbokpd']);
	$cbosupp 		=nb($_POST['cbosupp']);
	$jns_trans		=nb($_POST['jns_trans']);
	$jns_pengiriman	=nb($_POST['jns_pengiriman']);
	$cbobuyer       =nb($_POST['cbobuyer']);
	$ditagihkan     =nb($_POST['ditagihkan']);
	$curr     		=nb($_POST['curr']);
	$txtjth_tempo	=nb($_POST['txtjth_tempo']);
	$dok_pendukung	=nb($_POST['dok_pendukung']);
	$dateinput		=date('Y-m-d H:i:s');

	$sql_cari  = mysql_query("select max(nm_memo) urut from memo_h where YEAR(CURRENT_DATE()) and MONTH(CURRENT_DATE())");
	$row_cari = mysql_fetch_array($sql_cari);
	$kodepay = $row_cari['urut'];
	$urutan = (int) substr($kodepay, 15, 5);
	if ($urutan == "")
	{
		$urutan = "00001";
	}
	else
	{
		$urutan = $urutan;
	}
	$urutan++;
	$tahun   = substr(date('Y',strtotime($txtmemodate)), 2, 2);
	$bulan   = substr(date('m',strtotime($txtmemodate)), 0, 2);	
	$nm_memo = "MEMO/NAG/$tahun$bulan/";
	$kodepay_f = $nm_memo.sprintf("%05s",$urutan);	


		    $sql = "insert into memo_h (nm_memo, tgl_memo, kepada, id_supplier, jns_trans, jns_pengiriman, id_buyer, 
			ditagihkan, curr, jatuh_tempo, dok_pendukung, date_input, status, jns_inv, user) 
			values ('$kodepay_f','$txtmemodate','$cbokpd','$cbosupp','$jns_trans','$jns_pengiriman','$cbobuyer',
			'$ditagihkan','$curr','$txtjth_tempo','$dok_pendukung','$dateinput','DRAFT','INVOICE','$user')";
			insert_log($sql,$user);
	
	$cekheader=flookup("id_h","memo_h","nm_memo='$kodepay_f'");
	if ($cekheader != "")
	{
	$ItemArray = $_POST['id_cek'];
	$NoArr	   = $_POST['no_invoice'];
	foreach ($ItemArray as $key => $value)		
		{
			if ($value!="")
			{
			$id_book=$ItemArray[$key];
			$no_invoice=$NoArr[$key];
	
			$sql_insert_inv = "insert into memo_inv (id_h,id_book,no_invoice) values
						('$cekheader','$id_book','$no_invoice')";
						insert_log($sql_insert_inv,$user);

			$sql_update_book = "update tbl_book_invoice set stat_memo = 'Y' where id = '$id_book'";
			insert_log($sql_update_book,$user);	

			}
		}		

			$sql_insert_det = "insert into memo_det (id_h,id_ctg,nm_ctg,id_sub_ctg,nm_sub_ctg,biaya,cancel)
			select $cekheader,a.id_ctg, b.nm_ctg, a.id_sub_ctg, c.nm_sub_ctg, a.biaya, 'N' from memo_det_tmp a
			inner join master_memo_ctg b on a.id_ctg = b.id_ctg
			inner join master_memo_subctg c on a.id_sub_ctg = c.id_sub_ctg
			where user = '$user'";
						insert_log($sql_insert_det,$user);


			{$_SESSION['msg']="Data Berhasil Disimpan, Nomor Memo : ".$kodepay_f;}
			echo "<script>window.location.href='../shp/?mod=memo_edit&id_h=$cekheader';</script>";
	}	
}


if ($mod == 'simpan_header_non_inv')
{
	$txtmemodate	=fd($_POST['txtmemodate']);
	$cbokpd			=nb($_POST['cbokpd']);
	$cbosupp 		=nb($_POST['cbosupp']);
	$jns_trans		=nb($_POST['jns_trans']);
	$jns_pengiriman	=nb($_POST['jns_pengiriman']);
	$cbobuyer       =nb($_POST['cbobuyer']);
	$ditagihkan     =nb($_POST['ditagihkan']);
	$curr     		=nb($_POST['curr']);
	$txtjth_tempo	=nb($_POST['txtjth_tempo']);
	$dok_pendukung	=nb($_POST['dok_pendukung']);
	$txtnotes 		=nb($_POST['txtnotes']);
	$dateinput		=date('Y-m-d H:i:s');

	$sql_cari  = mysql_query("select max(nm_memo) urut from memo_h where YEAR(CURRENT_DATE()) and MONTH(CURRENT_DATE())");
	$row_cari = mysql_fetch_array($sql_cari);
	$kodepay = $row_cari['urut'];
	$urutan = (int) substr($kodepay, 15, 5);
	if ($urutan == "")
	{
		$urutan = "00001";
	}
	else
	{
		$urutan = $urutan;
	}
	$urutan++;
	$tahun   = substr(date('Y',strtotime($txtmemodate)), 2, 2);
	$bulan   = substr(date('m',strtotime($txtmemodate)), 0, 2);	
	$nm_memo = "MEMO/NAG/$tahun$bulan/";
	$kodepay_f = $nm_memo.sprintf("%05s",$urutan);	


		    $sql = "insert into memo_h (nm_memo, tgl_memo, kepada, id_supplier, jns_trans, jns_pengiriman, id_buyer, 
			ditagihkan, curr, jatuh_tempo, dok_pendukung, date_input, status, jns_inv, user, notes) 
			values ('$kodepay_f','$txtmemodate','$cbokpd','$cbosupp','$jns_trans','$jns_pengiriman','$cbobuyer',
			'$ditagihkan','$curr','$txtjth_tempo','$dok_pendukung','$dateinput','DRAFT','NON INVOICE','$user','$txtnotes')";
			insert_log($sql,$user);		

	$cekheader=flookup("id_h","memo_h","nm_memo='$kodepay_f'");
	if ($cekheader != "")
	{
	

			$sql_insert_det = "insert into memo_det (id_h,id_ctg,nm_ctg,id_sub_ctg,nm_sub_ctg,biaya,cancel)
			select $cekheader,a.id_ctg, b.nm_ctg, a.id_sub_ctg, c.nm_sub_ctg, a.biaya, 'N' from memo_det_tmp a
			inner join master_memo_ctg b on a.id_ctg = b.id_ctg
			inner join master_memo_subctg c on a.id_sub_ctg = c.id_sub_ctg
			where user = '$user'";
						insert_log($sql_insert_det,$user);


			{$_SESSION['msg']="Data Berhasil Disimpan, Nomor Memo : ".$kodepay_f;}
			echo "<script>window.location.href='../shp/?mod=memo_edit&id_h=$cekheader';</script>";
	}
		
}

if ($mod == 'simpan_temp')
{
	$cbokat			=nb($_POST['cbokat']);
	$cbosubkat 		=nb($_POST['cbosubkat']);
	$txtbiaya		=nb($_POST['txtbiaya']);


		    $sql = "insert into memo_det_tmp (id_ctg, id_sub_ctg, biaya, user) 
			values ('$cbokat','$cbosubkat','$txtbiaya','$user')";
			insert_log($sql,$user);			

			{$_SESSION['msg']="Data Berhasil Disimpan";}
			echo "<script>window.location.href='../forms/?mod=bpb_global_item&mode=Bahan_Baku&bpbno=$txtbpbno';</script>";
		
}

if ($mod == 'cancel_item')
{
	$id_h = $_GET['id_h'];
	$id	  = $_GET['idd'];


		    $sql = "update memo_det set cancel = case when cancel = 'Y' then'N' else 'Y' end where id_h = '$id_h' and id = '$id'";
			insert_log($sql,$user);			

			{$_SESSION['msg']="Data Berhasil Dicancel";}
			echo "<script>window.location.href='../shp/?mod=memo_edit&id_h=$id_h';</script>";
		
}

if ($mod == 'update_biaya')
{
	$id_h = $_GET['id_h'];

	$ItemArray = $_POST['id_cek'];
	$BiayaArray = $_POST['edit_biaya'];
	$CancelArray = $_POST['cancel'];
	foreach ($CancelArray as $key => $value)
	{
		if ($value=="N")
		{
			$id=$ItemArray[$key];
			$biaya=$BiayaArray[$key];
			$cancel=$CancelArray[$key];

		    $sql = "update memo_det set biaya = '$biaya' where id_h = '$id_h' and id = '$id'";
			insert_log($sql,$user);	

			{$_SESSION['msg']="Data Berhasil DiRubah";}
			echo "<script>window.location.href='../shp/?mod=memo_edit&id_h=$id_h';</script>";

		}
	}



		    $sql = "update memo_det set biaya = '$biaya' where id_h = '$id_h' and id = '$id' 1";
			insert_log($sql,$user);			

			{$_SESSION['msg']="Data Biaya Dirubah";}
			echo "<script>window.location.href='../shp/?mod=memo_edit&id_h=$id_h';</script>";
		
}

if ($mod == 'update_header')
{
	$id_h = $_GET['id_h'];
	$txtmemodate	=fd($_POST['txtmemodate']);
	$cbokpd			=nb($_POST['cbokpd']);
	$cbosupp 		=nb($_POST['cbosupp']);
	$jns_trans		=nb($_POST['jns_trans']);
	$jns_pengiriman	=nb($_POST['jns_pengiriman']);
	$ditagihkan     =nb($_POST['ditagihkan']);
	$curr     		=nb($_POST['curr']);
	$txtjth_tempo	=nb($_POST['txtjth_tempo']);
	$dok_pendukung	=nb($_POST['dok_pendukung']);

	$nm_memo=flookup("nm_memo","memo_h","id_h='$id_h'");

		    $sql = "update memo_h set tgl_memo = '$txtmemodate', kepada = '$cbkopd', id_supplier = '$cbosupp', jns_trans = '$jns_trans', jns_pengiriman = '$jns_pengiriman', ditagihkan = '$ditagihkan', curr = '$curr', jatuh_tempo = '$txtjth_tempo', dok_pendukung = '$dok_pendukung' where id_h = '$id_h'";
			insert_log($sql,$user);
	
			{$_SESSION['msg']="Data Berhasil Diupdate, Nomor Memo : ".$nm_memo;}
			echo "<script>window.location.href='../shp/?mod=memo_edit&id_h=$id_h';</script>";
		
}

if ($mod == 'update_header_non_inv')
{
	$id_h = $_GET['id_h'];
	$txtmemodate	=fd($_POST['txtmemodate']);
	$cbokpd			=nb($_POST['cbokpd']);
	$cbosupp 		=nb($_POST['cbosupp']);
	$jns_trans		=nb($_POST['jns_trans']);
	$jns_pengiriman	=nb($_POST['jns_pengiriman']);
	$ditagihkan     =nb($_POST['ditagihkan']);
	$curr     		=nb($_POST['curr']);
	$txtjth_tempo	=nb($_POST['txtjth_tempo']);
	$dok_pendukung	=nb($_POST['dok_pendukung']);
	$txtnotes		=nb($_POST['txtnotes']);

	$nm_memo=flookup("nm_memo","memo_h","id_h='$id_h'");

		    $sql = "update memo_h set tgl_memo = '$txtmemodate', kepada = '$cbokpd', id_supplier = '$cbosupp', jns_trans = '$jns_trans', jns_pengiriman = '$jns_pengiriman', ditagihkan = '$ditagihkan', curr = '$curr', jatuh_tempo = '$txtjth_tempo', dok_pendukung = '$dok_pendukung', notes = '$txtnotes' where id_h = '$id_h'";
			insert_log($sql,$user);
	
			{$_SESSION['msg']="Data Berhasil Diupdate, Nomor Memo : ".$nm_memo;}
			echo "<script>window.location.href='../shp/?mod=memo_edit_non_inv&id_h=$id_h';</script>";
		
}

if ($mod == 'tambah_det')
{
	$id_h = $_GET['id_h'];
	$id_kat_add = nb($_POST['cbokat']);
	$id_sub_kat_add = nb($_POST['cbosubkat']);
	$biaya_add = nb($_POST['txtbiaya']);

	$nm_memo=flookup("nm_memo","memo_h","id_h='$id_h'");

	$sql = "insert into memo_det (id_h,id_ctg,nm_ctg,id_sub_ctg,nm_sub_ctg,biaya,cancel) 
	select '$id_h',b.id_ctg, b.nm_ctg, c.id_sub_ctg, c.nm_sub_ctg, '$biaya_add', 'N' from
	master_memo_ctg b 
	inner join master_memo_subctg c on b.id_ctg = c.id_ctg
	where b.id_ctg = '$id_kat_add' and c.id_sub_ctg = '$id_sub_kat_add'";
	insert_log($sql,$user);
	
			{$_SESSION['msg']="Data Berhasil Diupdate, Nomor Memo : ".$nm_memo;}
			echo "<script>window.location.href='../shp/?mod=memo_edit&id_h=$id_h';</script>";
		
}



if ($mod == 'simpan')
{
	$txtid_supplier	=nb($_POST['cbosupp']);
	$cbomat			=nb($_POST['cbotipe']);
	$txtbpbdate 	=fd($_POST['txtbpbdate']);
	$txtjns_in		=nb($_POST['txtjns_in']);
	$status_kb	    =nb($_POST['txtstatus_kb']);
	$txtbcno        =nb($_POST['txtbcno']);
	$txtbcdate      =fd($_POST['txtbcdate']);
	$txtinvno		=nb($_POST['txtinvno']);
	$txtremark		=nb($_POST['txtremark']);
	$dateinput		=date('Y-m-d H:i:s');

	$txtbpbno = urutkan("Bahan_Baku",$cbomat); 

	$cbomat2=flookup("nama_pilihan","masterpilihan","kode_pilihan='MAP_MAT_".$cbomat."'");
	$date=fd($_POST['txtbpbdate']);
	$cri2=$cbomat2."/IN/".date('my',strtotime($date));
	$txtbpbno2=urutkan_inq($cbomat2."-IN-".date('Y',strtotime($date)),$cri2);
	
		$JmlArray 		= $_POST['qtybpb'];
		$UnitArray 		= $_POST['unitbpb'];
		$Id_itemArray 	= $_POST['id_item'];
		$Id_joArray 	= $_POST['id_jo'];
		$KpnoArray 		= $_POST['kpno'];

		foreach ($JmlArray as $key => $value) 
		{	if ($value!="")
			{
		    $txtid_item = $Id_itemArray[$key];
			$txtqty	 	= $JmlArray[$key];
			$txtunit	= $UnitArray[$key];
			$id_jo	 	= $Id_joArray[$key];
			$txtkpno	= $KpnoArray[$key];


		    {	$sql = "insert into bpb (id_item,qty,unit,curr,price,remark,id_supplier,
				invno,bcno,bcdate,bpbno,bpbno_int,bpbdate,jenis_dok,username,use_kite,
				kpno,status_retur,id_jo,id_sec,jenis_trans)
				values ('$txtid_item','$txtqty','$txtunit','IDR','0','$txtremark',
				'$txtid_supplier','$txtinvno','$txtbcno','$txtbcdate',
				'$txtbpbno','$txtbpbno2',
				'$txtbpbdate','$status_kb','$user','1','$txtkpno',
				'N','$id_jo','0','$txtjns_in')";
			insert_log($sql,$user);
			calc_stock($cbomat,$txtid_item);
			{$_SESSION['msg']="Data Berhasil Disimpan, Nomor BPB : ".$txtbpbno2;}
			echo "<script>window.location.href='../forms/?mod=bpb_global_item&mode=Bahan_Baku&bpbno=$txtbpbno';</script>";
		  	}
		  }
		}	
}

?>