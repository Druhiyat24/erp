<html>
<head> 
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>HR | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
</head>
<?php
include('include/conn.php');
session_start();
//tangkap data dari form login
if (isset($_POST['user'])) { $username = $_POST['user']; } else { $username = ""; }
if (isset($_POST['pass'])) { $pass = $_POST['pass']; } else { $pass = ""; }
$bahasa=$_POST['txtbahasa'];
//untuk mencegah sql injection
//kita gunakan mysql_real_escape_string
$user = mysql_real_escape_string($username);
$pwd=$pass;

$q = mysql_query("select * from userpassword where username='$user' and password='$pwd' and aktif='Y'");
$result = mysql_num_rows($q);
$data = mysql_fetch_array($q);
if ($result == 1) {
	$_SESSION['username']= $data['username'];
	$_SESSION['FullName']= $data['FullName'];
	$random = substr(md5(rand()), 0, 20);
  $_SESSION['sesi']= $random;
  $_SESSION['bahasa']= $bahasa;
	$_SESSION['first']= "Y";
  #header("location:pages/forms/?mod=1");
  if (!empty($_SERVER["HTTP_CLIENT_IP"]))
  { $ip = $_SERVER["HTTP_CLIENT_IP"]; }
  elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
  { $ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; }
  else
  { $ip = $_SERVER["REMOTE_ADDR"]; }
  $host_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);
  $Trans_Date = date("Y-m-d H:i:s");
  $Trans_Host = $_SESSION['username'].'/'.$ip.'/'.$host_name;
  $que = "insert into act_hist (Trans_Date,Trans_Desc,Trans_Host) values 
    ('$Trans_Date','Berhasil Login','$Trans_Host')";
  $strsql=mysql_query($que);
  if (!$strsql) { die($que. mysql_error()); }
  if($data['Groupp']=="SECURITY")
  { header("location:pages/sec/?mod=1L"); }
  else
  { header("location:pages/"); }
} 
else 
{ echo "<script>alert('Gagal login: Cek username, password!');history.go(-1);</script>";
}

	
?>