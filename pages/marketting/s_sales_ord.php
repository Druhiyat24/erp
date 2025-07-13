<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";
if (isset($_GET['id'])) {$id_so = $_GET['id']; } else {$id_so = "";}
if ($id_so!="") { $cekjo = flookup("id_jo","jo_det","id_so='$id_so'"); } else { $cekjo = ""; }
if (isset($_GET['pro'])) {$pro = $_GET['pro']; } else {$pro = "";}

$txtid_cost = nb($_POST['txtid_cost']);
$txtbuyerno = nb($_POST['txtbuyerno']);
$txtso_no = nb($_POST['txtso_no']);
$txtso_date = fd($_POST['txtso_date']);
$txtqty = nb($_POST['txtqty']);
$txtseason = nb($_POST['txtseason']);
$txtterms = $_POST['txtterms'];
$txtdays = $_POST['txtdays'];
$txtunit = nb($_POST['txtunit']);
$txtcurr = nb($_POST['txtcurr']);
$txtfob = nb($_POST['txtfob']);
$txttax = nb($_POST['txttax']);
$txtjns_so = nb($_POST['txtjns_so']);
$txtket_blc = nb($_POST['txtket_blc']);
/* CHECK BLOCK BUYER */
//id buyer
$i_buyer=flookup("id_buyer","act_costing","id='{$txtid_cost}'");
$akses_buyer=flookup("b_mkt","tbl_block_cust","id_cust='{$i_buyer}'");


if($akses_buyer == '1'){
	
	$_SESSION['msg'] = 'Tidak Bisa Create SO Karena Customer/Buyer Di Block';
	
	echo "<script>
		 window.location.href='../marketting/?mod=7L';
	</script>";	
	return "true";
	
}
if (isset($_FILES['txtfile']))
{ $nama_file = $_FILES['txtfile']['name'];
  $tmp_file = $_FILES['txtfile']['tmp_name'];
  $path = "upload_files/so/".$nama_file;
  move_uploaded_file($tmp_file, $path);
}
else
{ $nama_file=""; }
if (($txtso_no=="" and $id_so=="") or ($txtso_no=="" and $id_so!="" and $pro=="Copy"))
{	$date=fd($txtso_date);
	$cri2="SO/".date('my',strtotime($date));
	$txtso_no=urutkan_inq("SO-".date('Y',strtotime($date)),$cri2); 
}
#$cek = flookup("count(*)","so","id_cost='$txtid_cost'");
$cek = 0; # 1 Act Costing Bisa Beberapa SO
if (($cek=="0" and $id_so=="") or ($cek=="0" and $id_so!="" and $pro=="Copy" and $cekjo==""))
{	$sql = "insert into so (id_cost,buyerno,so_no,so_date,qty,unit,curr,fob,nm_file,username,tax,id_season,id_terms,jml_pterms,jns_so,ket_blc)
		values ('$txtid_cost','$txtbuyerno','$txtso_no','$txtso_date','$txtqty','$txtunit','$txtcurr','$txtfob',
		'$nama_file','$user','$txttax','$txtseason','$txtterms','$txtdays','$txtjns_so','$txtket_blc')";
	insert_log($sql,$user);
	if ($id_so!="" and $pro=="Copy")
	{	$id_so_new=flookup("id","so","so_no='$txtso_no'");
		$sql="insert into so_det (id_so,dest,color,size,qty,unit,sku,barcode,notes,reff_no) 
			select '$id_so_new',dest,color,size,qty,unit,sku,barcode,notes,reff_no
			from so_det where id_so='$id_so'";
		insert_log($sql,$user);
	}
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	$id=flookup("id","so","so_no='$txtso_no'");
	echo "<script>
		 window.location.href='../marketting/?mod=$mod&id=$id';
	</script>";
}
else
{	$cek=flookup("count(*)","jo_det","id_so='$id_so'");
	$dateskrg=date('Y-m-d');
	$cek2=flookup("so_no","unlock_so","id_so='$id_so' and DATE_ADD(unlock_date, INTERVAL 2 DAY)>'$dateskrg'");
	if ($cek!="0" and $cek2=="")
	{	$_SESSION['msg'] = 'XData Tidak Bisa Dirubah Karena Sudah Dibuat Worksheet';	}
	else
	{	$sql = "update so set buyerno='$txtbuyerno',
			qty='$txtqty',unit='$txtunit',curr='$txtcurr',fob='$txtfob',username='$user',
			tax='$txttax',id_season='$txtseason',id_terms='$txtterms',jml_pterms='$txtdays',jns_so='$txtjns_so',ket_blc='$txtket_blc'
			where id='$id_so'";
		insert_log($sql,$user);
		$_SESSION['msg'] = 'Data Berhasil Dirubah';
	}
	echo "<script>
		 window.location.href='../marketting/?mod=7L';
	</script>";
}
?>