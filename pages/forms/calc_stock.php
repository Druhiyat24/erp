<?php
include "fungsi.php";
include "../../include/conn.php";

session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$st_company = flookup("status_company","mastercompany","company<>''");
$mode=$_GET['mode'];

$supplier="INHOUSE";
$id_supplier = flookup("id_supplier","mastersupplier","supplier='$supplier'");
if ($id_supplier=="")
{	$sql="insert into mastersupplier (supplier,status_kb,area) 
		values ('$supplier','','F')";
	insert_log($sql,$user);
	$id_supplier = flookup("id_supplier","mastersupplier","supplier='$supplier'");
}

if ($mode=="2")
{}

if ($mode=="0" OR $mode=="1")
{	# CALCULATE STOCK ALL
	$query = mysql_query("select id_item,mattype from stock");
	while($data = mysql_fetch_array($query))
	{	$mattype = $data['mattype'];
		$id_item = $data['id_item'];
		calc_stock($mattype,$id_item);
	}
}
if ($mode=="1")
{	# BUAT BPB JIKA MINUS NAMUN UNTUK GB HAPUS BPPB YG SDH TIDAK MENCUKUPI STOCK
	$query = mysql_query("select id_item,mattype from stock where stock<0 group by mattype");
	while($data = mysql_fetch_array($query))
	{	if ($st_company=="GB")
		{	$mattype = $data['mattype'];
			$query2 = mysql_query("select id_item,mattype from stock where stock<0 
				and mattype='$mattype' ");
			while($data2 = mysql_fetch_array($query2))
			{	$id_item=$data2['id_item'];
				$query3 = mysql_query("select id_item,bpbno,qty from bpb where id_item='$id_item' 
					order by bpbdate");
				while($data3 = mysql_fetch_array($query3))
				{	$qtybpb=$data3['qty'];
					$query4 = mysql_query("select id_item,bppbno,qty from bppb where id_item='$id_item' 
						order by bppbdate");
					echo $id_item.'>'.$cekjmlbppb;
					while($data4 = mysql_fetch_array($query4))
					{	$qtybppb=$data4['qty'];
						$cekjmlbppb=flookup("count(distinct bppbno)","bppb","id_item='$id_item' ");
						if ($qtybppb>=$qtybpb AND $cekjmlbppb!="1")
						{	$sql="delete from bppb where bppbno='$data4[bppbno]' and id_item='$data4[id_item]'
								and qty='$data4[qty]' limit 1";
							insert_log($sql,$user);
						}
						else if ($qtybppb>=$qtybpb AND $cekjmlbppb=="1")
						{	$sql="update bppb set qty='$qtybpb' where bppbno='$data4[bppbno]' and 
								id_item='$data4[id_item]' and qty='$data4[qty]' limit 1";
							insert_log($sql,$user);
						}
					}
					calc_stock($mattype,$id_item);	
				}
			}
		}
		else
		{	$mattype = $data['mattype'];
			$trans_no_bpb = urutkan('Add_BPB',$mattype);
			$query2 = mysql_query("select id_item,mattype from stock where stock<0 and mattype='$mattype'");
			while($data2 = mysql_fetch_array($query2))
			{	$id_item=$data2['id_item'];
				$lastbppbdate1=flookup("max(bppbdate)","bppb","bppbno like 'SJ-$mattype%' and id_item='$id_item'");
				$lastbppbdate=fd($lastbppbdate1);
				$satuan=flookup("unit","bppb","bppbno like 'SJ-$mattype%' and id_item='$id_item'");
				$sql = "insert into bpb (bpbno,bpbdate,jenis_dok,id_item,id_supplier,qty,unit,curr,price,
					bcno,bcdate,nomor_aju,tanggal_aju,tujuan,pono,invno,kpno,username,use_kite)
					select '$trans_no_bpb','$lastbppbdate','INHOUSE',id_item,'$id_supplier',abs(stock),'$satuan','',
					0,'-','0000-00-00','-','0000-00-00','','','',id_item,
					'$user','1' from stock
					where mattype='$mattype' and id_item='$id_item'";
				insert_log($sql,$user);
				$sql = "delete from bpb where qty<=0";
				insert_log($sql,$user);
				calc_stock($mattype,$id_item);
			}
		}
	}
}
$_SESSION['msg'] = "Kalkulasi Selesai";
echo "<script>window.location.href='index.php?mod=1';</script>";

?>