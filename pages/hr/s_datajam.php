<?php
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$mode="";

$txtidhari      = nb($_POST['txtidhari']);
$txthari        = nb($_POST['txthari']);
$txtjabatan     = nb($_POST['txtjabatan']);
$txtjam_masuk   = nb($_POST['txtjam_masuk']);
$txtjam_pulang  = nb($_POST['txtjam_pulang']);
$txtstatus      = nb($_POST['txtstatus']);
$txtcreatedate  = nb($_POST['txtcreatedate']);
$txtcreatedate  = date('d M Y', strtotime($txtcreatedate ));
$txtcreatedate  = date('Y-m-d', strtotime($txtcreatedate ));
   
// var_dump($txtidhari);
// die();

if ($txtidhari=="")
  { $txtidhari=nb($txthari); 
    $cek = flookup("count(*)","hr_masterjamkerja","idhari='$txtidhari'");
    // var_dump($cek);
    // die();
          	if ($cek=="0")
          	{	$sql = "insert into hr_masterjamkerja (hari,kode_pilihan,jam_masuk,jam_pulang,status,
          			createdate)
          			values ('$txthari','$txtjabatan','$txtjam_masuk','$txtjam_pulang','$txtstatus','$txtcreatedate')";
          		insert_log($sql,$user);
          		$_SESSION['msg'] = "Data Berhasil Disimpan pada HARI : $txthari ";
          		echo "<script>
          			 window.location.href='../hr/?mod=35DPL';
          		</script>";
            }
            else
             	{	
                echo "<script>
                 alert('Data sudah ada');
                 window.location.href='../hr/?mod=35DP';
                  </script>";
              }
    }
    else{
        $sql = "update hr_masterjamkerja set hari='$txthari',kode_pilihan='$txtjabatan',
                jam_masuk='$txtjam_masuk',jam_pulang='$txtjam_pulang',status='$txtstatus',
                createdate='$txtcreatedate' where idhari='$txtidhari'";
              insert_log($sql,$user);
            echo "<script>
            alert('Data berhasil dirubah');
            window.location.href='../hr/?mod=35DP';
            </script>";
             	}
            ?>
