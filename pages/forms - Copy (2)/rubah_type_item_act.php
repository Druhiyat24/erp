<?php
include '../../include/conn.php';
include 'fungsi.php';

session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$id_item_old=strtoupper($_POST['txtfrom']);
$to=$_POST['txttipe'];
$mat_oldnya=$_POST['txttahun'];

if ($to=="Barang Jadi") { $mat_new="FG"; } else { $mat_new=$to; }

# BPB
if ($mat_oldnya=="Barang Jadi")
{ $query = mysql_query("select bpbno,id_item from bpb where id_item='$id_item_old' and bpbno like 'FG%'"); }
else
{ $query = mysql_query("select bpbno,id_item from bpb where id_item='$id_item_old' and bpbno not like 'FG%' 
	and left(bpbno,1)!='$mat_new'"); }
while($data = mysql_fetch_array($query))
{	$mat_old=substr($data['bpbno'], 0,2);
	if ($mat_old!="FG") { $mat_old=substr($data['bpbno'], 0,1); }
	if ($mat_new==$mat_old)
	{	echo "<script>
		alert('Tipe tidak boleh sama');
		window.location.href='index.php?mod=12';
		</script>";
	}
	$nom_old=$data['bpbno'];
	$nom_new=urutkan("Add_BPB",$mat_new);
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
		$sql="update bpb set bpbno='$nom_new',id_item='$id_item_new' where bpbno='$nom_old' 
			and id_item='$id_item_old'";
		insert_log($sql,$user);
		calc_stock($mat_old,$id_item_old);
		$sql="delete from stock where bpb=0 and bppb=0 and id_item='$id_item_old'";
		insert_log($sql,$user);
		calc_stock($mat_new,$id_item_new);
		$sql="delete from stock where bpb=0 and bppb=0 and id_item='$id_item_new'";
		insert_log($sql,$user);
		# DELETE MASTERITEM KARENA SUDAH ADA DI MASTERSTYLE
		$jumbpb=flookup("count(*)","bpb","id_item='$id_item_old' and bpbno not regexp 'FG'");
		$jumbppb=flookup("count(*)","bppb","id_item='$id_item_old' and bppbno not regexp 'FG'");
		$jumtrans = $jumbpb + $jumbppb; 
		if ($jumtrans==0)
		{	$sql="delete from masteritem where id_item='$id_item_old'";
			insert_log($sql,$user);
		}
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
		$sql="update bpb set bpbno='$nom_new',id_item='$id_item_new' where bpbno='$nom_old' 
			and id_item='$id_item_old'";
		insert_log($sql,$user);
		calc_stock($mat_old,$id_item_old);
		$sql="delete from stock where bpb=0 and bppb=0 and id_item='$id_item_old'";
		insert_log($sql,$user);
		calc_stock($mat_new,$id_item_new);
		$sql="delete from stock where bpb=0 and bppb=0 and id_item='$id_item_new'";
		insert_log($sql,$user);
	}
	else
	{	$id_item_new=$id_item_old;
		$sql="update masteritem set mattype='$mat_new' where id_item='$id_item_old'";
		insert_log($sql,$user);
		$sql="update bpb set bpbno='$nom_new',id_item='$id_item_new' where bpbno='$nom_old' 
			and id_item='$id_item_old'";
		insert_log($sql,$user);
		calc_stock($mat_old,$id_item_old);
		$sql="delete from stock where bpb=0 and bppb=0 and id_item='$id_item_old'";
		insert_log($sql,$user);
		calc_stock($mat_new,$id_item_new);
		$sql="delete from stock where bpb=0 and bppb=0 and id_item='$id_item_new'";
		insert_log($sql,$user);
	}
}

# BPPB
if ($mat_oldnya=="Barang Jadi")
{ $query = mysql_query("select bppbno,id_item from bppb where id_item='$id_item_old' and bppbno like 'SJ-FG%'"); }
else
{ $query = mysql_query("select bppbno,id_item from bppb where id_item='$id_item_old' and bppbno not like 'SJ-FG%'
	and mid(bppbno,4,1)!='$mat_new'"); }
while($data = mysql_fetch_array($query))
{ $mat_old=substr($data['bppbno'],3,2);
  if ($mat_old!="FG") { $mat_old=substr($data['bppbno'],3,1); }
  if ($mat_new==$mat_old)
  { echo "<script>
    alert('Tipe tidak boleh sama');
    window.location.href='index.php?mod=12';
    </script>";
  }
  $nom_old=$data['bppbno'];
  $nom_new=urutkan("Add_BPPB",$mat_new);
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
      and id_item='$id_item_old'";
    insert_log($sql,$user);
    calc_stock($mat_old,$id_item_old);
    $sql="delete from stock where bpb=0 and bppb=0 and id_item='$id_item_old'";
		insert_log($sql,$user);
    calc_stock($mat_new,$id_item_new);
    $sql="delete from stock where bpb=0 and bppb=0 and id_item='$id_item_new'";
		insert_log($sql,$user);
  	# DELETE MASTERITEM KARENA SUDAH ADA DI MASTERSTYLE
		$jumbpb=flookup("count(*)","bpb","id_item='$id_item_old' and bpbno not regexp 'FG'");
		$jumbppb=flookup("count(*)","bppb","id_item='$id_item_old' and bppbno not regexp 'FG'");
		$jumtrans = $jumbpb + $jumbppb; 
		if ($jumtrans==0)
		{	$sql="delete from masteritem where id_item='$id_item_old'";
			insert_log($sql,$user);
		}
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
      and id_item='$id_item_old'";
    insert_log($sql,$user);
    calc_stock($mat_old,$id_item_old);
    $sql="delete from stock where bpb=0 and bppb=0 and id_item='$id_item_old'";
	insert_log($sql,$user);
    calc_stock($mat_new,$id_item_new);
    $sql="delete from stock where bpb=0 and bppb=0 and id_item='$id_item_new'";
	insert_log($sql,$user);
  }
  else
  { $id_item_new=$id_item_old;
    $sql="update masteritem set mattype='$mat_new' where id_item='$id_item_old'";
    insert_log($sql,$user);
    $sql="update bppb set bppbno='$nom_new',id_item='$id_item_new' where bppbno='$nom_old' 
      and id_item='$id_item_old'";
    insert_log($sql,$user);
    calc_stock($mat_old,$id_item_old);
    calc_stock($mat_new,$id_item_new);
    $sql="delete from stock where bpb+bppb+stock=0";
    insert_log($sql,$user);
  }
}

echo "<script>
	alert('Data berhasil dirubah');
	window.location.href='index.php?mod=12';
</script>";
?>