<?php
include '../../include/conn.php';
include 'fungsi.php';

session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$nom_aju=strtoupper($_POST['txtfrom']);
if($nom_aju=="") { $nom_aju="xxx"; }
$nom_aju6=substr($nom_aju,20,6);
$to=$_POST['txttipe'];

if ($to=="Barang Jadi") { $mat_new="FG"; } else { $mat_new=$to; }

# BPB
if($nom_aju6!="") { $sql_cri=" or kpno='$nom_aju6'"; } else { $sql_cri=" "; }
$sql = "select bpbno,id_item from bpb where nomor_aju='$nom_aju' or kpno='$nom_aju' $sql_cri ";
$query = mysql_query($sql);
$nom_new = "";
while($data = mysql_fetch_array($query))
{	$mat_old=substr($data['bpbno'], 0,2);
	if ($mat_old!="FG") { $mat_old=substr($data['bpbno'], 0,1); }
	if ($mat_new==$mat_old)
	{	$_SESSION['msg'] = "XTipe Tidak Boleh Sama";
		echo "<script>window.location.href='index.php?mod=11';</script>";
	}
	else
	{	$nom_old=$data['bpbno'];
		if ($nom_new=="") { $nom_new=urutkan("Add_BPB",$mat_new); }
		$id_item_old = $data['id_item'];
		if ($mat_old!="FG" AND $mat_new=="FG")
		{	$kode_brg=flookup("goods_code","masteritem","id_item='$id_item_old'");
			$nama_brg=flookup("itemdesc","masteritem","id_item='$id_item_old'");
			$id_item_new=flookup("id_item","masterstyle","goods_code='$kode_brg' and itemname='$nama_brg'");
			if ($id_item_new=="")
			{	$sql="insert into masterstyle (goods_code,itemname) values ('$kode_brg','$nama_brg')";
				insert_log($sql,$user);
				$id_item_new=flookup("id_item","masterstyle","goods_code='$kode_brg' and itemname='$nama_brg'");
			}
			if($nom_aju6!="") { $sql_cri=" or kpno='$nom_aju6'"; } else { $sql_cri=" "; }
			$sql="update bpb set bpbno='$nom_new',id_item='$id_item_new' where bpbno='$nom_old' 
				and id_item='$id_item_old' and (nomor_aju='$nom_aju' or kpno='$nom_aju' $sql_cri)";
			insert_log($sql,$user);
			calc_stock($mat_old,$id_item_old);
			calc_stock($mat_new,$id_item_new);
			$sql="delete from stock where bpb+bppb+stock=0";
	    	insert_log($sql,$user);
		}
		else if ($mat_old=="FG" AND $mat_new!="FG")
		{	$kode_brg=flookup("goods_code","masterstyle","id_item='$id_item_old'");
			$nama_brg=flookup("itemname","masterstyle","id_item='$id_item_old'");
			$id_item_new=flookup("id_item","masteritem","goods_code='$kode_brg' and itemdesc='$nama_brg'");
			if ($id_item_new=="")
			{	$sql="insert into masteritem (goods_code,itemdesc) values ('$kode_brg','$nama_brg')";
				insert_log($sql,$user);
				$id_item_new=flookup("id_item","masteritem","goods_code='$kode_brg' and itemdesc='$nama_brg'");
			}
			if($nom_aju6!="") { $sql_cri=" or kpno='$nom_aju6'"; } else { $sql_cri=" "; }
			$sql="update bpb set bpbno='$nom_new',id_item='$id_item_new' where bpbno='$nom_old' 
				and id_item='$id_item_old' and (nomor_aju='$nom_aju' or kpno='$nom_aju' $sql_cri)";
			insert_log($sql,$user);
			calc_stock($mat_old,$id_item_old);
			calc_stock($mat_new,$id_item_new);
			$sql="delete from stock where bpb+bppb+stock=0";
	    	insert_log($sql,$user);
		}
		else
		{	$id_item_new=$id_item_old;
			$sql="update masteritem set mattype='$mat_new' where id_item='$id_item_old'";
			insert_log($sql,$user);
			if($nom_aju6!="") { $sql_cri=" or kpno='$nom_aju6'"; } else { $sql_cri=" "; }
			$sql="update bpb set bpbno='$nom_new',id_item='$id_item_new' where bpbno='$nom_old' 
				and id_item='$id_item_old' and (nomor_aju='$nom_aju' or kpno='$nom_aju' $sql_cri) ";
			insert_log($sql,$user);
			calc_stock($mat_old,$id_item_old);
			calc_stock($mat_new,$id_item_new);
			$sql="delete from stock where bpb+bppb+stock=0";
	    	insert_log($sql,$user);
		}
		$_SESSION['msg'] = "Data Berhasil Dirubah";
		echo "<script>window.location.href='index.php?mod=11';</script>";	
	}
}

# BPPB
$query = mysql_query("select bppbno,id_item from bppb where nomor_aju='$nom_aju'");
$nom_new = "";
while($data = mysql_fetch_array($query))
{ $mat_old=substr($data['bppbno'],3,2);
  if ($mat_old!="FG") { $mat_old=substr($data['bppbno'],3,1); }
  if ($mat_new==$mat_old)
  { $_SESSION['msg'] = "XTipe Tidak Boleh Sama";
		echo "<script>window.location.href='index.php?mod=11';</script>";
  }
  else
  {	$nom_old=$data['bppbno'];
	  if ($nom_new=="") { $nom_new=urutkan("Add_BPPB",$mat_new); }
	  $id_item_old = $data['id_item'];
	  if ($mat_old!="FG" AND $mat_new=="FG")
	  { $kode_brg=flookup("goods_code","masteritem","id_item='$id_item_old'");
	    $nama_brg=flookup("itemdesc","masteritem","id_item='$id_item_old'");
	    $id_item_new=flookup("id_item","masterstyle","goods_code='$kode_brg' and itemname='$nama_brg'");
	    if ($id_item_new=="")
	    { $sql="insert into masterstyle (goods_code,itemname) values ('$kode_brg','$nama_brg')";
	      insert_log($sql,$user);
	      $id_item_new=flookup("id_item","masterstyle","goods_code='$kode_brg' and itemname='$nama_brg'");
	    }
	    $sql="update bppb set bppbno='$nom_new',id_item='$id_item_new' where bppbno='$nom_old' 
	      and id_item='$id_item_old' and nomor_aju='$nom_aju'";
	    insert_log($sql,$user);
	    calc_stock($mat_old,$id_item_old);
	    calc_stock($mat_new,$id_item_new);
	    $sql="delete from stock where bpb+bppb+stock=0";
	    insert_log($sql,$user);
	  }
	  else if ($mat_old=="FG" AND $mat_new!="FG")
	  { $kode_brg=flookup("goods_code","masterstyle","id_item='$id_item_old'");
	    $nama_brg=flookup("itemname","masterstyle","id_item='$id_item_old'");
	    $id_item_new=flookup("id_item","masteritem","goods_code='$kode_brg' and itemdesc='$nama_brg'");
	    if ($id_item_new=="")
	    { $sql="insert into masteritem (goods_code,itemdesc) values ('$kode_brg','$nama_brg')";
	      insert_log($sql,$user);
	      $id_item_new=flookup("id_item","masteritem","goods_code='$kode_brg' and itemdesc='$nama_brg'");
	    }
	    $sql="update bppb set bppbno='$nom_new',id_item='$id_item_new' where bppbno='$nom_old' 
	      and id_item='$id_item_old' and nomor_aju='$nom_aju'";
	    insert_log($sql,$user);
	    calc_stock($mat_old,$id_item_old);
	    calc_stock($mat_new,$id_item_new);
	    $sql="delete from stock where bpb+bppb+stock=0";
	    insert_log($sql,$user);
	  }
	  else
	  { $id_item_new=$id_item_old;
	    $sql="update masteritem set mattype='$mat_new' where id_item='$id_item_old'";
	    insert_log($sql,$user);
	    $sql="update bppb set bppbno='$nom_new',id_item='$id_item_new' where bppbno='$nom_old' 
	      and id_item='$id_item_old' and nomor_aju='$nom_aju'";
	    insert_log($sql,$user);
	    calc_stock($mat_old,$id_item_old);
	    calc_stock($mat_new,$id_item_new);
	    $sql="delete from stock where bpb+bppb+stock=0";
	    insert_log($sql,$user);
	  }
		$_SESSION['msg'] = "Data Berhasil Dirubah";
		echo "<script>window.location.href='index.php?mod=11';</script>";
	}
}
?>