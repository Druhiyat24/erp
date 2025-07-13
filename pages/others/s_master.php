<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$goods_code_only=flookup("goods_code_only","mastercompany","company!=''");
$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id=$_GET['id'];} else {$id="";}

$txtmattype = nb($_POST['txtmattype']);
$txtgoods_code = nb($_POST['txtgoods_code']);
$txtitemdesc = nb($_POST['txtitemdesc']);
$txtcolor = nb($_POST['txtcolor']);
$txtsize = nb($_POST['txtsize']);
$txtjenisitem = nb($_POST['txtjenisitem']);
$txtpersediaan = nb($_POST['txtpersediaan']);
if(isset($_POST['txtjenismut'])) { $txtjenismut=$_POST['txtjenismut']; } else { $txtjenismut=""; }
if (isset($_FILES['txtfile']))
{	$nama_file = $_FILES['txtfile']['name'];
	$tmp_file = $_FILES['txtfile']['tmp_name'];
	$path = "upload_files/".$nama_file;
	move_uploaded_file($tmp_file, $path);
}
else
{ $nama_file=""; }

if ($id=="")
{	if($goods_code_only=="Y")
	{	$cek = flookup("count(*)","masteritem","mattype='$txtmattype' 
			and goods_code='$txtgoods_code' ");
	}
	else
	{	$cek = flookup("count(*)","masteritem","mattype='$txtmattype' and goods_code='$txtgoods_code' 
			and itemdesc='$txtitemdesc' and color='$txtcolor' and size='$txtsize'");
	}
}
else
{	if($goods_code_only=="Y")
	{	$cek = flookup("count(*)","masteritem","mattype='$txtmattype' 
			and goods_code='$txtgoods_code' and id_item!='$id'");
	}
	else
	{	$cek = flookup("count(*)","masteritem","mattype='$txtmattype' and goods_code='$txtgoods_code' 
			and itemdesc='$txtitemdesc' and color='$txtcolor' and size='$txtsize'
			and id_item!='$id'");
	}
}
if ($cek=="0" and $id=="")
{	$sql = "insert into masteritem (n_code_category,matclass,tipe_item,tipe_mut,mattype,goods_code,itemdesc,color,size,file_gambar)
		values ('$txtpersediaan','-','$txtjenisitem','$txtjenismut','$txtmattype','$txtgoods_code','$txtitemdesc','$txtcolor','$txtsize','$nama_file')";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	echo "<script>
		 window.location.href='../others/?mod=2L';
	</script>";
}
else if ($cek=="0" and $id!="")
{	$sql = "update masteritem set n_code_category='$txtpersediaan',tipe_item='$txtjenisitem',tipe_mut='$txtjenismut',goods_code='$txtgoods_code',
		itemdesc='$txtitemdesc',color='$txtcolor',
		size='$txtsize',file_gambar='$nama_file' where id_item='$id'";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Dirubah';
	echo "<script>
		 window.location.href='../others/?mod=2L';
	</script>";
}
else if ($cek!="0" and $id!="")
{	$sql = "update masteritem set tipe_item='$txtjenisitem',color='$txtcolor',
		size='$txtsize',file_gambar='$nama_file' where id_item='$id'";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Dirubah';
	echo "<script>
		 window.location.href='../others/?mod=2L';
	</script>";
}
else
{	$_SESSION['msg'] = 'XData Sudah Ada';
	echo "<script>
		window.location.href='../others/?mod=2L';
	</script>";
}
?>