<?php
include "../../include/conn.php";
include "fungsi.php";
session_start();
$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$nm_company = $rscomp["company"];
$st_company = $rscomp["status_company"];
$status_company = $rscomp["jenis_company"];
$jenis_company = $rscomp["jenis_company"];
$logo_company = $rscomp["logo_company"];
$modenya = $_GET['modeajax'];
$user = $_SESSION['username'];
$tgl_input = date('Y-m-d H:i:s');

#echo $modenya;

if ($modenya == "submit_qr") {
	$txtqr = $_REQUEST['txtqr'];
	$txtqr1 = substr($txtqr, 0, strpos($txtqr, "|"));
	if ($txtqr1 == "") {
		$_SESSION['msg_scan'] = "Data Tidak Valid";
	} else {
		$sql = "insert into numbering_input (id_qr,user,tgl_input) 
			values ('$txtqr1', '$user','$tgl_input')
				";

		insert_log($sql, $user); {
			$_SESSION['msg_scan'] = "Data Berhasil Disimpan";
			// $_SESSION['msg'] = "Data Berhasil Disimpan, Nomor: " . $dataqr;
		}
	}
}
