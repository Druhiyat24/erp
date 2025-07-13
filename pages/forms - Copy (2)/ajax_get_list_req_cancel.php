<?php
session_start();

include "../../include/conn.php";
include "../forms/fungsi.php";

$user=$_SESSION['username'];
$peri=$_REQUEST['perinya'];
$first_day=date("Y-m-01",strtotime($peri));
$last_day=date("Y-m-t",strtotime($peri));

$sql = "select bppbno isi,bppbno tampil from 
  bppb_req where ifnull(cancel,'N')='N' and bppbdate between '$first_day' and '$last_day' group by bppbno";
IsiCombo($sql,$id_cost,'Pilih Req #');
?>
