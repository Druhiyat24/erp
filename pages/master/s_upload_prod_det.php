<?php
$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$tgl_down=date('Y-m-d H:i:s');

$sql="select * from masterproduct_detail_tmp where username='$user' and sesi='$sesi' 
	and jenis_barang='FG'";
$rs=mysql_query($sql);
while($dataFG = mysql_fetch_array($rs))
{	$cek=flookup("id_product","masterproduct_h","id_product='$dataFG[id_product]'");
	if($cek!="")
	{	$sql="delete from masterproduct_h where id_product='$dataFG[id_product]'";
		insert_log($sql,$user);
	}
}
$sql="select * from masterproduct_detail_tmp where username='$user' and sesi='$sesi' 
	and jenis_barang='FG'";
$rs=mysql_query($sql);
while($dataFG = mysql_fetch_array($rs))
{	$sql2="select * from masterproduct_detail_tmp 
		where jenis_barang!='FG' and id_product='$dataFG[id_product]' 
		and username='$user' and sesi='$sesi' order by seqno ";
	$rs2=mysql_query($sql2);
	while($dataDT = mysql_fetch_array($rs2))
	{	$sql="insert into masterproduct_h (id_product,id_item,cons,unit) 
			values ('$dataFG[id_product]','$dataDT[id_item]',
			'$dataDT[cons]','$dataDT[unit]')";
		insert_log($sql,$user);
	}
}
$_SESSION['msg']="Upload Berhasil";
echo "<script>window.location.href='../master/?mod=28L';</script>";
?>