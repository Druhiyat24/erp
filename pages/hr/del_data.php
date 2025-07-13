<?php
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$mode="";
$pro=$_GET['pro'];
$id=$_GET['id'];

if ($pro=="Apply")
{	$sql="delete from data_pribadi where id_dp='$id'";
	insert_log($sql,$user);
	$_SESSION['msg']="Data Berhasil Dihapus";
	echo "<script>
		 window.location.href='../hr/?mod=29';
	</script>";
	exit;
}
if ($pro=="FPTK")
{	$sql="delete from form_tenaga_kerja where id_tk='$id'";
	insert_log($sql,$user);
	$_SESSION['msg']="Data Berhasil Dihapus";
	echo "<script>
		 window.location.href='../hr/?mod=29p';
	</script>";
	exit;
}
if ($pro=="Dok")
{	$sql="delete from form_dokumen where id_form_dk='$id'";
	insert_log($sql,$user);
	$_SESSION['msg']="Data Berhasil Dihapus";
	echo "<script>
		 window.location.href='../hr/?mod=29a';
	</script>";
	exit;
}
if ($pro=="Interview")
{	$sql="delete from form_interview where id_fi='$id'";
	insert_log($sql,$user);
	$_SESSION['msg']="Data Berhasil Dihapus";
	echo "<script>
		 window.location.href='../hr/?mod=29i';
	</script>";
	exit;
}
if ($pro=="MAbs")
{	$sql = "delete from hr_masterabsen where kodeabsen='$id'";
	insert_log($sql,$user);
	$_SESSION['msg']="Data Berhasil Dihapus";
	echo "<script>
		 window.location.href='../hr/?mod=10';
	</script>";
}
if ($pro=="SPL")
{	$cri=split(":",$id);
	$nik=$cri[0];
	$tgl=$cri[1];
	$sql = "delete from hr_spl where nik='$nik' and tanggal='$tgl'";
	insert_log($sql,$user);
	$_SESSION['msg']="Data Berhasil Dihapus";
	echo "<script>
		 window.location.href='../hr/?mod=18L';
	</script>";
}
if ($pro=="ManAbs")
{	$cri=split(":",$id);
	$nik=$cri[0];
	$tgl=$cri[1];
	$sql = "delete from hr_timecard where empno='$nik' and workdate='$tgl'";
	insert_log($sql,$user);
	$_SESSION['msg']="Data Berhasil Dihapus";
	echo "<script>
		 window.location.href='../hr/?mod=13L';
	</script>";
}
if ($pro=="Ded" or $pro=="BPay")
{	$cri=split(":",$id);
	$nik=$cri[0];
	$paydate=$cri[1];
	$sql = "delete from hr_backpay where nik='$nik' and paydate='$paydate'";
	insert_log($sql,$user);
	$_SESSION['msg']="Data Berhasil Dihapus";
	echo "<script>
		 window.location.href='../hr/?mod=24L';
	</script>";
}
if ($pro=="Ijin")
{	$cri=split(":",$id);
	$nik=$cri[0];
	$tgl=$cri[1];
	$sql = "delete from hr_ijinkaryawan where nik='$nik' and tanggal='$tgl'";
	insert_log($sql,$user);
	$_SESSION['msg']="Data Berhasil Dihapus";
	echo "<script>
		 window.location.href='../hr/?mod=20';
	</script>";
}
if ($pro=="EmpFam")
{	$sql = "delete from hr_employeefamily where nik='$id'";
	insert_log($sql,$user);
	$_SESSION['msg']="Data Berhasil Dihapus";
	echo "<script>
		 window.location.href='../hr/?mod=5L';
	</script>";
}
if ($pro=="MHol")
{	$sql = "delete from hr_masterholiday where holiday_date='$id'";
	insert_log($sql,$user);
	$_SESSION['msg']="Data Berhasil Dihapus";
	echo "<script>
		 window.location.href='../hr/?mod=11';
	</script>";
}
if ($pro=="MPTKP")
{	$sql = "delete from hr_masterptkp where ptkpcode='$id'";
	insert_log($sql,$user);
	$_SESSION['msg']="Data Berhasil Dihapus";
	echo "<script>
		 window.location.href='../hr/?mod=6';
	</script>";
}
if ($pro=="MLok")
{	$sql = "delete from masterpilihan where nama_pilihan='$id' and kode_pilihan='Lokasi'";
	insert_log($sql,$user);
	$_SESSION['msg']="Data Berhasil Dihapus";
	echo "<script>
		 window.location.href='../hr/?mod=3';
	</script>";
}
if ($pro=="MDep")
{	$sql = "delete from masterpilihan where nama_pilihan='$id' and kode_pilihan='Dept'";
	insert_log($sql,$user);
	$_SESSION['msg']="Data Berhasil Dihapus";
	echo "<script>
		 window.location.href='../hr/?mod=16';
	</script>";
}
if ($pro=="MLin")
{	$sql = "delete from masterpilihan where nama_pilihan='$id' and kode_pilihan='Line'";
	insert_log($sql,$user);
	$_SESSION['msg']="Data Berhasil Dihapus";
	echo "<script>
		 window.location.href='../hr/?mod=17';
	</script>";
}
if ($pro=="MJab")
{	$sql = "delete from masterpilihan where nama_pilihan='$id' and kode_pilihan='Jabatan'";
	insert_log($sql,$user);
	$_SESSION['msg']="Data Berhasil Dihapus";
	echo "<script>
		 window.location.href='../hr/?mod=4';
	</script>";
}
if ($pro=="MBag")
{	$sql = "delete from masterpilihan where nama_pilihan='$id' and kode_pilihan='Bagian'";
	insert_log($sql,$user);
	$_SESSION['msg']="Data Berhasil Dihapus";
	echo "<script>
		 window.location.href='../hr/?mod=19';
	</script>";
}
?>