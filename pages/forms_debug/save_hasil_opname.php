<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user = $_SESSION['username'];
$sesi = $_SESSION['sesi'];;
$mode=$_GET['mode'];
$tglopname=$_GET['tgl'];
$tgl_down = date('Y-m-d H-i-s');
$id_supplier=flookup("id_supplier","mastersupplier","supplier='PRODUKSI' OR supplier='PRODUKSI'");
if ($id_supplier=="")
{	$sql = "insert into mastersupplier (Supplier,Attn,Phone,Fax,Email,area,alamat,alamat2,npwp,status_kb,country,tipe_sup)
		values ('PRODUKSI','','','','','F','','','','INHOUSE','','')";
	insert_log($sql,$user);
	$id_supplier=flookup("id_supplier","mastersupplier","supplier='PRODUKSI' OR supplier='PRODUKSI'");
}

if (!isset($_POST['item']))
{	echo "<script>window.location.href='index.php?mod=$mod&mode=$mode&msg=7';</script>"; }
else
{	#CEK TRANS APA YG HARUS DIBUAT BPB saja atau BKB saja atau BPB dan BKB
	$ItemArray = $_POST['item'];
	$ItemStockArray = $_POST['itemstock'];
	$sql="delete from upload_tpb where username='$user' and sesi='$sesi'";
	insert_log($sql,$user);	
	foreach ($ItemArray as $key => $value) 
	{	if (is_numeric($value))
		{	$hasil_opname=$value;
			$id_item=$key;
			$stock_original=$ItemStockArray[$id_item];
			$qty_trans=0;
			if ($mode=="hasilfg")
			{	$matnya="FG"; 
				$satuan=flookup("unit","bpb","id_item='$id_item' and bpbno regexp '$matnya' ");
			}
			else
			{	$matnya=flookup("mattype","masteritem","id_item='$id_item'"); 
				$satuan=flookup("unit","bpb","id_item='$id_item' and bpbno regexp '$matnya' 
					and bpbno not regexp 'FG' ");
			}
			if ($hasil_opname>$stock_original)
			{	$transnya="BPB"; 
				$qty_trans=$hasil_opname-$stock_original;
			}
			elseif ($hasil_opname<$stock_original)
			{	$transnya="BKB"; 
				$qty_trans=$stock_original-$hasil_opname;
			
			}
			if ($qty_trans!=0)
			{	$sql="insert into upload_tpb (USERNAME,SESI,URAIAN_DOKUMEN,ID_ITEM,ID_SUPPLIER,
					JUMLAH_SATUAN,MATTYPE,TANGGAL_AJU,KODE_SATUAN,TGL_DOWNLOAD) 
					values ('$user','$sesi','$transnya','$id_item','$id_supplier','$qty_trans','$matnya',
					'$tglopname','$satuan','$tgl_down')";
				insert_log($sql,$user);	
				echo "Id Item ".$id_item." Hasil Opname ".$hasil_opname." Stok Aslinya ".$stock_original." MAka Harus Buat ".$transnya." Dengan Qty ".$qty_trans;
		    echo "<br>";
	  	}
		}
	}
	$sql="select URAIAN_DOKUMEN,MATTYPE,TANGGAL_AJU from upload_tpb 
		where username='$user' and sesi='$sesi' group by URAIAN_DOKUMEN,MATTYPE,TANGGAL_AJU";
	$query = mysql_query($sql);
	while($data = mysql_fetch_array($query))
	{	$trans_pro=$data['URAIAN_DOKUMEN'];
		$mattype = $data['MATTYPE'];
		$tglopname = $data['TANGGAL_AJU'];
		
		if ($trans_pro=="BKB")
		{	if ($mattype=="FG")
			{	$trans_no=flookup("bppbno","bppb","id_supplier='$id_supplier' and bppbdate='$tglopname' 
					and bppbno regexp '$mattype'");
			}
			else
			{	$trans_no=flookup("bppbno","bppb","id_supplier='$id_supplier' and bppbdate='$tglopname'
					and mid(bppbno,4,1)='$mattype' and bppbno not regexp 'FG' ");
			}
			if ($trans_no=="") { $trans_no = urutkan('Add_BPPB', $mattype); }  
		}
		else
		{	if ($mattype=="FG")
			{	$trans_no=flookup("bpbno","bpb","id_supplier='$id_supplier' and bpbdate='$tglopname' 
					and bpbno regexp '$mattype'");
			}
			else
			{	$trans_no=flookup("bpbno","bpb","id_supplier='$id_supplier' and bpbdate='$tglopname' 
					and left(bpbno,1)='$mattype' and bpbno not regexp 'FG' ");
			}
			if ($trans_no=="") { $trans_no = urutkan('Add_BPB',$mattype); }  
		}
		if ($trans_no!="")
		{ $sql = "update upload_tpb set trans_no='$trans_no' where username='$user' 
				and sesi='$sesi' and URAIAN_DOKUMEN='$trans_pro' and MATTYPE='$mattype' 
				and TANGGAL_AJU='$tglopname' ";
		  insert_log($sql,$user);
		}
	}
	$sql="insert into bpb (BPBNO,USERNAME,ID_SUPPLIER,ID_ITEM,QTY,UNIT,BPBDATE,KPNO) 
		select TRANS_NO,USERNAME,ID_SUPPLIER,ID_ITEM,JUMLAH_SATUAN,KODE_SATUAN,TANGGAL_AJU,TGL_DOWNLOAD 
		from upload_tpb where username='$user' 
		and sesi='$sesi' and URAIAN_DOKUMEN='BPB' ";
	insert_log($sql,$user);
	$sql="insert into bppb (BPPBNO,USERNAME,ID_SUPPLIER,ID_ITEM,QTY,UNIT,BPPBDATE,KPNO) 
		select TRANS_NO,USERNAME,ID_SUPPLIER,ID_ITEM,JUMLAH_SATUAN,KODE_SATUAN,TANGGAL_AJU,TGL_DOWNLOAD 
		from upload_tpb where username='$user' 
		and sesi='$sesi' and URAIAN_DOKUMEN='BKB' ";
	insert_log($sql,$user);
	# CALC STOCK
	$sql="select ID_ITEM,MATTYPE from upload_tpb 
		where username='$user' and sesi='$sesi' group by ID_ITEM,MATTYPE";
	$query = mysql_query($sql);
	while($data = mysql_fetch_array($query))
	{	$mattype = $data['MATTYPE'];
		$id_item = $data['ID_ITEM'];
		calc_stock($mattype,$id_item);		
	}
	$sql="insert into upload_tpb_hist (USERNAME,SESI,URAIAN_DOKUMEN,ID_ITEM,ID_SUPPLIER,
		JUMLAH_SATUAN,MATTYPE,TANGGAL_AJU,KODE_SATUAN,TRANS_NO) select USERNAME,SESI,URAIAN_DOKUMEN,
		ID_ITEM,ID_SUPPLIER,JUMLAH_SATUAN,MATTYPE,TANGGAL_AJU,KODE_SATUAN,TRANS_NO 
		from upload_tpb where username='$user' and sesi='$sesi' ";
	insert_log($sql,$user);	
	$sql="delete from upload_tpb where username='$user' and sesi='$sesi'";
	insert_log($sql,$user);	
	$_SESSION['msg'] = "Data Berhasil Disimpan";
	echo "<script>window.location.href='../forms/?mod=1&mode=$mode';</script>";
}
?>