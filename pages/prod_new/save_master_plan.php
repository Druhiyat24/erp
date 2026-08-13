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

	if ($id) {
		// Check Total Output 
		$sql_cari_output_rft  		= mysql_query("select COUNT(*) as total_rft from output_rfts where master_plan_id = '$id' group by master_plan_id");
		$sql_cari_output_defect  	= mysql_query("select COUNT(*) as total_defect from output_defects where master_plan_id = '$id' group by master_plan_id");
		$sql_cari_output_reject  	= mysql_query("select COUNT(*) as total_reject from output_rejects where master_plan_id = '$id' group by master_plan_id");
		
		$row_cari_output_rft 		= mysql_fetch_array($sql_cari_output_rft);
		$row_cari_output_defect 	= mysql_fetch_array($sql_cari_output_defect);
		$row_cari_output_reject 	= mysql_fetch_array($sql_cari_output_reject);

		$s_total_rft = isset($row_cari_output_rft['total_rft']) ? $row_cari_output_rft['total_rft'] : 0;
		$s_total_defect = isset($row_cari_output_defect['total_defect']) ? $row_cari_output_defect['total_defect'] : 0;
		$s_total_reject = isset($row_cari_output_reject['total_reject']) ? $row_cari_output_reject['total_reject'] : 0;

		// Check Total Output Finishing
		$sql_cari_output_rft_finishing 		= mysql_query("select COUNT(*) as total_rft from output_rfts_packing where master_plan_id = '$id' group by master_plan_id");
		$sql_cari_output_defect_finishing 	= mysql_query("select COUNT(*) as total_defect from output_defects_packing where master_plan_id = '$id' group by master_plan_id");
		$sql_cari_output_reject_finishing 	= mysql_query("select COUNT(*) as total_reject from output_rejects_packing where master_plan_id = '$id' group by master_plan_id");

		$row_cari_output_rft_finishing 		= mysql_fetch_array($sql_cari_output_rft_finishing);
		$row_cari_output_defect_finishing 	= mysql_fetch_array($sql_cari_output_defect_finishing);
		$row_cari_output_reject_finishing 	= mysql_fetch_array($sql_cari_output_reject_finishing);

		$s_total_rft_finishing = isset($row_cari_output_rft_finishing['total_rft']) ? $row_cari_output_rft_finishing['total_rft'] : 0;
		$s_total_defect_finishing = isset($row_cari_output_defect_finishing['total_defect']) ? $row_cari_output_defect_finishing['total_defect'] : 0;
		$s_total_reject_finishing = isset($row_cari_output_reject_finishing['total_reject']) ? $row_cari_output_reject_finishing['total_reject'] : 0;

		// Check Total Output Packing
		$sql_cari_output_rft_packing  		= mysql_query("select COUNT(*) as total_rft from output_rfts_packing_po where master_plan_id = '$id' group by master_plan_id");
		$sql_cari_output_defect_packing  	= mysql_query("select COUNT(*) as total_defect from output_defects_packing_po where master_plan_id = '$id' group by master_plan_id");
		$sql_cari_output_reject_packing  	= mysql_query("select COUNT(*) as total_reject from output_rejects_packing_po where master_plan_id = '$id' group by master_plan_id");

		$row_cari_output_rft_packing 		= mysql_fetch_array($sql_cari_output_rft_packing);
		$row_cari_output_defect_packing 	= mysql_fetch_array($sql_cari_output_defect_packing);
		$row_cari_output_reject_packing 	= mysql_fetch_array($sql_cari_output_reject_packing);

		$s_total_rft_packing = isset($row_cari_output_rft_packing['total_rft']) ? $row_cari_output_rft_packing['total_rft'] : 0;
		$s_total_defect_packing = isset($row_cari_output_defect_packing['total_defect']) ? $row_cari_output_defect_packing['total_defect'] : 0;
		$s_total_reject_packing = isset($row_cari_output_reject_packing['total_reject']) ? $row_cari_output_reject_packing['total_reject'] : 0;

		if ($s_total_rft + $s_total_defect + $s_total_reject + $s_total_rft_finishing + $s_total_defect_finishing + $s_total_reject_finishing + $s_total_rft_packing + $s_total_defect_packing + $s_total_reject_packing  > 0) {
			// If Master Plan has Output
			$_SESSION['msg'] = "X Master Plan Sudah Memiliki Output.";
		} else {
			// If Master Plan has no Output
			$sql = "update master_plan set cancel = case when cancel = 'Y' then'N' else 'Y' end
			where id = '$id'";
			insert_log($sql, $user); {
				$_SESSION['msg'] = "Data Berhasil Diubah.";
			}
		}
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
	$txt_jam_kerja_awal	= $_POST['txt_jam_kerja_awal'];	

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
	smv = '$txtsmv', man_power = '$txtmpwr', plan_target = '$txt_plan_target', target_effy = '$txt_target_eff' $gambar, set_target = '$txtsettarget', 
	jam_kerja_awal = '$txt_jam_kerja_awal'
	where id = '$id'";
	insert_log($sql, $user); {
		$_SESSION['msg'] = "Data Berhasil Di rubah";
	}
	echo "<script>window.location.href='../prod_new/?mod=master_plan_edit&id=$id';</script>";
}
