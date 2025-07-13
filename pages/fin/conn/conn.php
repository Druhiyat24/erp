<?php
include "parser-php-version.php";

$servername = "10.10.5.12";
$user_name = "root";
$password = "ERP@S19n4lB1t";
$db = "signalbit_erp";

$server = "10.10.5.12";
$user = "root";
$pass = "ERP@S19n4lB1t";
$database = "signalbit_erp";

$server_hris = "10.10.5.111";
$user_hris = "root";
$pass_hris = "95*76s^SAl8a";
$database_hris = "hris";

$conn1 = mysqli_connect($servername, $user_name, $password, $db);
if (!$conn1) {
    die('Could not connect: ' . mysqli_error());
}
$db_selected1 = mysqli_select_db($conn1,$db);
if (!$db_selected1) {
    die ('Can\'t use signalbit_erp : ' . mysqli_error());
}

$conn2 = mysqli_connect($server, $user, $pass, $database);
if (!$conn2) {
    die('Could not connect: ' . mysqli_error());
}
$db_selected2 = mysqli_select_db($conn2,$database);
if (!$db_selected2) {
    die ('Can\'t use sbv2 : ' . mysqli_error());
}

// $conn3 = mysqli_connect($server_hris, $user_hris, $pass_hris, $database_hris);
// if (!$conn3) {
//     die('Could not connect: ' . mysqli_error());
// }
// $db_selected3 = mysqli_select_db($conn3,$database_hris);
// if (!$db_selected3) {
//     die ('Can\'t use hris : ' . mysqli_error());
// }

// error_reporting(0);
// $host = "10.10.5.2"; #host for your database
// $user = "root"; #username database
// $pswd = "ERP@S19n4lB1t"; #password database
// $db = "signalbit_erp"; #nama database

// $server_hris = "10.10.5.111";
// $user_hris = "root";
// $pass_hris = "toor";
// $database_hris = "hris_new";


// $conn1 = mysql_connect($host, $user, $pswd);
// $conn3 = mysql_connect($server_hris, $user_hris, $pass_hris);
// $con_new = new mysqli($host, $user, $pswd, $db);
// $conn_li = mysqli_connect($host, $user, $pswd, $db);


// if (!$conn1){ #jika koneksi gagal maka munculkan pesan error
// echo "Koneksi Gagal Karena ".mysql_error();
// }
// else{ #kebalikannya (jika koneksi berhasil maka lakukan pemilhan database)
// mysql_select_db($db);
// mysql_select_db($database_hris);
// date_default_timezone_set("Asia/Jakarta"); #untuk settingan default time zone (zona waktu yang di pakai)
// }
?>