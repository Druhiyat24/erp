<?php
$mod=$_GET['mod'];
$grafik=flookup("grafik","mastercompany","company!='' ");
if ($mod=="1" or $mod=="1L" or $mod=="1v")
{	include "list_in_out.php"; }
elseif ($mod=="2")
{	include "user_setting.php"; }
elseif ($mod=="1vh")
{	include "dihide.php"; }
?>