<?php
include "parser-php-version.php";

$servername = "10.10.5.2";
$user_name = "root";
$password = "ERP@S19n4lB1t";
$db = "signalbit_erp";

$server = "10.10.5.2";
$user = "root";
$pass = "ERP@S19n4lB1t";
$database = "signalbit_erp";

$server_hris = "10.10.5.111";
$user_hris = "root";
$pass_hris = "toor";
$database_hris = "hris_new";

$conn1 = mysqli_connect($servername, $user_name, $password);
if (!$conn1) {
    die('Could not connect: ' . mysqli_error());
}
// $db_selected1 = mysqli_select_db($conn1,$db);
// if (!$db_selected1) {
//     die ('Can\'t use signalbit_erp : ' . mysqli_error());
// }

$conn2 = mysqli_connect($server, $user, $pass);
if (!$conn2) {
    die('Could not connect: ' . mysqli_error());
}
// $db_selected2 = mysqli_select_db($database, $conn2);
// if (!$db_selected2) {
//     die ('Can\'t use sbv2 : ' . mysqli_error());
// }

$conn3 = mysqli_connect($server_hris, $user_hris, $pass_hris);
if (!$conn3) {
    die('Could not connect: ' . mysqli_error());
}
// $db_selected3 = mysqli_select_db($database_hris, $conn3);
// if (!$db_selected3) {
//     die ('Can\'t use hris : ' . mysqli_error());
// }
?>