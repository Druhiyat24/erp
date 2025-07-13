<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user = $_SESSION['username'];
$sesi = $_SESSION['sesi'];
if (isset($_GET['mode'])) {$mode=$_GET['mode'];} else {$mode="";}
if (isset($_GET['mod'])) {$mod=$_GET['mod'];} else {$mod="";}

$jenis_company=flookup("jenis_company","mastercompany","company!=''");
$id_item=$_GET['id'];
if (isset($_GET['noid'])) {$transno=$_GET['noid']; } else {$transno="";}
if (isset($_GET['pro'])) {$pronya=$_GET['pro']; } else {$pronya="";}

if ($mod=="22L")
{	$sql="delete from konversi_satuan where id='$id_item'";
	insert_log($sql,$user);
	$_SESSION['msg'] = "Data Berhasil Dihapus";
	echo "<script>window.location.href='index.php?mod=$mod&mode=$mode';</script>";
	exit;
}
if ($pronya=="In" AND ($mode=="Bahan_Baku" OR $mode=="Scrap" OR $mode=="Mesin" OR $mode=="WIP" OR $mode=="FG" OR $mode=="General")) 
{	
	$cridel = "id='$id_item' and bpbno='$transno'";
	if ($mode=="FG")
	{	$sql = "select a.qty,'FG'mattype,a.id_item from 
			bpb a inner join masterstyle s on a.id_item=s.id_item
			where $cridel and bpbno like 'FG%'";
	}
	else
	{	$sql = "select a.qty,s.mattype,a.id_item from 
			bpb a inner join masteritem s on a.id_item=s.id_item
			where $cridel and bpbno not like 'FG%'";
	}
	$rs = mysql_fetch_array(mysql_query($sql));
	$qtyold = $rs['qty'];
	$cbomat = $rs['mattype'];
	$txtid_item = $rs['id_item'];
	$sql = "update bpb set qty=0 where $cridel ";
	insert_log($sql,$user);
	if ($cbomat=="FG")
	{ gen_kartu_stock($user,$sesi,$txtid_item,"bpbno like 'FG%'","bppbno like 'SJ-FG%'"); }
	else
	{ gen_kartu_stock($user,$sesi,$txtid_item,"bpbno not like 'FG%'","bppbno not like 'SJ-FG%'"); }
	$cek=cek_minus_by_date($user,$sesi);
	if ($cek<0)
	{	$sql="update bpb set qty='$qtyold' where $cridel";
		insert_log($sql,$user);
		$_SESSION['msg']="XStock Tidak Mencukupi : ".$cek;
		echo "
		<script>
			window.location.href='index.php?mod=$mod&mode=$mode&noid=$transno';
		</script>";
		exit;
	}
	$sql = "delete from bpb where $cridel";
	insert_log($sql,$user);
	if ($mode=="FG")
	{ calc_stock("FG",$txtid_item); }
	else
	{ calc_stock($cbomat,$txtid_item); }
	$_SESSION['msg']="Data Berhasil Dihapus";
	echo "
	<script>
		window.location.href='index.php?mod=$mod&mode=$mode&noid=$transno';
	</script>";		
}
else if ($pronya=="Out" AND ($mode=="Bahan_Baku" OR $mode=="Scrap" OR $mode=="Mesin" OR $mode=="WIP" OR $mode=="FG")) 
{	$sql = "delete from bppb where id='$id_item' and bppbno='$transno'";
	insert_log($sql,$user);
	if ($mode=="FG")
	{ calc_stock("FG",$id_item); }
	else
	{ calc_stock(substr($transno,3,1),$id_item); }
	$_SESSION['msg']="Data Berhasil Dihapus";
	echo "<script>
		window.location.href='../forms/?mod=$mod&mode=$mode&noid=$transno';
	</script>";
}
else if ($mode=="Supplier" OR $mode=="Customer") 
{	$cek = flookup("count(*)","bpb","id_supplier='$id_item'");
	if ($cek==0) { $cek = flookup("count(*)","bppb","id_supplier='$id_item'"); }	
	if ($cek==0) { $cek = flookup("count(*)","po_header","id_supplier='$id_item'"); }	
	if ($cek==0)
	{	$sql = "delete from mastersupplier where id_supplier='$id_item'";
		insert_log($sql,$user);
		if ($mode=="Customer")
		{	echo "<script>
				alert('Data berhasil dihapus');
				window.location.href='../master/?mod=20&mode=$mode';
			</script>";
		}
		else
		{	if ($mod==21)
			{	echo "<script>
					alert('Data berhasil dihapus');
					window.location.href='../master/?mod=$mod&mode=$mode';
				</script>";
			}
			else
			{	echo "<script>
					alert('Data berhasil dihapus');
					window.location.href='../forms/?mod=4&mode=$mode';
				</script>";
			}
		}
	}
	else
	{	$_SESSION['msg']="XData Tidak Bisa Dihapus";
		if ($mod==21)
		{	echo "<script>window.location.href='../master/?mod=$mod&mode=$mode';</script>";	}
		else
		{	echo "<script>window.location.href='../forms/?mod=4&mode=$mode';</script>";	}
	}	
}
else if ($mode=="Bahan_Baku" OR $mode=="Scrap" OR $mode=="Mesin" OR $mode=="WIP") 
{	$id_gen=flookup("id_gen","masteritem","id_item='$id_item'");
	$cek = flookup("count(*)","bpb","id_item='$id_item' and bpbno not like 'FG%'");
	if ($cek==0) { $cek = flookup("count(*)","bppb","id_item='$id_item' and bppbno not like 'SJ-FG%'"); }	
	if ($cek==0 and $jenis_company!="VENDOR LG") { $cek = flookup("count(*)","po_item","id_gen='$id_gen' "); }	
	if ($cek==0 and $jenis_company=="VENDOR LG") { $cek = flookup("count(*)","po_item","id_gen='$id_item' "); }	
	if ($cek==0 and $jenis_company=="VENDOR LG") { $cek = flookup("count(*)","act_costing_mat","id_item='$id_item' "); }
	if ($cek==0)
	{	$sql = "delete from masteritem where id_item='$id_item'";
		insert_log($sql,$user);
		echo "<script>
			alert('Data berhasil dihapus');
			window.location.href='../forms/?mod=2L&mode=$mode';
		</script>";
	}
	else
	{	$_SESSION['msg']="XData Tidak Bisa Dihapus Karena Sudah Ada Transaksi";
		echo "<script>
			window.location.href='../forms/?mod=2L&mode=$mode';
		</script>";
	}	
}
else if ($mode=="FG") 
{	$cek = flookup("count(*)","bpb","id_item='$id_item' and bpbno like 'FG%'");
	if ($cek==0) { $cek = flookup("count(*)","bppb","id_item='$id_item' and bppbno like 'SJ-FG%'"); }	
	if ($cek==0)
	{	$sql = "delete from masterstyle where id_item='$id_item'";
		insert_log($sql,$user);
		echo "<script>
			alert('Data berhasil dihapus');
			window.location.href='index.php?mod=$mod&mode=$mode';
		</script>";
	}
	else
	{	echo "<script>
			alert('Data tidak bisa dihapus karena sudah ada transaksi');
			window.location.href='index.php?mod=$mod&mode=$mode';
		</script>";
	}	
}
?>