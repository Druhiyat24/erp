<?php 
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mode=$_GET['mode'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}
if (isset($_GET['mod'])) {$mod = $_GET['mod']; } else {$mod = "";}
$auto_coa_supp=flookup("auto_coa_supp","mastercompany","company!=''");
if($auto_coa_supp=="Y")
{ include 'journal_interface.php'; }
if ($mode=="Bahan_Baku" OR $mode=="Scrap" OR $mode=="Mesin" OR $mode=="WIP") 
{	$txtmattype = nb($_POST['txtmattype']);
	$txtmatclass = nb($_POST['txtmatclass']);
	$txtitemdesc = nb($_POST['txtitemdesc']);
	$txtcolor = nb($_POST['txtcolor']);
	$txtsize = nb($_POST['txtsize']);
	if (isset($_POST['txtstock_card'])) {$txtstock_card = nb($_POST['txtstock_card']);} else {$txtstock_card = "";}
	if (isset($_POST['txtbase_supplier'])) {$txtbase_supplier = nb($_POST['txtbase_supplier']);} else {$txtbase_supplier = "";}
	if (isset($_POST['txtbase_curr'])) {$txtbase_curr = nb($_POST['txtbase_curr']);} else {$txtbase_curr = "";}
	if (isset($_POST['txtbase_price'])) {$txtbase_price = nb($_POST['txtbase_price']);} else {$txtbase_price = "0";}
	$txtgoods_code = nb($_POST['txtgoods_code']);
	if (isset($_POST['txtbrand'])) {$txtbrand=nb($_POST['txtbrand']);} else {$txtbrand="";}
	if (isset($_POST['txtsn'])) {$txtsn=nb($_POST['txtsn']);} else {$txtsn="";}
	if (isset($_POST['txtminst'])) {$txtminst=nb($_POST['txtminst']);} else {$txtminst="0";}
	if (isset($_POST['txtthn_beli'])) {$txtthn_beli=nb($_POST['txtthn_beli']);} else {$txtthn_beli="";}
	if (isset($_POST['txtgen_code'])) {$txtgen_code=nb($_POST['txtgen_code']);} else {$txtgen_code="0";}
	if (isset($_POST['txthscode'])) {$txthscode=nb($_POST['txthscode']);} else {$txthscode="";}
	if (isset($_POST['txtgen_code_odo'])) {$txtgoods_code_odo=$_POST['txtgen_code_odo'];} else {$txtgoods_code_odo="";}
	$txtnotes=nb($_POST['txtnotes']);
	if (isset($_FILES['txtfile']))
	{	$nama_file = $_FILES['txtfile']['name'];
		$tmp_file = $_FILES['txtfile']['tmp_name'];
		$path = "upload_files/item/".$nama_file;
		move_uploaded_file($tmp_file, $path);
	}
	else
	{ $nama_file=""; }
	if ($id_item=="")
	{ if ($txtgen_code!="" AND $txtgen_code!="0")
		{	$cek = flookup("count(*)","masteritem","id_gen='$txtgen_code'"); }
		else
		{	$cek = flookup("count(*)","masteritem","mattype='$txtmattype' and itemdesc='$txtitemdesc' and 
				color='$txtcolor' and size='$txtsize' and goods_code='$txtgoods_code'");
		}
		if ($cek=="0")
		{	$sql = "insert into masteritem (id_item_odo,mattype,matclass,itemdesc,color,
				size,stock_card,base_supplier,base_curr,base_price,goods_code,brand,file_gambar,sn,min_stock,thn_beli,id_gen,hscode,notes)
				values ('$txtgoods_code_odo','$txtmattype','$txtmatclass','$txtitemdesc','$txtcolor',
				'$txtsize','$txtstock_card','$txtbase_supplier','$txtbase_curr','$txtbase_price','$txtgoods_code','$txtbrand',
				'$nama_file','$txtsn','$txtminst','$txtthn_beli','$txtgen_code','$txthscode','$txtnotes')";
			insert_log($sql,$user);
			$_SESSION['msg'] = 1;
			echo "<script>window.location.href='index.php?mod=2&mode=$mode';</script>";
		}
		else
		{	$_SESSION['msg'] = "XData Sudah Ada"." ".$txtgen_code;
			echo "<script>window.location.href='index.php?mod=2&mode=$mode';</script>"; 
		}
	}
	else
	{	$cek = flookup("count(*)","bpb","id_item='$id_item' and bpbno not like 'FG%'");
		if ($cek==0) { $cek = flookup("count(*)","bppb","id_item='$id_item' and bppbno not like 'SJ-FG%'"); }	
		if ($cek>=1)
		{	$sql = "update masteritem set id_item_odo='$txtgoods_code_odo',goods_code='$txtgoods_code',
				itemdesc='$txtitemdesc',
				color='$txtcolor',
				size='$txtsize',matclass='$txtmatclass',
				stock_card='$txtstock_card',base_supplier='$txtbase_supplier',base_curr='$txtbase_curr'
				,base_price='$txtbase_price',
				brand='$txtbrand',file_gambar='$nama_file',sn='$txtsn',min_stock='$txtminst',
				thn_beli='$txtthn_beli',hscode='$txthscode',notes='$txtnotes' where id_item='$id_item'";
			insert_log($sql,$user);
		}
		else
		{	$sql = "update masteritem set id_item_odo='$txtgoods_code_odo',mattype='$txtmattype',
				matclass='$txtmatclass',
				itemdesc='$txtitemdesc',
				color='$txtcolor',
				size='$txtsize',
				stock_card='$txtstock_card',
				base_supplier='$txtbase_supplier',base_curr='$txtbase_curr',
				base_price='$txtbase_price',
				goods_code='$txtgoods_code',brand='$txtbrand',
				file_gambar='$nama_file',sn='$txtsn',min_stock='$txtminst',thn_beli='$txtthn_beli',hscode='$txthscode'
				where id_item='$id_item'";
			insert_log($sql,$user);
		}
		$_SESSION['msg'] = 2;
		echo "<script>window.location.href='../forms/?mod=2L&mode=$mode';</script>";
	}
}
else if ($mode=="Supplier" OR $mode=="Customer" OR $mode=="Gudang")
{	$txtSupplier = nb($_POST['txtSupplier']);
	$txtAttn = nb($_POST['txtAttn']);
	$txtAttn2 = nb($_POST['txtAttn2']);
	$txtAttn3 = nb($_POST['txtAttn3']);
	$txtAttn4 = nb($_POST['txtAttn4']);
	$txtPhone = nb($_POST['txtPhone']);
	$txtFax = nb($_POST['txtFax']);
	$txtEmail = nb($_POST['txtEmail']);
	$txtarea = substr(nb($_POST['txtarea']),0,1);
	if(isset($_POST['txtvencat'])) { $txtvencat=$_POST['txtvencat']; } else { $txtvencat="NG"; }
	if (isset($_POST['txtfas'])) {$txtfas = nb($_POST['txtfas']);} else {$txtfas = "";}
	$txtalamat = nb($_POST['txtalamat']);
	$txtalamat2 = nb($_POST['txtalamat2']);
	$txtnpwp = nb($_POST['txtnpwp']);
	$txtstatus_kb = "";
	$txtcountry = nb($_POST['txtcountry']);
	$txttipe_sup = nb($_POST['txttipe_sup']);
	$txttipe_sup = substr($txttipe_sup,0,1);
	$txtsupplier_code = nb($_POST['txtsupplier_code']);
	$txtshort_name = nb($_POST['txtshort_name']);
	$txtzip_code = nb($_POST['txtzip_code']);
	$txtgroup_name = nb($_POST['txtgroup_name']);
	$txtproduct_name = nb($_POST['txtproduct_name']);
	$txtmoq = nb($_POST['txtmoq']);
	$txtlead_time = nb($_POST['txtlead_time']);
	$txtmoq_lead_time = nb($_POST['txtmoq_lead_time']);
	$txtterms_of_pay = nb($_POST['txtterms_of_pay']);
	if(isset($_POST['tipe_buyer'])) { $tipe_buyer='Y'; } else { $tipe_buyer=""; }
	if(isset($_POST['tipe_agent'])) { $tipe_agent='Y'; } else { $tipe_agent=""; }
	if($auto_coa_supp=="Y")
	{	$txtcoa = create_supplier_coa($txtsupplier_code.' - '.$txtSupplier); }
	else
	{	$txtcoa = nb($_POST['txtcoa']); }
	if (isset($_POST['txtpkp'])) {$txtpkp = nb($_POST['txtpkp']);} else {$txtpkp = "";}
	if ($id_item=="")
	{	$cek = flookup("count(*)","mastersupplier","Supplier='$txtSupplier' and tipe_sup='$txttipe_sup'");
		if ($cek=="0")
		{	$sql = "insert into mastersupplier (Supplier,Attn,Phone,Fax,Email,area,vendor_cat,jenis_fasilitas,alamat,alamat2,npwp,status_kb,country,tipe_sup
				,supplier_code,short_name,zip_code,group_name,product_name,moq,lead_time,moq_lead_time,terms_of_pay,pkp,id_coa
				,Attn2,Attn3,Attn4,tipe_buyer,tipe_agent)
				values ('$txtSupplier','$txtAttn','$txtPhone','$txtFax','$txtEmail'
				,'$txtarea','$txtvencat','$txtfas','$txtalamat','$txtalamat2','$txtnpwp','$txtstatus_kb','$txtcountry','$txttipe_sup'
				,'$txtsupplier_code','$txtshort_name','$txtzip_code','$txtgroup_name','$txtproduct_name','$txtmoq','$txtlead_time','$txtmoq_lead_time',
				'$txtterms_of_pay','$txtpkp','$txtcoa'
				,'$txtAttn2','$txtAttn3','$txtAttn4','$tipe_buyer','$tipe_agent')";
			insert_log($sql,$user);
			$_SESSION['msg'] = 1;
		}
		else
		{	$_SESSION['msg'] = 3;
		}
		if ($mod==20 OR $mod==21)
		{	echo "<script>window.location.href='../master/?mod=$mod&mode=$mode';</script>"; }
		else
		{	echo "<script>window.location.href='../forms/?mod=$mod&mode=$mode';</script>"; }
	}
	else
	{	$cek = flookup("count(*)","mastersupplier","Supplier='$txtSupplier' and tipe_sup='$txttipe_sup'");
		if($cek=="0")
		{	$cek = flookup("count(*)","bpb","id_supplier='$id_item'");
			if ($cek==0) { $cek = flookup("count(*)","bppb","id_supplier='$id_item'"); }	
			if ($cek==0) { $cek = flookup("count(*)","act_costing","id_buyer='$id_item'"); }	
			if ($cek>=1)
			{	$sql = "update mastersupplier set id_terms='$txtterms_of_pay',
						product_name = '$txtproduct_name'
						,Supplier='$txtSupplier',id_coa='$txtcoa',Attn='$txtAttn',Phone='$txtPhone',Fax='$txtFax',Email='$txtEmail',
					Attn2='$txtAttn2',Attn3='$txtAttn3',Attn4='$txtAttn4',
					area='$txtarea',vendor_cat='$txtvencat',jenis_fasilitas='$txtfas',alamat='$txtalamat',alamat2='$txtalamat2',npwp='$txtnpwp',status_kb='$txtstatus_kb',
					country='$txtcountry',pkp='$txtpkp',tipe_buyer='$tipe_buyer',tipe_agent='$tipe_agent' where id_supplier='$id_item'";
				insert_log($sql,$user);
				$_SESSION['msg'] = 2;
				#echo "<script>window.location.href='index.php?mod=4&mode=$mode';</script>";
			}
			else
			{	$sql = "update mastersupplier set id_terms='$txtterms_of_pay'
					,product_name = '$txtproduct_name'
					,id_coa='$txtcoa',supplier_code='$txtsupplier_code',
					Supplier='$txtSupplier',
					Attn='$txtAttn',Phone='$txtPhone',Fax='$txtFax',Email='$txtEmail',
					Attn2='$txtAttn2',Attn3='$txtAttn3',Attn4='$txtAttn4',
					area='$txtarea',vendor_cat='$txtvencat',jenis_fasilitas='$txtfas',alamat='$txtalamat',alamat2='$txtalamat2',npwp='$txtnpwp',
					status_kb='$txtstatus_kb',country='$txtcountry',tipe_sup='$txttipe_sup',pkp='$txtpkp',tipe_buyer='$tipe_buyer',tipe_agent='$tipe_agent' 
					where id_supplier='$id_item'";
				insert_log($sql,$user);
				$_SESSION['msg'] = 2;
				#echo "<script>window.location.href='index.php?mod=4&mode=$mode';</script>";
			}
		}
		else
		{	$sql = "update mastersupplier set id_terms='$txtterms_of_pay'
				,product_name = '$txtproduct_name'
				,id_coa='$txtcoa',supplier_code='$txtsupplier_code',
				Attn='$txtAttn',Phone='$txtPhone',Fax='$txtFax',Email='$txtEmail',
				Attn2='$txtAttn2',Attn3='$txtAttn3',Attn4='$txtAttn4',
				area='$txtarea',vendor_cat='$txtvencat',jenis_fasilitas='$txtfas',alamat='$txtalamat',alamat2='$txtalamat2',npwp='$txtnpwp',
				status_kb='$txtstatus_kb',country='$txtcountry',tipe_sup='$txttipe_sup',pkp='$txtpkp',tipe_buyer='$tipe_buyer',tipe_agent='$tipe_agent' 
				where id_supplier='$id_item'";
			insert_log($sql,$user);
			$_SESSION['msg'] = 2;
		}
		if ($mod==20 OR $mod==21)
		{	echo "<script>window.location.href='../master/?mod=$mod&mode=$mode';</script>"; }
		else
		{	echo "<script>window.location.href='../forms/?mod=$mod&mode=$mode';</script>"; }
	}
}
elseif ($mode=="FG") 
{	$txtStyleno = nb($_POST['txtStyleno']);
	$txtBuyerno = nb($_POST['txtBuyerno']);
	$txtgoods_code = nb($_POST['txtgoods_code']);
	$txtitemname = nb($_POST['txtitemname']);
	$txtColor = nb($_POST['txtColor']);
	$txtSize = nb($_POST['txtSize']);
	$txtbarcode = nb($_POST['txtbarcode']);
	$txtkpno = nb($_POST['txtkpno']);
	$txtdeldate = fd($_POST['txtdeldate']);
  $txtdest = nb($_POST['txtdest']);
  $txtwhs_code = nb($_POST['txtwhs_code']);
  $txtqty = $_POST['txtqty']; 
	if (isset($_POST['txtsatuan'])) { $txtsatuan = $_POST['txtsatuan']; } else { $txtsatuan = ""; } 
	if ($id_item=="")
	{ 	$cek = flookup("count(*)","masterstyle","Styleno='$txtStyleno' and 
				goods_code='$txtgoods_code' and itemname='$txtitemname' and Color='$txtColor' 
				and Size='$txtSize' and kpno='$txtkpno' and Buyerno='$txtBuyerno'
				and deldate='$txtdeldate' and country='$txtdest'");
		if ($cek=="0")
		{	$sql = "insert into masterstyle (Styleno,Buyerno,goods_code,itemname,Color,Size,barcode,kpno
				,deldate,country,qty,unit)
				values ('$txtStyleno','$txtBuyerno','$txtgoods_code','$txtitemname','$txtColor','$txtSize',
				'$txtbarcode','$txtkpno','$txtdeldate','$txtdest','$txtqty','$txtsatuan')";
			insert_log($sql,$user);
			$_SESSION['msg'] = 1;
			echo "<script>window.location.href='index.php?mod=3&mode=$mode';</script>";
		}
		else
		{	$_SESSION['msg'] = 3;
			echo "<script>window.location.href='index.php?mod=3&mode=$mode';</script>";
		}
	}
	else
	{	$cek = flookup("count(*)","bpb","id_item='$id_item' and bpbno like 'FG%'");
		if ($cek==0) { $cek = flookup("count(*)","bppb","id_item='$id_item' and bppbno like 'SJ-FG%'"); }	
		if ($cek>=1)
		{	$sql = "update masterstyle set itemname='$txtitemname',whs_code='$txtwhs_code',Buyerno='$txtBuyerno',barcode='$txtbarcode',KPNo='$txtkpno',unit='$txtsatuan' where id_item='$id_item'";
			insert_log($sql,$user);
		}
		else
		{	$sql = "update masterstyle set itemname='$txtitemname',whs_code='$txtwhs_code',Styleno='$txtStyleno',
				Buyerno='$txtBuyerno',
				goods_code='$txtgoods_code',
				itemname='$txtitemname',
				Color='$txtColor',
				Size='$txtSize',
				barcode='$txtbarcode',KPNo='$txtkpno'
				,deldate='$txtdeldate',country='$txtdest',qty='$txtqty',unit='$txtsatuan' where id_item='$id_item'";
			insert_log($sql,$user);
		}
		$_SESSION['msg'] = 2;
		echo "<script>window.location.href='../forms/?mod=3L&mode=$mode';</script>";
	}
}
?>