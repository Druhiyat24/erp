<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start(); 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$jenis_company=flookup("company","mastercompany","company!=''");
$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}
if (isset($_GET['pro'])) {$pro = $_GET['pro']; } else {$pro = "";}

$txtid_pre_cost = 0;
$txtcost_no = nb($_POST['txtcost_no']);
$txtcost_date = fd($_POST['txtcost_date']);
if (isset($_POST['txtkpno'])) {$txtkpno = nb($_POST['txtkpno']);} else {$txtkpno = "";}
$txtcurr = nb($_POST['txtcurr']);
if (isset($_POST['txtid_smode'])) {$txtid_smode = unfn($_POST['txtid_smode']);} else {$txtid_smode = "";}
if (isset($_POST['txtsmv_min'])) {$txtsmv_min = unfn($_POST['txtsmv_min']);} else {$txtsmv_min = "";}
if (isset($_POST['txtsmv_sec'])) {$txtsmv_sec = unfn($_POST['txtsmv_sec']);} else {$txtsmv_sec = "";}
if (isset($_POST['txtbook_min'])) {$txtbook_min = unfn($_POST['txtbook_min']);} else {$txtbook_min = "";}
if (isset($_POST['txtbook_sec'])) {$txtbook_sec = unfn($_POST['txtbook_sec']);} else {$txtbook_sec = "";}
if (isset($_POST['txtnotes'])) {$txtnotes = nb($_POST['txtnotes']);} else {$txtnotes = "";}
if (isset($_POST['txtvat'])) {$txtvat = nb($_POST['txtvat']);} else {$txtvat = "";}
if (isset($_POST['txtdeal'])) {$txtdeal = nb($_POST['txtdeal']);} else {$txtdeal = "";}
if (isset($_POST['txtga'])) {$txtga = nb($_POST['txtga']);} else {$txtga = "";}
$txtcfm = nb($_POST['txtcfm']);
if (isset($_POST['txtcomm'])) {$txtcomm = nb($_POST['txtcomm']);} else {$txtcomm = "";}
if (isset($_POST['txtdeldate'])) {$txtdeldate = fd($_POST['txtdeldate']);} else {$txtdeldate = "";}
if ($jenis_company=="VENDOR LG") { $txtdeldate=$txtcost_date; }
if (isset($_FILES['txtattach_file']))
{	$nama_file = $_FILES['txtattach_file']['name'];
	$tmp_file = $_FILES['txtattach_file']['tmp_name'];
	$path = "upload_files/costing/".$nama_file;
	move_uploaded_file($tmp_file, $path);
}
else
{ $nama_file=""; }
$txtattach_file = $nama_file;
$id_buyer=$_POST['txtid_buyer'];
$id_product=$_POST['txtid_product'];
$styleno=nb($_POST['txtstyle']);
if (isset($_POST['txtqty'])) {$qty=unfn($_POST['txtqty']);} else {$qty="";}
if (isset($_POST['txtunit'])) {$txtunit=nb($_POST['txtunit']);} else {$txtunit="";}
$status=nb($_POST['txtstatus']);
$status_order=nb($_POST['txtstatus_order']);
if ($txtcost_no=="")
{	$date=fd($txtcost_date);
	$cri2="DEV/".date('my',strtotime($date));
	$txtcost_no=urutkan_inq("DEV-".date('Y',strtotime($date)),$cri2); 
	$sql="select supplier_code from mastersupplier 
		where id_supplier='$id_buyer'"; 
	$rs=mysql_fetch_array(mysql_query($sql));
	$kd_buyer=$rs['supplier_code'];
	$cri2=$kd_buyer."/".date('my',strtotime($date));
	$txtkpno=urutkan_inq($kd_buyer."-".date('Y',strtotime($date)),$cri2); 
}
$cek = flookup("count(*)","act_development","cost_no='$txtcost_no'");
if ($cek=="0")
{	$dateinput = date("Y-m-d H:i:s");
	$sql = "insert into act_development (id_pre_cost,cost_no,cost_date,
		kpno,id_smode,smv_min,smv_sec,book_min,book_sec,notes,deldate,
		attach_file,id_buyer,id_product,styleno,qty,status,status_order,
		username,curr,vat,deal_allow,ga_cost,unit,cfm_price,comm_cost,dateinput)
		values ('$txtid_pre_cost','$txtcost_no','$txtcost_date',
		'$txtkpno','$txtid_smode','$txtsmv_min','$txtsmv_sec',
		'$txtbook_min','$txtbook_sec','$txtnotes','$txtdeldate',
		'$txtattach_file','$id_buyer','$id_product','$styleno',
		'$qty','$status','$status_order','$user','$txtcurr','$txtvat','$txtdeal','$txtga',
		'$txtunit','$txtcfm','$txtcomm','$dateinput')";
	insert_log($sql,$user);
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	$id=flookup("id","act_development","cost_no='$txtcost_no'");
	if ($pro=="Copy")
	{	$sql="insert into act_development_mat 
		(id_act_cost,id_item,price,cons,unit,allowance,material_source,jenis_rate) 
		select '$id',id_item,price,cons,unit,allowance,material_source,jenis_rate 
		from act_development_mat where id_act_cost='$id_item'";
		insert_log($sql,$user);
		$sql="insert into act_development_mfg 
		(id_act_cost,id_item,smv,price,cons,unit,allowance,material_source,jenis_rate) 
		select '$id',id_item,smv,price,cons,unit,allowance,material_source,jenis_rate 
		from act_development_mfg where id_act_cost='$id_item'";
		insert_log($sql,$user);
		$sql="insert into act_development_oth 
		(id_act_cost,id_item,smv,price,cons,unit,allowance,material_source,jenis_rate) 
		select '$id',id_item,smv,price,cons,unit,allowance,material_source,jenis_rate 
		from act_development_oth where id_act_cost='$id_item'";
		insert_log($sql,$user);
	}
	echo "<script>
		 window.location.href='../marketting/?mod=$mod&id=$id';
	</script>";
}
else
{	$cek = flookup("count(*)","so_dev so inner join act_development ac on so.id_cost=ac.id","cost_no='$txtcost_no'");
	$dateskrg=date('Y-m-d'); 
	$cekunl = flookup("cost_no","unlock_cost","cost_no='$txtcost_no' and DATE_ADD(unlock_date, INTERVAL 2 DAY)>'$dateskrg'");
	if ($cek=="0" or $cekunl!="")
	{	if ($txtattach_file!="") {$sql_attach=",attach_file='$txtattach_file'";} else {$sql_attach="";}
		$sql = "update act_development set id_smode='$txtid_smode', notes='$txtnotes', qty='$qty',curr='$txtcurr',status='$status'
			,status_order='$status_order',vat='$txtvat',deal_allow='$txtdeal',cfm_price='$txtcfm',comm_cost='$txtcomm'
			,ga_cost='$txtga' $sql_attach
			,smv_min='$txtsmv_min',smv_sec='$txtsmv_sec',book_min='$txtbook_min',book_sec='$txtbook_sec'
			,unit='$txtunit',id_product='$id_product',styleno='$styleno'
			,deldate='$txtdeldate' where cost_no='$txtcost_no'"; 
		insert_log($sql,$user);
		$_SESSION['msg'] = 'Data Berhasil Dirubah';
	}
	else
	{	$_SESSION['msg'] = 'XData Tidak Bisa Dirubah Karena Sudah Ada SO'; }
	echo "<script>
		 window.location.href='../marketting/?mod=1X';
	</script>";
}
?>