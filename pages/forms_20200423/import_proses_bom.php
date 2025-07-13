<?php
include "fungsi.php";
include "../../include/conn.php";

session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$nm_company=flookup("company","mastercompany","company!=''");

$user = $_SESSION['username']; #mendapatkan data id  dari method get
$sesi = $_SESSION['sesi'];;

$sql = "insert into upload_bom_hist select * from upload_bom where username='$user'
	and sesi='$sesi'";
insert_log($sql,$user);
$sql = "delete from upload_bom where username='$user'
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
		$customer = $row_data[0];
		$kpno = $row_data[1];
		$styleno = $row_data[2];
		$buyerno = $row_data[3];
		$mdname = $row_data[4];
		$qty = $row_data[5];
		$unit = $row_data[6];
		$deldate = $row_data[7];
		$itemname = $row_data[8];
		$goods_code = $row_data[9];
		$itemdesc = $row_data[10];
		$cons = $row_data[11];
		$allowance = $row_data[12];
		$mattype = $row_data[13];
		$satuan = $row_data[14];
		$cri = "a.kpno='$kpno' and a.buyerno='$buyerno' and a.deldate='$deldate'";
		$cek = flookup("a.kpno","masterstyle a inner join bom s on a.id_item=s.id_item_fg",$cri);
		if ($cek=="")
		{	$sql = "insert into upload_bom (customer,kpno,styleno,buyerno,mdname,
				qty,unit,deldate,itemname,goods_code,itemdesc,cons,satuan,allowance,mattype,
				username,sesi,nama_file,tgl_download) values ('$customer','$kpno',
				'$styleno','$buyerno','$mdname','$qty','$unit','$deldate','$itemname',
				'$goods_code','$itemdesc','$cons','$satuan','$allowance','$mattype','$user',
				'$sesi','$nama_file','$tgl_down')";
			insert_log($sql,$user);
		}
		else
		{ $cek = flookup("a.kpno","masterstyle a inner join bom s on 
				a.id_item=s.id_item_fg inner join bpb d 
				on s.id_item_bb=d.id_item",$cri." and bpbno not like 'FG%' ");
			if ($cek!="")
			{	$data_exists = "Y";
			} else
			{	$data_exists = "N";
				$sql="delete s.* from masterstyle a inner join bom s on 
					a.id_item=s.id_item_fg where $cri";
				insert_log($sql,$user);
				$sql = "insert into upload_bom (customer,kpno,styleno,buyerno,mdname,
					qty,unit,deldate,itemname,goods_code,itemdesc,cons,satuan,allowance,mattype,
					username,sesi,nama_file,tgl_download) values ('$customer','$kpno',
					'$styleno','$buyerno','$mdname','$qty','$unit','$deldate','$itemname',
					'$goods_code','$itemdesc','$cons','$satuan','$allowance','$mattype','$user',
					'$sesi','$nama_file','$tgl_down')";
				insert_log($sql,$user);
			}

		}
	}
}
# CEK MASTER STYLE
$que = "select * from upload_bom where username='$user'
	and sesi='$sesi' group by kpno,buyerno,deldate";
$result = mysql_query($que);
if (!$result) { die($que. mysql_error()); }
while($rs = mysql_fetch_array($result))
{	$customer = $rs['customer'];
	$kpno = $rs['kpno'];
	$styleno = $rs['styleno'];
	$buyerno = $rs['buyerno'];
	$mdname = $rs['mdname'];
	$qty = $rs['qty'];
	$unit = $rs['unit'];
	$deldate = $rs['deldate'];
	$itemname = $rs['itemname'];
	$cri = "kpno='$kpno' and buyerno='$buyerno' and deldate='$deldate'";
	$cek = flookup("kpno","masterstyle",$cri);
	if ($cek=="")
	{ $sql = "insert into masterstyle (customer,kpno,styleno,buyerno,mdname,qty,
			unit,deldate,itemname) values ('$customer','$kpno','$styleno',
			'$buyerno','$mdname','$qty','$unit','$deldate','$itemname') ";
		insert_log($sql,$user);
	}
	else
	{	$sql = "update masterstyle set qty='$qty' where $cri ";
		insert_log($sql,$user);
	}
	$id_item_fg = flookup("id_item","masterstyle",$cri);
	$sql = "update upload_bom set id_item_fg='$id_item_fg' where $cri ";
	insert_log($sql,$user);		
}
# CEK MASTER ITEM
$que = "select * from upload_bom where username='$user'
	and sesi='$sesi' group by goods_code,itemdesc";
$result = mysql_query($que);
if (!$result) { die($que. mysql_error()); }
while($rs = mysql_fetch_array($result))
{	$goods_code = $rs['goods_code'];
	$itemdesc = $rs['itemdesc'];
	$mattype = $rs['mattype'];
	$cri = "goods_code='$goods_code' and itemdesc='$itemdesc'";
	$cek = flookup("itemdesc","masteritem",$cri);
	if ($cek=="")
	{ $sql = "insert into masteritem (goods_code,itemdesc,mattype,matclass,
			color,size) values 
			('$goods_code','$itemdesc','$mattype','-','-','-') ";
		insert_log($sql,$user);
	}
	$id_item_bb = flookup("id_item","masteritem",$cri);
	$sql = "update upload_bom set id_item_bb='$id_item_bb' where $cri ";
	insert_log($sql,$user);		
}
$sql = "insert into bom (id_item_fg,id_item_bb,cons,satuan,allowance,
	date_create) select id_item_fg,id_item_bb,cons,satuan,allowance,'$tgl_create' 
	from upload_bom where 
	username='$user' and sesi='$sesi'";		
insert_log($sql,$user);
if ($data_exists=="Y")
{ $_SESSION['msg'] = "XBOM Sudah Ada"; }
else
{ $_SESSION['msg'] = "BOM Berhasil Diupload"; }
echo "<script>window.location.href='index.php?mod=1';</script>";
?>