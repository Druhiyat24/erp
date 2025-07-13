<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$txtnik = nb($_POST['txtnik']);
$txtpaydate = fd($_POST['txtpaydate']);
$txtamount = nb($_POST['txtamount']);
$txtketerangan = nb($_POST['txtketerangan']);
$cek = flookup("count(*)","hr_backpay","nik='$txtnik' and paydate='$txtpaydate'");
if ($cek=="0")
{ $sql = "insert into hr_backpay (nik,paydate,amount,keterangan)
    values ('$txtnik','$txtpaydate','$txtamount','$txtketerangan')";
  insert_log($sql,$user);
  $_SESSION['msg'] = 'Data Berhasil Disimpan';
  echo "<script>
     window.location.href='../hr/?mod=24L';
  </script>";
}
else if ($id_item!="")
{	$sql="update hr_backpay set amount='$txtamount',keterangan='$txtketerangan'
		where nik='$txtnik' and paydate='$txtpaydate'";
	insert_log($sql,$user);
  $_SESSION['msg'] = 'Data Berhasil Dirubah';
  echo "<script>
     window.location.href='../hr/?mod=24L';
  </script>";
}
else
{ $_SESSION['msg'] = 'XData Sudah Ada';
  echo "<script>
     window.location.href='../hr/?mod=24L';
  </script>";
}
?>