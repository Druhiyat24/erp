<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$trx=$_GET['trx'];
$id=$_GET['id'];
$app1_date = date("Y-m-d H:i:s");
$app1_by = $user;
if ($trx=="CS")
{	$sql = "update act_development set app1='A',app1_by='$app1_by',app1_date='$app1_date'
		where id='$id'";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	echo "<script>
		 window.location.href='../appr/?mod=$mod';
	</script>";
}
else if ($trx=="JO")
{	$sql = "update jo_dev set app='A',app_by='$app1_by',app_date='$app1_date'
		where id='$id'";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	echo "<script>
		 window.location.href='../appr/?mod=$mod';
	</script>";
}
else if ($trx=="PO")
{	$sql = "update po_header_dev set app='A',app_by='$app1_by',app_date='$app1_date'
		where id='$id'";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	echo "<script>
		 window.location.href='../appr/?mod=$mod';
	</script>";
}
else if ($trx=="RN")
{	$sql = "update reqnon_header_dev set app='A',app_date='$app1_date'
		where id='$id' and app_by='$user'";
	insert_log($sql,$user);
	$sql = "update reqnon_header_dev set app2='A',app_date2='$app1_date'
		where id='$id' and app_by2='$user'";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	echo "<script>
		 window.location.href='../appr/?mod=$mod';
	</script>";
}
else if ($trx=="PTK")
{	$cek=flookup("id_tk","form_tenaga_kerja","setuju1='$app1_by' and setuju1_app='W'");
	if ($cek!="")
	{	$nm_fld="setuju1"; }
	else
	{	$cek=flookup("id_tk","form_tenaga_kerja","setuju2='$app1_by' and setuju2_app='W'");	
		if ($cek!="")
		{	$nm_fld="setuju2"; }
		else
		{	$cek=flookup("id_tk","form_tenaga_kerja","setuju3='$app1_by' and setuju3_app='W'");
			if ($cek!="")
			{	$nm_fld="setuju3"; }
			else
			{	$cek=flookup("id_tk","form_tenaga_kerja","ketahui='$app1_by' and ketahui_app='W'");
				if ($cek!="")
				{	$nm_fld="ketahui"; }	
			}	
		}
	}
	if ($nm_fld<>"")
	{	$nm_fld2=$nm_fld."_app";
		$nm_fld3=$nm_fld."_date";
		$sql = "update form_tenaga_kerja set $nm_fld2='A',$nm_fld3='$app1_date' 
			where id_tk='$id' and $nm_fld='$app1_by'";
		insert_log($sql,$user);
		$_SESSION['msg'] = 'Data Berhasil Disimpan';
	}
	else
	{	$_SESSION['msg'] = 'XTidak Ada Data';	}
	echo "<script>
		 window.location.href='../appr/?mod=$mod';
	</script>";
}
?>