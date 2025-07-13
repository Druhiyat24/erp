<?php
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) {
	header("location:../../index.php");
}

$user = $_SESSION['username'];
$sesi = $_SESSION['sesi'];
$mod = $_GET['mod'];

if ($mod == 'simpan') {
	$cbows				= nb($_POST['cbows']);
	$txtsmv				= nb($_POST['txtsmv']);
	$txtmpwr			= nb($_POST['txtmpwr']);
	$txtjamkerja		= nb($_POST['txtjamkerja']);
	$txtbuyer     		= nb($_POST['txtbuyer']);
	$cbocolor			= nb($_POST['cbocolor']);
	$txt_plan_target	= nb($_POST['txt_plan_target']);
	$txt_target_eff		= nb($_POST['txt_target_eff']);
	$txt_set_target		= nb($_POST['txt_set_target']);
	$dateinput			= date('Y-m-d H:i:s');	
	$tanggal			= fd($_POST['tgl_plan']);
	$jam_awal			= $_POST['txt_jam_kerja_awal'];
	$id_plan			= date('Ymd');

	if (isset($_FILES['txtfile'])) {
		$nama_file = $_FILES['txtfile']['name'];
		$tmp_file = $_FILES['txtfile']['tmp_name'];
		$path = "upload_files/" . $nama_file;
		move_uploaded_file($tmp_file, $path);
	} else {
		$nama_file = "";
	}


	// $cboline			= nb($_POST['cboline']);

	$usernameArray = $_POST['username_arr'];

	foreach ($usernameArray as $key_1 => $value_1) {

		$username = $usernameArray[$key_1];

		$sql = "insert into master_plan (id_plan,id,tgl_plan,sewing_line,id_ws,color,create_by,
				smv,jam_kerja,man_power,plan_target,target_effy,set_target,jam_kerja_awal,tgl_input,cancel,gambar)
				values ('$id_plan','','$tanggal','$username','$cbows','$cbocolor',
				'$user','$txtsmv','$txtjamkerja','$txtmpwr','$txt_plan_target','$txt_target_eff','$txt_set_target','$jam_awal',
				'$dateinput','N','$nama_file')";
		insert_log($sql, $user); {
			$_SESSION['msg'] = "Data Berhasil Disimpan";
		}
		echo "<script>window.location.href='../prod_new/?mod=master_plan_new';</script>";
	}
}

if ($mod == 'update_status') {

	$id 		= $_GET['id'];
	$sql_cari  	= mysql_query("select * from master_part where id = '$id'");
	$row_cari 	= mysql_fetch_array($sql_cari);
	$txt_nmpart = $row_cari['nama_part'];

	$dateinput		= date('Y-m-d H:i:s');


	$sql = "update master_plan set cancel = case when cancel = 'Y' then'N' else 'Y' end
	where id = '$id'";
	insert_log($sql, $user); {
		$_SESSION['msg'] = "Data Berhasil Di rubah, Nama Part : " . $txt_nmpart;
	}
	echo "<script>window.location.href='../prod_new/?mod=master_plan_h';</script>";
}

if ($mod == 'update') {

	$id 			= $_POST['txtid'];
	$txtsmv			= $_POST['txtsmv'];
	$txtmpwr		= $_POST['txtmpwr'];
	$txtjamkerja	= $_POST['txtjamkerja'];
	$txt_target_eff	= $_POST['txt_target_eff'];
	$txt_plan_target = $_POST['txt_plan_target'];
	$txtfile_baru 	= $_POST['txtfile_baru'];
	$txtsettarget	= $_POST['txt_set_target'];

	if (isset($_FILES['txtfile_baru'])) {
		$nama_file = $_FILES['txtfile_baru']['name'];
		$tmp_file = $_FILES['txtfile_baru']['tmp_name'];
		if ($nama_file != '') {
			$path = "upload_files/" . $nama_file;
			move_uploaded_file($tmp_file, $path);
			$gambar = ",gambar = '$nama_file'";
		} else {
			$gambar = "";
		}
	}

	$sql = "update master_plan set jam_kerja = '$txtjamkerja', 
	smv = '$txtsmv', man_power = '$txtmpwr', plan_target = '$txt_plan_target', target_effy = '$txt_target_eff' $gambar, set_target = '$txtsettarget'
	where id = '$id'";
	insert_log($sql, $user); {
		$_SESSION['msg'] = "Data Berhasil Di rubah";
	}
	echo "<script>window.location.href='../prod_new/?mod=master_plan_edit&id=$id';</script>";
}
