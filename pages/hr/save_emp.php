<?php
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$mode="";

$txtnik = nb($_POST['txtnik']);
$txtnama = nb($_POST['txtnama']);
$txtjenis_kelamin = nb($_POST['txtjenis_kelamin']);
$txttgl_lahir = nb($_POST['txttgl_lahir']);
if ($txttgl_lahir!="") { $txttgl_lahir=fd($txttgl_lahir); }
$txtdivisi_kerja = nb($_POST['txtdivisi_kerja']);
$txtserikat = nb($_POST['txtserikat']);
$txtjabatan = nb($_POST['txtjabatan']);
$txtdept = nb($_POST['txtdept']);
$txtbagian = nb($_POST['txtbagian']);
if (isset($_POST['txtline'])) {$txtline = nb($_POST['txtline']);} else {$txtline = "";}
$txtmulai_kerja = nb($_POST['txtmulai_kerja']);
if ($txtmulai_kerja!="") { $txtmulai_kerja=fd($txtmulai_kerja); }
$txtselesai_kontrak1 = nb($_POST['txtselesai_kontrak1']);
if ($txtselesai_kontrak1!="") { $txtselesai_kontrak1=fd($txtselesai_kontrak1); }
$txtmulai_kontrak2 = nb($_POST['txtmulai_kontrak2']);
if ($txtmulai_kontrak2!="") { $txtmulai_kontrak2=fd($txtmulai_kontrak2); }
$txtselesai_kontrak2 = nb($_POST['txtselesai_kontrak2']);
if ($txtselesai_kontrak2!="") { $txtselesai_kontrak2=fd($txtselesai_kontrak2); }
$txttgl_permanent = nb($_POST['txttgl_permanent']);
if ($txttgl_permanent!="") { $txttgl_permanent=fd($txttgl_permanent); }
$txtagama = nb($_POST['txtagama']);
$txtalamat_karyawan = nb($_POST['txtalamat_karyawan']);
$txtno_npwp = nb($_POST['txtno_npwp']);
$txtid_absen = nb($_POST['txtid_absen']);
if (isset($_POST['txtjenis_jamkes'])) 
	{ $txtjenis_jamkes = nb($_POST['txtjenis_jamkes']); } 
else 
	{ $txtjenis_jamkes = ""; }
$txtno_ktp_tk = nb($_POST['txtno_ktp_tk']);
$txtno_bpjs_kes_tk = nb($_POST['txtno_bpjs_kes_tk']);
$txtno_bpjs_tk = nb($_POST['txtno_bpjs_tk']);
if ($txtnik=="") 
{ $txtnik=get_nik("NAG",fd($txtmulai_kerja)); 
  $cek = flookup("count(*)","hr_masteremployee","nik='$txtnik'");
	if ($cek=="0")
	{	$sql = "insert into hr_masteremployee (nik,nama,jenis_kelamin,tgl_lahir,divisi_kerja,lokasi_kerja,
			jabatan,department,bagian,line,mulai_kerja,
			selesai_kotrak1,mulai_kontrak2,selesai_kontrak2,tgl_permanent,agama,alamat_karyawan,no_npwp,jenis_jamkes,no_ktp_tk,no_bpjs_kes_tk,
			no_bpjs_tk,id_absen,base_lokasi) 
			values ('$txtnik','$txtnama','$txtjenis_kelamin','$txttgl_lahir','$txtdivisi_kerja','$txtserikat',
			'$txtjabatan','$txtdept','$txtbagian','$txtline',
			'$txtmulai_kerja','$txtselesai_kontrak1','$txtmulai_kontrak2','$txtselesai_kontrak2','$txttgl_permanent','$txtagama','$txtalamat_karyawan',
			'$txtno_npwp','$txtjenis_jamkes','$txtno_ktp_tk','$txtno_bpjs_kes_tk','$txtno_bpjs_tk','$txtid_absen',
			'$txtdivisi_kerja')";
		insert_log($sql,$user);
		$_SESSION['msg'] = "Data Berhasil Disimpan Dengan NIK : $txtnik ";
		echo "<script>
			 window.location.href='../hr/?mod=2L';
		</script>";
	}
	else
	{	echo "<script>
			 alert('Data sudah ada');
			 window.location.href='../hr/?mod=2L';
		</script>";
	}
}
else
{	$sql = "update hr_masteremployee set nama='$txtnama',jenis_kelamin='$txtjenis_kelamin',
		tgl_lahir='$txttgl_lahir',divisi_kerja='$txtdivisi_kerja',lokasi_kerja='$txtserikat',
		jabatan='$txtjabatan',mulai_kerja='$txtmulai_kerja',selesai_kotrak1='$txtselesai_kontrak1',
		selesai_kontrak2='$txtselesai_kontrak2',tgl_permanent='$txttgl_permanent',
		agama='$txtagama',alamat_karyawan='$txtalamat_karyawan',no_npwp='$txtno_npwp',
		jenis_jamkes='$txtjenis_jamkes',no_ktp_tk='$txtno_ktp_tk',
		no_bpjs_kes_tk='$txtno_bpjs_kes_tk',no_bpjs_tk='$txtno_bpjs_tk',id_absen='$txtid_absen',
		bagian='$txtbagian',department='$txtdept',line='$txtline',
		base_lokasi='$txtdivisi_kerja' where nik='$txtnik'";
	insert_log($sql,$user);
	echo "<script>
		alert('Data berhasil dirubah');
		window.location.href='../hr/?mod=2L';
	</script>";
}
?>