<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }


$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";
if (isset($_GET['id'])) {$id_so = $_GET['id']; } else {$id_so = "";}
if (isset($_GET['pro'])) {$pro = $_GET['pro']; } else {$pro = "";}



$txtkpno = nb($_POST['txtkpno']);
$txtdate = fd($_POST['txtdate']);
$txtid_buyer = nb($_POST['txtid_buyer']);
$txtid_style = nb($_POST['txtid_style']);
$order = nb($_POST['order']);
$txtkode_jenis = implode("|",$_POST['txtkode_jenis']);
$txtperpatdate = fd($_POST['txtperpatdate']);
$txtpersamdate = fd($_POST['txtpersamdate']);
$txtselpatdate = fd($_POST['txtselpatdate']);
$txtselsamdate = fd($_POST['txtselsamdate']);
$txtcekpatdate = fd($_POST['txtcekpatdate']);
$txtselcekpatdate = fd($_POST['txtselcekpatdate']);
$txtkode_lampiran = implode("|",$_POST['txtkode_lampiran']);
$txtkode_acc = implode("|",$_POST['txtkode_acc']);



if (($txtkpno=="" and $id_so=="") or ($txtkpno=="" and $id_so!="" and $pro=="Copy"))
{	$date=fd($txtdate);
	$cri2="REQDEV/".date('my',strtotime($date));
	$txtkpno=urutkan_inq("REQDEV-".date('Y',strtotime($date)),$cri2); 
}

#$cek = flookup("count(*)","so","id_cost='$txtid_cost'");
$cek = 0; # 1 Act Costing Bisa Beberapa SO
if (($cek=="0" and $id_so=="") or ($cek=="0" and $id_so!="" and $pro=="Copy"))
{	$sql = "insert into request_sample_dev (no_req,req_date,id_buyer,id_item,xorder,id_jenis,date_perselpat,date_perselsam,date_selpat,date_selsam,date_cekpat,date_selcekpat,id_lampiran,id_acc,username,cancel)
		values ('$txtkpno','$txtdate','$txtid_buyer','$txtid_style','$order','$txtkode_jenis','$txtperpatdate','$txtpersamdate',
		'$txtselpatdate','$txtselsamdate','$txtcekpatdate','$txtselcekpatdate','$txtkode_lampiran','$txtkode_acc','$user','N')";
	insert_log($sql,$user);
	/*if ($id_so!="" and $pro=="Copy")
	{	$id_so_new=flookup("id","request_sample_dev","so_no='$txtkpno'");
		$sql="insert into so_det_dev (id_so,dest,color,size,qty,unit,sku,barcode,notes) 
			select '$id_so_new',dest,color,size,qty,unit,sku,barcode,notes 
			from so_det_dev where id_so='$id_so'";
		insert_log($sql,$user);
	}*/
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	$id=flookup("id","request_sample_dev","no_req='$txtkpno'");
	echo "<script>
		 window.location.href='../marketting/?mod=$mod&id=$id';
	</script>";
}
else
{		
$sql = "update request_sample_dev set req_date='$txtdate',
			id_buyer='$txtid_buyer',id_item='$txtid_style',xorder='$order',id_jenis='$txtkode_jenis',date_perselpat='$txtperpatdate',
			date_perselsam='$txtpersamdate',date_selpat='$txtselpatdate',date_selsam='$txtselsamdate',date_cekpat='$txtcekpatdate',date_selcekpat='$txtselcekpatdate',id_lampiran='$txtkode_lampiran',id_acc='$txtkode_acc'
			where id='$id_so'";
		insert_log($sql,$user);
		$_SESSION['msg'] = 'Data Berhasil Dirubah';
		$id=flookup("id","request_sample_dev","no_req='$txtkpno'");
	echo "<script>
		 window.location.href='../marketting/?mod=$mod&id=$id';
	</script>";
}
?>