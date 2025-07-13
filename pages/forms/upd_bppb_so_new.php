<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$mod=$_GET['mod'];

if ($mod == 'update')
{
   $id_bppb = $_GET['id_bppb'];
 
   $queryheader = mysql_query("SELECT * from bppb where bppbno = '$id_bppb' limit 1");
   $databppb    = mysql_fetch_array($queryheader);
   $bppbno_int  =$databppb['bppbno_int'];

   $txtgrade = nb($_POST['txtgrade']);
   $txtbppbdate = fd($_POST['txtbppbdate']);
   $txtid_supplier = nb($_POST['txtid_supplier']);
   $txtbuyer = nb($_POST['txtbuyer']);
   $txtinvno = nb($_POST['txtinvno']);
   $txtjenis_dok = nb($_POST['txtjenis_dok']);

   $sql	="update bppb set grade = '$txtgrade', bppbdate = '$txtbppbdate', id_buyer = '$txtbuyer', 
   	id_supplier = '$txtid_supplier', invno = '$txtinvno', jenis_dok = '$txtjenis_dok'
    where bppbno = '$id_bppb'";
   insert_log($sql,$user);
   $_SESSION['msg'] = 'Data Berhasil Disimpan. Nomor BKB : '.$bppbno_int;
   echo "<script>
	window.location.href='../forms/?mod=321ed&mode=FG&noid=$id_bppb';
   </script>";
}




?>