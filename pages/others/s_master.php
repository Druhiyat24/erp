<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

// ===== FUNGSI HAPUS GAMBAR (DELETE IMAGE) =====
if (isset($_GET['act']) && $_GET['act'] == 'del_img' && isset($_GET['id'])) {
    $id  = $_GET['id'];
    $mod = isset($_GET['mod']) ? $_GET['mod'] : '2';
    // Hapus file fisik jika ada
    $nm_file = flookup("file_gambar", "masteritem", "id_item='$id'");
    if (!empty($nm_file)) {
        $path_file = "upload_files/".$nm_file;
        if (file_exists($path_file)) { @unlink($path_file); }
    }
    $sql = "update masteritem set file_gambar='' where id_item='$id'";
    insert_log($sql, $_SESSION['username']);
    $_SESSION['msg'] = 'Gambar Berhasil Dihapus';
    echo "<script>window.location.href='../others/?mod=2L';</script>";
    exit;
}

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
$txtpersediaan = isset($_POST['txtpersediaan']) ? nb($_POST['txtpersediaan']) : '';
if(isset($_POST['txtjenismut'])) { $txtjenismut=$_POST['txtjenismut']; } else { $txtjenismut=""; }

if ($txtpersediaan == '')
{	$_SESSION['msg'] = 'Mapping Persediaan Tidak Boleh Kosong';
	echo "<script>
		 window.location.href='../others/?mod=2&id=$id';
	</script>";
	exit;
}
$coa_production     = isset($_POST['txt_coa_production'])     ? nb($_POST['txt_coa_production'])     : '';
$coa_sup_production = isset($_POST['txt_coa_sup_production']) ? nb($_POST['txt_coa_sup_production']) : '';
$coa_sup_gen_adm    = isset($_POST['txt_coa_sup_gen_adm'])    ? nb($_POST['txt_coa_sup_gen_adm'])    : '';
$coa_sup_selling    = isset($_POST['txt_coa_sup_selling'])    ? nb($_POST['txt_coa_sup_selling'])    : '';

// COA hanya wajib untuk Mapping Persediaan ATK (n_id=1) dan UMUM (n_id=2), selain itu default strip
if ($txtpersediaan != '1' && $txtpersediaan != '2') {
	if ($coa_production == '')     { $coa_production = '-'; }
	if ($coa_sup_production == '') { $coa_sup_production = '-'; }
	if ($coa_sup_gen_adm == '')    { $coa_sup_gen_adm = '-'; }
	if ($coa_sup_selling == '')    { $coa_sup_selling = '-'; }
}
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
{	$sql = "insert into masteritem (n_code_category,matclass,tipe_item,tipe_mut,mattype,goods_code,itemdesc,color,size,file_gambar,coa_production,coa_sup_production,coa_sup_gen_adm,coa_sup_selling)
		values ('$txtpersediaan','-','$txtjenisitem','$txtjenismut','$txtmattype','$txtgoods_code','$txtitemdesc','$txtcolor','$txtsize','$nama_file','$coa_production','$coa_sup_production','$coa_sup_gen_adm','$coa_sup_selling')";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	echo "<script>
		 window.location.href='../others/?mod=2L';
	</script>";
}
else if ($cek=="0" and $id!="")
{	$sql = "update masteritem set n_code_category='$txtpersediaan',tipe_item='$txtjenisitem',tipe_mut='$txtjenismut',goods_code='$txtgoods_code',
		itemdesc='$txtitemdesc',color='$txtcolor',size='$txtsize',file_gambar='$nama_file',
		coa_production='$coa_production',coa_sup_production='$coa_sup_production',
		coa_sup_gen_adm='$coa_sup_gen_adm',coa_sup_selling='$coa_sup_selling'
		where id_item='$id'";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Dirubah';
	echo "<script>
		 window.location.href='../others/?mod=2L';
	</script>";
}
else if ($cek!="0" and $id!="")
{	$sql = "update masteritem set tipe_item='$txtjenisitem',color='$txtcolor',size='$txtsize',file_gambar='$nama_file',
		coa_production='$coa_production',coa_sup_production='$coa_sup_production',
		coa_sup_gen_adm='$coa_sup_gen_adm',coa_sup_selling='$coa_sup_selling'
		where id_item='$id'";
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