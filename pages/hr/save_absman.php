<?php
include '../../include/conn.php';
include '../forms/fungsi.php';
include "f_calc_jk.php";
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

if (isset($_GET['mode'])) { $mode=$_GET['mode']; } else { $mode=""; }
$txtnik = nb($_POST['txtnik']);
$txttanggal = fd($_POST['txttanggal']);
$txtTimeIn = nb($_POST['txtTimeIn']);
$txtTimeOut = nb($_POST['txtTimeOut']);

if ($mode=="dept")
{	$que="select a.* from hr_masteremployee a left join hr_timecard s on 
		a.nik=s.empno and '$txttanggal'=s.workdate where 
		department='$txtnik' and 
		(workdate is null or timeout is null or timein is null) and 
		(selesai_kerja is null or selesai_kerja='0000-00-00')";
	#echo $que;
	$result=mysql_query($que);
	while($data = mysql_fetch_array($result))
	{	$txtnik=$data['nik'];
		$cek = flookup("count(*)","hr_timecard","empno='$txtnik' and workdate='$txttanggal'");
		if ($cek=="0")
		{	$sql = "insert into hr_timecard (empno,workdate,TimeIn,TimeOut)
				values ('$txtnik','$txttanggal','$txtTimeIn','$txtTimeOut')";
			insert_log($sql,$user);
			calc_jk($txtnik,$txttanggal,date('l',strtotime($txttanggal)),
				$txtTimeIn,$txtTimeOut,$user);
		}
		else
		{	$sql = "update hr_timecard set TimeIn='$txtTimeIn' 
				where empno='$txtnik' and workdate='$txttanggal' and TimeIn is null ";
			insert_log($sql,$user);
			$sql = "update hr_timecard set TimeOut='$txtTimeOut'
				where empno='$txtnik' and workdate='$txttanggal' and TimeOut is null ";
			insert_log($sql,$user);
			calc_jk($txtnik,$txttanggal,date('l',strtotime($txttanggal)),
				$txtTimeIn,$txtTimeOut,$user);
		}
	}
	$_SESSION['msg'] = "Data Berhasil Disimpan";
	echo "<script>
		 window.location.href='../hr/?mod=13L&mode=$mode';
	</script>";
}
else
{	$cek = flookup("count(*)","hr_timecard","empno='$txtnik' and workdate='$txttanggal'");
	if ($cek=="0")
	{	$sql = "insert into hr_timecard (empno,workdate,TimeIn,TimeOut)
			values ('$txtnik','$txttanggal','$txtTimeIn','$txtTimeOut')";
		insert_log($sql,$user);
		calc_jk($txtnik,$txttanggal,date('l',strtotime($txttanggal)),
			$txtTimeIn,$txtTimeOut,$user);
		$_SESSION['msg'] = "Data Berhasil Disimpan";
		echo "<script>
			 window.location.href='../hr/?mod=13L';
		</script>";
	}
	else
	{	$sql = "update hr_timecard set TimeIn='$txtTimeIn',TimeOut='$txtTimeOut'
			where empno='$txtnik' and workdate='$txttanggal'";
		insert_log($sql,$user);
		calc_jk($txtnik,$txttanggal,date('l',strtotime($txttanggal)),
			$txtTimeIn,$txtTimeOut,$user);
		$_SESSION['msg'] = "Data Berhasil Dirubah";
		echo "<script>
			 window.location.href='../hr/?mod=13L';
		</script>";
	}
}
?>