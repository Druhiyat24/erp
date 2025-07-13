<?php
$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$tgl_down=date('Y-m-d H:i:s');

include "../marketting/excel_reader2.php";

$data = new Spreadsheet_Excel_Reader($_FILES['txtfile']['tmp_name']);
$jmlbaris = $data->rowcount($sheet_index=0);

$sql="delete from masterproduct_detail_tmp where username='$user' and sesi='$sesi'";
insert_log($sql,$user);

for ($i=3; $i<=$jmlbaris; $i++)
{	$seqno=$data->val($i, 1, 0);
	$level=$data->val($i, 2, 0);
	$level=str_replace(".","",$level);
	$jenis=$data->val($i, 9, 0);
	if($jenis!="FG" and $jenis!="RM" and $jenis!="WIP")
	{ $_SESSION['msg']="XJenis Barang Tidak Valid. Cek Baris Ke ".$i; 
		echo "<script>window.location.href='../master/?mod=28';</script>";
		exit;
	}
	$id_barang=$data->val($i, 3, 0);
	$kode_barang=$id_barang;
	if($id_barang=="")
	{ $_SESSION['msg']="XKode Barang Tidak Boleh Kosong. Cek Baris Ke ".$i; 
		echo "<script>window.location.href='../master/?mod=28';</script>";
		exit;
	}
	if($jenis=="FG")
	{$cek=flookup("id","masterproduct","product_group='$id_barang'");}
	else if($jenis=="WIP")
	{$cek=flookup("id_item","masteritem","goods_code='$id_barang' and mattype='C'");}
	else 
	{$cek=flookup("id_item","masteritem","goods_code='$id_barang' and mattype not in ('C','M','N')");}
	
	if($cek=="" and $jenis=="FG")
	{ $_SESSION['msg']="XPart # ".$id_barang." Tidak Ditemukan. Cek Baris Ke ".$i; 
		echo "<script>window.location.href='../master/?mod=28';</script>";
		exit;
	}
	else if($cek=="" and $jenis=="WIP")
	{ $_SESSION['msg']="XKode WIP ".$id_barang." Tidak Ditemukan. Cek Baris Ke ".$i; 
		echo "<script>window.location.href='../master/?mod=28';</script>";
		exit;
	}
	else if($cek=="" and $jenis=="RM")
	{ $_SESSION['msg']="XKode Material ".$id_barang." Tidak Ditemukan. Cek Baris Ke ".$i; 
		echo "<script>window.location.href='../master/?mod=28';</script>";
		exit;
	}
	$itemdesc=$data->val($i, 4, 0);
	$spec=$data->val($i, 5, 0);
	
	$id_barang=$cek;
	
	$cons=$data->val($i, 7, 0);
	if(!is_numeric($cons))
	{ $_SESSION['msg']="XCons Tidak Valid. Cek Baris Ke ".$i; 
		echo "<script>window.location.href='../master/?mod=28';</script>";
		exit;
	}
	$unit=$data->val($i, 6, 0);
	$cek=flookup("nama_pilihan","masterpilihan","kode_pilihan='Satuan' and nama_pilihan='$unit'");
	if($cek=="")
	{ $_SESSION['msg']="XSatuan $unit Tidak Ditemukan. Cek Baris Ke ".$i; 
		echo "<script>window.location.href='../master/?mod=28';</script>";
		exit;
	}
	$process=$data->val($i, 8, 0);
	$allow=0;
	if(!is_numeric($allow))
	{ $_SESSION['msg']="XAllowance Tidak Valid. Cek Baris Ke ".$i; 
		echo "<script>window.location.href='../master/?mod=28';</script>";
		exit;
	}
	if($jenis=="FG") { $id_product_fg=$id_barang; }
	$sql="insert into masterproduct_detail_tmp (seqno,level,kode_barang,itemdesc,spec,process,jenis_barang,
		id_product,id_item,cons,unit,allowance,username,sesi)  
		values ('$seqno','$level','$kode_barang','$itemdesc','$spec','$process',
		'$jenis','$id_product_fg','$id_barang','$cons','$unit','$allow','$user','$sesi')";
	#echo "<br>".$sql; # FOR TEST
	insert_log($sql,$user);
}
echo "<script>window.location.href='../master/?mod=28R';</script>";
?>