<?php
session_start();

include "../../include/conn.php";
include "../forms/fungsi.php";

$user=$_SESSION['username'];
$peri=$_REQUEST['perinya'];
$first_day=date("Y-m-01",strtotime($peri));
$last_day=date("Y-m-t",strtotime($peri));

$sql = "select bppbno isi,if(bppbno_int!='',bppbno_int,bppbno) tampil from 
  bppb where ifnull(cancel,'N')='N' and bppbdate between '$first_day' and '$last_day' group by bppbno";
IsiCombo($sql,$id_cost,'Pilih SJ #');
?>
