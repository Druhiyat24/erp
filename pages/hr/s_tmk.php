<?php
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_tmk = $_GET['id']; } else {$id_tmk = "";}

$kdtunjangan=nb($_POST['txtkodetunjangan']);
$name_employee=nb($_POST['txtnamaemp']);
$dept=nb($_POST['txtdept']);
$bagian=nb($_POST['txtbagian']);
$tahun=nb($_POST['txttahun']);
$tahun=date('d M Y', strtotime($tahun ));
$tahun=date('Y-m-d', strtotime($tahun ));
$tgl_skrg=nb($_POST['txttgl_skrg']);
// $diff= date_diff($tahun,$tgl_skrg);
// $today=date('Y-m-d');
// $hitung=$today-$tahun2;
// $tun = 0;
// if($hitung > 730) 
// {
// 	$tun = 2000;
// }
// else
// {
// 	$tun = 1000;
// }
$nilaitunjangan=nb($_POST['txtnilaitunjangan']);


if ($id_tmk!="")
{ $sql="update hr_mastertmk set departemen='$dept', name_employee='$name_employee', bagian='$bagian',
	tahun_masuk='$tahun', nilai_tunjangan='$nilaitunjangan'
    where kdtunjangan='$id_item'";
	insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Dirubah";
  echo "<script>window.location.href='../hr/?mod=36TL';</script>";
}
else
{ $sql="insert into hr_mastertmk (kdtunjangan,name_employee,departemen,bagian,tahun_masuk,tgl_skrg,nilai_tunjangan)
    values ('$kdtunjangan','$name_employee','$dept','$bagian','$tahun','$tgl_skrg','$nilaitunjangan')";
  insert_log($sql,$user);
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='../hr/?mod=36TL';</script>";
}
?>
