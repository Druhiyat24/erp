<?php 
include "../../include/conn.php";
include "../forms/fungsi.php";
$modajax=$_GET['mdajax'];
if ($modajax=="format_ribuan" or $modajax=="format_ribuan_dec")
{	$cri=$_REQUEST['angkanya'];
	if($modajax=="format_ribuan_dec")
	{$hsl=fn($cri,2);}
	else
	{$hsl=fn($cri,0);}
	echo json_encode(array($hsl));
}
?>