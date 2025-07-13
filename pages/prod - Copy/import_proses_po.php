<?php
include "../forms/fungsi.php";
include "../../include/conn.php";

session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$nm_company=flookup("company","mastercompany","company!=''");

$user = $_SESSION['username']; #mendapatkan data id  dari method get
$sesi = $_SESSION['sesi'];;

$sql = "insert into upload_po_hist select * from upload_po where username='$user'
	and sesi='$sesi'";
insert_log($sql,$user);
$sql = "delete from upload_po where username='$user'
	and sesi='$sesi'";
insert_log($sql,$user);

$nama_file = $_FILES['txtfile']['name'];
$ukuran_file = $_FILES['txtfile']['size'];
$tipe_file = $_FILES['txtfile']['type'];
$tmp_file = $_FILES['txtfile']['tmp_name'];
$tgl_down = date('Y-m-d H-i-s');
$tgl_create = date('Y-m-d');
 
$path = "upload_files/".$nama_file;
move_uploaded_file($tmp_file, $path);

$data_exists = "N";
$txt_file    = file_get_contents($path);
$rows        = explode("\n", $txt_file);
array_shift($rows);
$i=1;
foreach($rows as $row => $data)
{	if ($data!="")
	{	$row_data = explode('|', $data);
		$pono = $row_data[0];
		$podate = $row_data[1];
		$supplier = $row_data[2];
		$itemdesc = $row_data[3];
		$qty = $row_data[4];
		$unit = $row_data[5];
		$curr = $row_data[6];
		$price = $row_data[7];
		$line_item = $row_data[8];
		
		$cek = flookup("pono","po","pono='$pono'");
		if ($cek=="")
		{	$sql = "insert into upload_po (pono,podate,supplier,itemdesc,qty,unit,
				curr,price,line_item,username,sesi,nm_file,tgl_download) 
				values ('$pono','$podate','$supplier','$itemdesc','$qty',
				'$unit','$curr','$price','$line_item',
				'$user','$sesi','$nama_file','$tgl_down')";
			insert_log($sql,$user);
		}
		else
		{ $data_exists = "Y"; }
	}
}
# CEK MASTER SUPPLIER
$que = "select * from upload_po where username='$user'
	and sesi='$sesi' group by supplier";
$result = mysql_query($que);
if (!$result) { die($que. mysql_error()); }
while($rs = mysql_fetch_array($result))
{	$supplier = $rs['supplier'];
	$cek = flookup("supplier","mastersupplier","supplier='$supplier'");
	if ($cek=="")
	{ $sql = "insert into mastersupplier (supplier) values ('$supplier') ";
		insert_log($sql,$user);
	}
	$id_supplier = flookup("id_supplier","mastersupplier","supplier='$supplier'");
	$sql = "update upload_po set id_supplier='$id_supplier' where supplier='$supplier' ";
	insert_log($sql,$user);		
}
# CEK MASTER ITEM
$que = "select * from upload_po where username='$user'
	and sesi='$sesi' group by itemdesc";
$result = mysql_query($que);
if (!$result) { die($que. mysql_error()); }
while($rs = mysql_fetch_array($result))
{	$goods_code = "-";
	$itemdesc = $rs['itemdesc'];
	$mattype = "A";
	$cri = "itemdesc='$itemdesc'";
	$cek = flookup("itemdesc","masteritem",$cri);
	if ($cek=="")
	{ $sql = "insert into masteritem (goods_code,itemdesc,mattype,matclass,
			color,size) values 
			('$goods_code','$itemdesc','$mattype','-','-','-') ";
		insert_log($sql,$user);
	}
	$id_item = flookup("id_item","masteritem",$cri);
	$sql = "update upload_po set id_item='$id_item' where $cri ";
	insert_log($sql,$user);		
}
if ($data_exists=="Y")
{ $_SESSION['msg'] = "XPO Sudah Ada"; }
else
{	$sql="insert into po (pono,podate,id_supplier,line_item,id_item,qty,unit,curr,price)
 		select pono,podate,id_supplier,line_item,id_item,qty,unit,curr,price
 		from upload_po where username='$user' and sesi='$sesi'";
 	insert_log($sql,$user); 
	$_SESSION['msg'] = "PO Berhasil Diupload"; 
}
echo "<script>window.location.href='index.php?mod=1';</script>";
?>