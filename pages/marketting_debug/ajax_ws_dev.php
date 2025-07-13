<?php 
include "../../include/conn.php";
include "../forms/fungsi.php";
$modajax=$_GET['mdajax'];
if ($modajax=="cari_style")
{	$crinya=$_REQUEST['cri_item'];
	$sql="select styleno isi,styleno tampil from 
		act_development where id_buyer='$crinya' group by styleno";
	IsiCombo($sql,'','Pilih Style #');
	exit;
}
?>