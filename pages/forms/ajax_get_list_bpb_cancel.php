<?php
session_start();

include "../../include/conn.php";
include "../forms/fungsi.php";

$user=$_SESSION['username'];
$peri=$_REQUEST['perinya'];
$first_day=date("Y-m-01",strtotime($peri));
$last_day=date("Y-m-t",strtotime($peri));

$sql = "select bpbno isi,if(bpbno_int!='',bpbno_int,bpbno) tampil from 
  bpb where ifnull(cancel,'N')='N' and bpbdate between '$first_day' and '$last_day' group by bpbno";
IsiCombo($sql,$id_cost,'Pilih BPB #');
?>
