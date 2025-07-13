<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
$mode="";
$auto_copy_costing_in_so=flookup("auto_copy_costing_in_so","mastercompany","company!=''");

if (isset($_GET['id'])) {$id_so = $_GET['id']; } else {$id_so = "";}
if (isset($_GET['pro'])) {$pro = $_GET['pro']; } else {$pro = "";}

$txtid_cost = nb($_POST['txtid_cost']);
$txtbuyerno = nb($_POST['txtbuyerno']);
$txtso_no = nb($_POST['txtso_no']);
$txtso_date = fd($_POST['txtso_date']);
$txtdeldate = fd($_POST['txtdeldate']);
$txtqty = nb($_POST['txtqty']);
if (isset($_POST['txtseason'])) {$txtseason = nb($_POST['txtseason']);} else {$txtseason = "";}
$txtunit = nb($_POST['txtunit']);
$txtcurr = nb($_POST['txtcurr']);
$txtfob = nb($_POST['txtfob']);
$txttax = nb($_POST['txttax']);
if (isset($_FILES['txtfile']))
{ $nama_file = $_FILES['txtfile']['name'];
  $tmp_file = $_FILES['txtfile']['tmp_name'];
  $path = "upload_files/so/".$nama_file;
  move_uploaded_file($tmp_file, $path);
}
else
{ $nama_file=""; }
if (($txtso_no=="" and $id_so=="") or ($txtso_no=="" and $id_so!="" and $pro=="Copy"))
{	$date=fd($txtso_date);
	$cri2="SO/".date('my',strtotime($date));
	$txtso_no=urutkan_inq("SO-".date('Y',strtotime($date)),$cri2); 
	if($auto_copy_costing_in_so=="Y")
	{	$dateinput = date("Y-m-d H:i:s");
		$date=fd($txtso_date);
		$cri2="CST/".date('my',strtotime($date));
		$txtcost_no=urutkan_inq("CST-".date('Y',strtotime($date)),$cri2);
		$id_buyer=flookup("id_buyer","act_costing","id='$txtid_cost'");
		$kd_buyer=flookup("supplier_code","mastersupplier","id_supplier='$id_buyer'");
		$cri2=$kd_buyer."/".date('my',strtotime($date));
		$txtkpno=urutkan_ws($kd_buyer."-".date('Y',strtotime($date)),$cri2);
		$sql="insert into act_costing (id_pre_cost,cost_no,cost_date,kpno,id_smode,smv_min,smv_sec,book_min,book_sec,notes,deldate,attach_file,status,status_order,id_buyer,id_product,styleno,qty,username,curr,vat,deal_allow,app1,app1_by,app1_date,app2,unit,dateinput,ga_cost,cfm_price,comm_cost,aktif) 
			select id_pre_cost,'$txtcost_no','".fd($txtso_date)."','$txtkpno',id_smode,smv_min,smv_sec,book_min,book_sec,notes,deldate,attach_file,status,status_order,id_buyer,id_product,styleno,qty,username,curr,vat,deal_allow,app1,app1_by,app1_date,app2,unit,'$dateinput',ga_cost,cfm_price,comm_cost,aktif 
			from act_costing where id='$txtid_cost'";
		insert_log($sql,$user);
		$txtid_cost_new=flookup("id","act_costing","cost_no='$txtcost_no'");
		$sql="insert into act_costing_mat (id_act_cost,id_item,price,cons,unit,allowance,material_source,jenis_rate) 
			select '$txtid_cost_new',id_item,price,cons,unit,allowance,material_source,jenis_rate 
			from act_costing_mat where id_act_cost='$txtid_cost'";
		insert_log($sql,$user);
		$sql="insert into act_costing_mfg (id_act_cost,id_item,price,cons,unit,allowance,material_source,jenis_rate) 
			select '$txtid_cost_new',id_item,price,cons,unit,allowance,material_source,jenis_rate 
			from act_costing_mfg where id_act_cost='$txtid_cost'";
		insert_log($sql,$user);
		$sql="insert into act_costing_oth (id_act_cost,id_item,price,cons,unit,allowance,material_source,jenis_rate) 
			select '$txtid_cost_new',id_item,price,cons,unit,allowance,material_source,jenis_rate 
			from act_costing_oth where id_act_cost='$txtid_cost'";
		insert_log($sql,$user);
		$sql="update act_costing set aktif='N' where id='$txtid_cost'";
		insert_log($sql,$user);
		$txtid_cost=$txtid_cost_new;
	}
}
#$cek = flookup("count(*)","so","id_cost='$txtid_cost'");
$cek = 0; # 1 Act Costing Bisa Beberapa SO
if (($cek=="0" and $id_so=="") or ($cek=="0" and $id_so!="" and $pro=="Copy"))
{	$sql = "insert into so (id_cost,buyerno,so_no,so_date,qty,unit,curr,fob,nm_file,username,tax,id_season)
		values ('$txtid_cost','$txtbuyerno','$txtso_no','$txtso_date','$txtqty','$txtunit','$txtcurr','$txtfob',
		'$nama_file','$user','$txttax','$txtseason')";
	insert_log($sql,$user);
	if ($id_so!="" and $pro=="Copy")
	{	$id_so_new=flookup("id","so","so_no='$txtso_no'");
		$sql="insert into so_det (id_so,dest,color,size,qty,unit,sku,barcode,notes) 
			select '$id_so_new',dest,color,size,qty,unit,sku,barcode,notes 
			from so_det where id_so='$id_so'";
		insert_log($sql,$user);
	}
	$id_so_new=flookup("id","so","so_no='$txtso_no'");
	$sql="insert into so_det (id_so,deldate_det,qty,unit,price) 
		values ('$id_so_new','$txtdeldate','$txtqty','$txtunit','$txtfob')";
	insert_log($sql,$user);
	$cri2="JO/".date('my',strtotime($txtso_date));
	$jo_no=urutkan_inq("JO-".date('Y',strtotime($txtso_date)),$cri2); 
	$sql = "insert into jo (jo_no,jo_date,username) 
		values ('$jo_no','".fd($txtso_date)."','$user')";
	insert_log($sql,$user);
	$sql = "update act_costing set deldate='$txtdeldate' where id='$txtid_cost'";
	insert_log($sql,$user);
	$id_jo=flookup("id","jo","jo_no='$jo_no'");
	$sql = "insert into jo_det (id_jo,id_so) 
		values ('$id_jo','$id_so_new')";
	insert_log($sql,$user);
	$sql="select s.* 
		from jo_det a inner join so_det s 
		on a.id_so=s.id_so where 
		id_jo='$id_jo' ";
  $rs=mysql_query($sql);
  while($data=mysql_fetch_array($rs))
  {	$sql2="select * from act_costing_mat where id_act_cost='$txtid_cost'";
		$rs2=mysql_query($sql2);
	  while($data2=mysql_fetch_array($rs2))
	  {	$id_supp1=flookup("id_supplier","bom_jo_item","id_item='$data2[id_item]'
	  		and id_supplier!='' order by id desc limit 1");
			$id_supp2=flookup("id_supplier2","bom_jo_item","id_item='$data2[id_item]'
	  		and id_supplier2!='' order by id desc limit 1");
			$sql="insert into bom_jo_item (id_jo,id_so_det,id_item,cons,unit,username,status,id_supplier,
				id_supplier2)
	  		values ('$id_jo','$data[id]','$data2[id_item]','$data2[cons]',
	  		'$data2[unit]','$user','M','$id_supp1','$id_supp2')";
	  	insert_log($sql,$user);
	  }
	  $sql3="select * from act_costing_mfg where id_act_cost='$txtid_cost'";
		$rs3=mysql_query($sql3);
	  while($data3=mysql_fetch_array($rs3))
	  {	$sql="insert into bom_jo_item (id_jo,id_so_det,id_item,cons,unit,username,status)
	  		values ('$id_jo','$data[id]','$data3[id_item]','$data3[cons]',
	  		'$data3[unit]','$user','P')";
	  	insert_log($sql,$user);
	  }
	}
	$_SESSION['msg'] = 'Data Berhasil Disimpan';
	$id=flookup("id","so","so_no='$txtso_no'");
	echo "<script>
		 window.location.href='../marketting/?mod=7L';
	</script>";
}
else
{	$cek=flookup("count(*)","jo_det","id_so='$id_so'");
	$dateskrg=date('Y-m-d');
	$cek2=flookup("so_no","unlock_so","id_so='$id_so' and DATE_ADD(unlock_date, INTERVAL 2 DAY)>'$dateskrg'");
	if ($cek!="0" and $cek2=="")
	{	$_SESSION['msg'] = 'XData Tidak Bisa Dirubah Karena Sudah Dibuat Worksheet';	}
	else
	{	$sql = "update so set buyerno='$txtbuyerno',
			qty='$txtqty',unit='$txtunit',curr='$txtcurr',fob='$txtfob',username='$user',
			tax='$txttax',id_season='$txtseason'
			where id='$id_so'";
		insert_log($sql,$user);
		$sql = "update so_det set qty='$txtqty'where id_so='$id_so'";
		insert_log($sql,$user);
		$_SESSION['msg'] = 'Data Berhasil Dirubah';
	}
	echo "<script>
		 window.location.href='../marketting/?mod=7L';
	</script>";
}
?>