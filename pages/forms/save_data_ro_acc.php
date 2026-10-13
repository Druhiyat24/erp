<?php
include '../../include/conn.php';
include 'fungsi.php';
include 'journal_interface.php';
include 'ro_acc_sumber.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); exit; }
$user=$_SESSION['username'];
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$gen_nomor_int=$rscomp["gen_nomor_int"];
	$auto_ap_ar=$rscomp["auto_ap_ar"];

$mode_ro = isset($_GET['mode']) ? preg_replace('/[^A-Za-z_]/', '', $_GET['mode']) : '';
if ($mode_ro == '') { $mode_ro = 'Bahan_Baku'; }
$url_form = "../forms/?mod=Retur_accs&mode=$mode_ro";
$url_list = "../forms/?mod=21v&mode=$mode_ro";

$txtbpbno_ro = nb($_POST['txtsjno']);
$tgl_ro = fd($_POST['txtbpbdate']);
$jenis_dok=$_POST['txtstatus_kb'];
// nomor & tanggal daftar/aju diisi belakangan oleh Exim
$txtbcno="";
$txtbcdate="0000-00-00";
$txtbcaju="";
$txttglaju="0000-00-00";
$txtremark=$_POST['txtdefect'];
$txtjenis_trans = isset($_POST['txtjenis_trans']) ? nb($_POST['txtjenis_trans']) : "";
$cbomat='A';
$QtyRO = isset($_POST['qty_ro']) ? $_POST['qty_ro'] : array();

// Validasi ulang di server terhadap stok terkini, memakai sumber yang sama dengan tampilan.
// Qty RO dipotong dari baris bpb_det tempat stok itu sekarang berada (bisa baris RI / hasil mutasi),
// bukan selalu dari baris BPB IN (yang stoknya mungkin sudah keluar).
$gagal = array();
$pilih = array();
$sumber = array();
if ($txtbpbno_ro == "" || $jenis_dok == "" || $txtremark == "" || $txtjenis_trans == "")
{ $gagal[] = "Nomor BPB, Jenis Dokumen, Jenis Defect dan Jenis Pengeluaran wajib diisi"; }
else
{
	$sumber = ro_acc_sumber($con_new, $txtbpbno_ro);
	foreach ($QtyRO as $id => $val)
	{
		$qty = (float) str_replace(',', '', $val);
		if ($qty <= 0) { continue; }
		$id = (int) $id;
		if (!isset($sumber[$id]))
		{ $gagal[] = "ID $id bukan stok dari BPB $txtbpbno_ro"; continue; }
		if ($qty > $sumber[$id]['sisa'])
		{ $gagal[] = $sumber[$id]['id_item']." di ".$sumber[$id]['rak'].": qty RO $qty melebihi sisa ".number_format($sumber[$id]['sisa'],2); continue; }
		$pilih[$id] = $qty;
	}
	if (count($gagal) == 0 && count($pilih) == 0) { $gagal[] = "Tidak ada Qty RO yang diisi"; }
}

if (count($gagal) > 0)
{
	$_SESSION['msg'] = "XData tidak disimpan:\\n".implode("\\n", $gagal);
	echo "<script>window.location.href='$url_form';</script>";
	exit;
}

	$txtrono=urutkan("Add_RO",$cbomat);
	if($gen_nomor_int=="Y")
	{	$cbomat2=flookup("nama_pilihan","masterpilihan","kode_pilihan='MAP_MAT_A'");
		$date=fd($tgl_ro);
		$cri2=$cbomat2."/RO/".date('my',strtotime($date));
		$txtrono2=urutkan_inq($cbomat2."-OUT-".date('Y',strtotime($date)),$cri2);
	}
	else
	{ $txtrono2=""; }

	$doc = mysql_real_escape_string($txtbpbno_ro);
	$tersimpan = 0;
	foreach ($pilih as $id => $txtqtyro)
	{
		$row = $sumber[$id];
		$txtiditem = $row['id_item'];
		$txtidjo = $row['id_jo'];

		// harga, supplier, dll tetap dari dokumen BPB yang dipilih user (referensi retur ke supplier & jurnal)
		$databpb=mysql_fetch_array(mysql_query("select * from bpb where bpbno_int='$doc' and id_item = '$txtiditem' and id_jo = '$txtidjo' limit 1"));
		if (!$databpb)
		{ $databpb=mysql_fetch_array(mysql_query("select * from bpb where bpbno_int='$doc' and id_item = '$txtiditem' limit 1")); }
	    $txtid_item_fg=$databpb['id_item_fg'];
	    $txtbpbno=$databpb['bpbno'];
	    $txtunit=$databpb['unit'] != '' ? $databpb['unit'] : $row['unit'];
	    $txtcurr=$databpb['curr'];
	    $txtprice=$databpb['price'];
	    $txtberat_bersih=0;
	    $txtberat_kotor=0;
	    $txtnomor_mobil="";
	    $txtid_supplier=$databpb['id_supplier'];
	    $txtinvno="";
	    $txtkpno=$databpb['kpno'];
	    $txtnomor_rak="";
	    $retur="Y";

		// guard atomik: baris hanya tercatat keluar kalau sisanya masih cukup saat disimpan
		// (mencegah minus karena submit ganda / dipakai transaksi lain bersamaan)
		$sql_det = "insert into bppb_det (bppbno,bppbno_int,bppbno_req,id_item,id_jo,roll_qty,roll_qty_old,unit,id_rak_loc,user,bppbdate,id_bpb_det,cancel)
			select '$txtrono','$txtrono2','',bd.id_item,bd.id_jo,'$txtqtyro','','$txtunit',bd.id_rak_loc,'$user','$tgl_ro','$id','N'
			from bpb_det bd
			where bd.id = '$id'
			and (bd.roll_qty - coalesce((select sum(x.roll_qty) from bppb_det x where x.id_bpb_det = '$id' and x.cancel = 'N'),0) - coalesce(bd.qty_mutasi,0)) >= $txtqtyro";
		mysql_query($sql_det);
		$affected = mysql_affected_rows();
		insert_log("X".$sql_det, $user);
		if ($affected < 1)
		{ $gagal[] = $txtiditem." di ".$row['rak'].": stok berubah saat disimpan"; continue; }

	    $sql = "insert into bppb_temp_ro (id_item,id_item_fg,qty,unit,curr,price,remark,berat_bersih,berat_kotor,nomor_mobil,id_supplier,invno,bcno,bcdate,bppbno,bppbno_int,
				bppbdate,jenis_dok,username,use_kite,nomor_aju,tanggal_aju,kpno,
				nomor_rak,status_retur,bpbno_ro,id_jo,jenis_trans) values ('$txtiditem','$txtid_item_fg',
				'$txtqtyro','$txtunit','$txtcurr','$txtprice','$txtremark','$txtberat_bersih','$txtberat_kotor',
				'$txtnomor_mobil','$txtid_supplier','$txtinvno','$txtbcno','$txtbcdate','$txtrono','$txtrono2',
				'$tgl_ro','$jenis_dok','$user','1','$txtbcaju','$txttglaju',
				'$txtkpno','$txtnomor_rak','$retur','$txtbpbno','$txtidjo','$txtjenis_trans')";
		insert_log($sql,$user);

		calc_stock($cbomat,$txtiditem);
		$tersimpan++;
	}

	if ($tersimpan == 0)
	{
		$_SESSION['msg'] = "XData tidak disimpan:\\n".implode("\\n", $gagal);
		echo "<script>window.location.href='$url_form';</script>";
		exit;
	}

	// bppb_temp_ro dipakai bersama semua user: selalu dibatasi ke nomor RO ini
	$sql_bppb = "insert into bppb (id_item,id_item_fg,qty,unit,curr,price,remark,berat_bersih,berat_kotor,nomor_mobil,id_supplier,invno,bcno,bcdate,bppbno,bppbno_int,
				bppbdate,jenis_dok,username,use_kite,nomor_aju,tanggal_aju,kpno,
				nomor_rak,status_retur,bpbno_ro,id_jo,jenis_trans) (select id_item,id_item_fg,sum(qty),unit,curr,price,remark,berat_bersih,berat_kotor,nomor_mobil,id_supplier,invno,bcno,bcdate,bppbno,bppbno_int,bppbdate,jenis_dok,username,use_kite,nomor_aju,tanggal_aju,kpno,nomor_rak,status_retur,bpbno_ro,id_jo,jenis_trans from bppb_temp_ro where bppbno = '$txtrono' GROUP BY id_item,id_jo)";
	insert_log($sql_bppb,$user);
	$sql_del = "delete from bppb_temp_ro where bppbno = '$txtrono'";
	insert_log($sql_del,$user);

	insert_bpb_gr_ret($txtrono);
	if($auto_ap_ar=="Y") { insert_jurnal_retur("AR",$txtrono,$user); }

	$_SESSION['msg'] = "Data Berhasil Disimpan Dengan Nomor RO : ".$txtrono;
	if (count($gagal) > 0) { $_SESSION['msg'] .= "\\nItem berikut tidak tersimpan:\\n".implode("\\n", $gagal); }
	echo "<script>window.location.href='$url_list';</script>";

?>
