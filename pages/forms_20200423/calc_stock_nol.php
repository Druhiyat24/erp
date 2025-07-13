<?php
include "fungsi.php";
include "../../include/conn.php";

session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];

$supplier="INHOUSE";
$id_supplier = flookup("id_supplier","mastersupplier","supplier='$supplier'");
if ($id_supplier=="")
{	$sql="insert into mastersupplier (supplier,status_kb,area) 
	values ('$supplier','','F')";
insert_log($sql,$user);
$id_supplier = flookup("id_supplier","mastersupplier","supplier='$supplier'");
}
# CALCULATE STOCK
$query = mysql_query("select id_item,mattype from stock where stock>0");
while($data = mysql_fetch_array($query))
{	$mattype = $data['mattype'];
	$id_item = $data['id_item'];
	calc_stock($mattype,$id_item);
}
# BUAT BPPB JIKA BUKAN 0
$query = mysql_query("select id_item,mattype from stock where stock>0 group by mattype");
while($data = mysql_fetch_array($query))
{	$mattype = $data['mattype'];
	$trans_no_bpb = urutkan('Add_BPPB',$mattype);
	$query2 = mysql_query("select id_item,mattype from stock where stock>0 and mattype='$mattype'");
	while($data2 = mysql_fetch_array($query2))
	{	$id_item=$data2['id_item'];
		$lastbppbdate1=flookup("max(bpbdate)","bpb","bpbno like '$mattype%' and id_item='$id_item'");
		$lastbppbdate=fd($lastbppbdate1);
		$satuan=flookup("unit","bpb","bpbno like '$mattype%' and id_item='$id_item'");
		$sql = "insert into bppb (bppbno,bppbdate,jenis_dok,id_item,id_supplier,qty,unit,curr,price,
			bcno,bcdate,nomor_aju,tanggal_aju,tujuan,invno,kpno,username,use_kite)
			select '$trans_no_bpb','$lastbppbdate','INHOUSE',id_item,'$id_supplier',stock,'$satuan','',
			0,'-','0000-00-00','-','0000-00-00','','',id_item,
			'$user','1' from stock
			where mattype='$mattype' and id_item='$id_item'";
		insert_log($sql,$user);
		$sql = "delete from bpb where qty<=0";
		insert_log($sql,$user);
		calc_stock($mattype,$id_item);
	}
}
echo "<script>window.location.href='index.php?mod=1&msg=1';</script>";

?>