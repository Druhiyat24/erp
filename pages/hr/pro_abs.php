<?php
include "../forms/fungsi.php";
include "../../include/conn.php";

session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

include "f_calc_jk.php";

$nm_company=flookup("company","mastercompany","company!=''");

$user = $_SESSION['username']; #mendapatkan data id  dari method get
$sesi = $_SESSION['sesi'];;

$nama_file = $_FILES['txtfile']['name'];
$ukuran_file = $_FILES['txtfile']['size'];
$tipe_file = $_FILES['txtfile']['type'];
$tmp_file = $_FILES['txtfile']['tmp_name'];
 
$path = "upload_files/".$nama_file;
move_uploaded_file($tmp_file, $path);

$txt_file    = file_get_contents($path);
$rows        = explode("\n", $txt_file);
array_shift($rows);
$i=1;
foreach($rows as $row => $data)
{	if ($data!="")
	{ $row_data = explode('|', $data);
		$id_absen = nb($row_data['0']);
    if ($id_absen!="")
    {	$nik = $id_absen;
    	$tanggal = nb($row_data['1']);
	    $status = nb($row_data['3']);
	    $jam = substr(nb($row_data['2']),0,5);
	    $loc_id = "";
	    $tgl_down = date('Y-m-d H-i-s');
  		$sql="insert into hr_upload_abs (username,sesi,id_absen,nik,tanggal_absen,status,location_id,jam,tgl_download) 
	    	values ('$user','$sesi','$id_absen','$nik','$tanggal','$status','$loc_id','$jam','$tgl_down')";
	    insert_log($sql,$user);
		}
	}
}
# CEK JAM MASUK
$sql="select * from hr_upload_abs where status='IN' AND USERNAME='$user'
	and SESI='$sesi' order by id_absen,tanggal_absen";
$result=mysql_query($sql);
while($rs = mysql_fetch_array($result))
{	$nik=$rs['ID_ABSEN'];
	$tgl=$rs['TANGGAL_ABSEN'];
	$status=$rs['STATUS'];
	$jam=$rs['jam'];
	$cek=flookup("empno","hr_timecard","empno='$nik' and workdate='$tgl'");
	if ($cek=="")
	{	$sqlin="insert into hr_timecard (empno,workdate,timein) 
			values ('$nik','$tgl','$jam')";
		insert_log($sqlin,$user);
	}
}
# CEK JAM PULANG
$sql="select * from hr_upload_abs where status='OUT' AND USERNAME='$user'
	and SESI='$sesi' order by id_absen,tanggal_absen";
$result=mysql_query($sql);
while($rs = mysql_fetch_array($result))
{	$nik=$rs['ID_ABSEN'];
	$tgl=$rs['TANGGAL_ABSEN'];
	$status=$rs['STATUS'];
	$jam=$rs['jam'];
	$cek=flookup("empno","hr_timecard","empno='$nik' and workdate='$tgl'");
	if ($cek=="")
	{	$sqlout="insert into hr_timecard (empno,workdate,timeout) 
			values ('$nik','$tgl','$jam')";
		insert_log($sqlout,$user);
	}
	else
	{	$sqlout="update hr_timecard set timeout='$jam' where empno='$nik' and 
			workdate='$tgl'";
		insert_log($sqlout,$user);
	}
}
$sql="update hr_timecard set timeout=null where timein=timeout";
insert_log($sql,$user);
# CEK JAM KERJA
$sql="select a.empno nik,a.workdate,dayname(a.workdate) hari,a.timein,a.timeout 
	from hr_timecard a inner join hr_upload_abs s on a.empno=s.id_absen 
	and a.workdate=s.tanggal_absen where USERNAME='$user'
	and SESI='$sesi' group by id_absen,tanggal_absen";
$result=mysql_query($sql);
while($rs = mysql_fetch_array($result))
{	calc_jk($rs['nik'],$rs['workdate'],$rs['hari'],
		$rs['timein'],$rs['timeout'],$user);	
}
$sql = "insert into hr_upload_abs_hist select * from hr_upload_abs where USERNAME='$user'
	and SESI='$sesi'";
insert_log($sql,$user);
$sql = "delete from hr_upload_abs where USERNAME='$user'
	and SESI='$sesi'";
insert_log($sql,$user);
$_SESSION['msg'] = "Data Berhasil Diproses";
echo "<script>
		window.location.href='../hr/?mod=7';
	</script>";
?>
