<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode=$_GET['mode'];
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$gen_nomor_int=$rscomp["gen_nomor_int"];
	$auto_ap_ar=$rscomp["auto_ap_ar"];
$txtbppbno_ri = nb($_POST['txtsjno']);
$tgl_ri = fd($_POST['txtbpbdate']);
$jenis_dok=$_POST['txtstatus_kb'];
if (isset($_POST['txttujuan'])) {$txttujuan=$_POST['txttujuan'];} else {$txttujuan="";}
$txtremark=$_POST['txtnotes'];
$txtbcno=$_POST['txtbcno'];
$txtbcdate=fd($_POST['txtbcdate']);
$txtbcaju="";
$txttglaju="";
if($mode=="FG")
{	$cbomat=substr($txtbppbno_ri,3,2); }
else
{	$cbomat=substr($txtbppbno_ri,3,1); }
if (!isset($_POST['item']))
{	$_SESSION['msg'] = "XTidak Ada Data Yang Harus Disimpan";
	echo "<script>window.location.href='../forms/?mod=$mod&mode=$mode';</script>"; 
}
else
{	$QtySJ = $_POST['itemstock'];
	$QtyRI = $_POST['item'];
	$JOArr = $_POST['jo'];
	
	$txtrino=urutkan("Add_RI",$cbomat);
	if($gen_nomor_int=="Y")
	{	$cbomat2=flookup("nama_pilihan","masterpilihan","kode_pilihan='MAP_MAT_".$cbomat."'");
		$date=fd($tgl_ri);
		$cri2=$cbomat2."/RI/".date('my',strtotime($date));
		$txtrino2=urutkan_inq($cbomat2."-IN-".date('Y',strtotime($date)),$cri2);
	}
	else
	{ $txtrino2=""; }
	foreach ($QtyRI as $key => $value) 
	{	if (is_numeric($value))
		{	$txtqtyri = $QtyRI[$key];
	    $keysplit=explode("|",$key);
	    $id_item = $keysplit[0];
	    $id_jo = $JOArr[$key];
			if($mode=="FG") { $sqlcri=""; } else { $sqlcri="and id_jo='$id_jo'"; }
			$sql="select * from bppb where bppbno='$txtbppbno_ri' 
	    	and id_item='$id_item' $sqlcri ";
	    $databppb=mysql_fetch_array(mysql_query($sql));
	    $txtid_item_fg=$databppb['id_item_fg'];
	    $txtunit=$databppb['unit'];
	    $txtcurr=$databppb['curr'];
	    $txtprice=$databppb['price'];
	    $txtjam_masuk="";
	    $txtberat_bersih=0;
	    $txtberat_kotor=0;
	    $txtnomor_mobil="";
	    $txtpono="";
	    $txtid_supplier=$databppb['id_supplier'];
	    $txtinvno=$_POST['txtsjno2'];
	    $txtkpno=$databppb['kpno'];
	    $txtid_gudang=$databppb['id_gudang'];
	    $txtid_so_det=$databppb['id_so_det'];
	    $txtnomor_rak="";
	    $retur="Y";
	    $sql = "insert into bpb (id_item,id_item_fg,qty,unit,curr,price,remark,jam_masuk,berat_bersih,berat_kotor,nomor_mobil,pono,id_supplier,
				invno,bcno,bcdate,bpbno,bpbno_int,bpbdate,jenis_dok,tujuan,username,use_kite,nomor_aju,tanggal_aju,
				kpno,id_gudang,nomor_rak,status_retur,bppbno_ri,bppbno,id_jo,id_so_det)
				values ('$id_item','$txtid_item_fg','$txtqtyri','$txtunit','$txtcurr','$txtprice','$txtremark','$txtjam_masuk','$txtberat_bersih',
				'$txtberat_kotor','$txtnomor_mobil','$txtpono','$txtid_supplier','$txtinvno','$txtbcno','$txtbcdate',
				'$txtrino','$txtrino2','$tgl_ri','$jenis_dok','$txttujuan','$user','1','$txtbcaju','$txttglaju','$txtkpno',
				'$txtid_gudang','$txtnomor_rak','$retur','$txtbppbno_ri','$txtbppbno_ri','$id_jo','$txtid_so_det')";
			insert_log($sql,$user);
			calc_stock($cbomat,$id_item);
	  }
	}	
	$_SESSION['msg'] = "Data Berhasil Disimpan Dengan Nomor RI : ".$txtrino." (".$txtrino2.")";
	echo "<script>window.location.href='../forms/?mod=20v&mode=$mode';</script>";
}
?>