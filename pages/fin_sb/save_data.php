<?PHP
include ("../../include/conn.php");
include ("../forms/fungsi.php");
session_start();
if (isset($_SESSION['username'])) {} else { header("location:../../index.php"); }
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

if (isset($_GET['mod'])) { $mod=$_GET['mod']; } else { $mod=""; }
if (isset($_GET['mode'])) { $mode=$_GET['mode']; } else { $mode=""; }
if (isset($_GET['noid'])) { $id_item=$_GET['noid']; } else { $id_item=""; }
$user=$_SESSION['username'];
$auto_ap_ar=flookup("auto_ap_ar","mastercompany","company!=''");
if($auto_ap_ar=="Y") { include '../forms/insert_ap_ar.php'; }
if ($mode=="AP") 
{ $title="Account Payable";
  $tbl="acc_pay";
  $fld="bpbno"; 
  $tbl_trx = "bpb";
  $fldid = "id_ap";
}
else if ($mode=="AR") 
{ $title="Account Receivable";
  $tbl="acc_rec"; 
  $fld="bppbno";
  $tbl_trx = "bppb";
  $fldid = "id_ar";
}
else if ($mode=="Supplier") 
{ $title="Master Supplier";
  $tbl="mastersupplier"; 
}
else if ($mode=="Customer") 
{ $title="Master Customer";
  $tbl="mastersupplier"; 
}
else { echo "<script>alert('Terjadi kesalahan mode'); window.location.href='index.php'; </script>"; }

if ($mode=="AP" OR $mode=="AR")
{	$txtinv_date = fd($_POST['txtinv_date']);
	$txttrx_no_ori = strtoupper($_POST['txtinv_no']);
	$txttrx_no_ori = explode("|",$txttrx_no_ori);
	if($mode=="AP")
	{	$rstrx=mysql_fetch_array(mysql_query("select * from bpb where invno='$txttrx_no_ori[1]' 
			and bcno='$txttrx_no_ori[2]'"));
		$txttrx_no = $rstrx["bpbno"];
		$txtid_supplier = $rstrx["id_supplier"];
	}
	else
	{	$rstrx=mysql_fetch_array(mysql_query("select * from bppb where invno='$txttrx_no_ori[1]' 
			and bcno='$txttrx_no_ori[2]'"));
		$txttrx_no = $rstrx["bppbno"];
		$txtid_supplier = $rstrx["id_supplier"];
	}
	$txtinv_no = $txttrx_no_ori[1];
	$txtbc_no = $txttrx_no_ori[2];
	$txtno_faktur = strtoupper($_POST['txtno_faktur']);
	#$txtid_supplier = strtoupper($_POST['txtid_supplier']);
	$txtcurr = strtoupper($_POST['txtcurr']);
	$txtamount = strtoupper($_POST['txtamount']);
	$txttt_date = fd($_POST['txttt_date']);
	$txtdue_date = fd($_POST['txtdue_date']);
	if (!isset($_POST['txtpay_date']))
	{ $txtpay_date=""; }
	else
	{ $txtpay_date = fd($_POST['txtpay_date']); }
	if (isset($_POST['txtpay_bank'])) 
	{ $txtpay_bank=strtoupper($_POST['txtpay_bank']); }
	else
	{ $txtpay_bank=""; }
	if(isset($_POST['txtbyrke'])) {$txtbyrke=strtoupper($_POST['txtbyrke']);} else {$txtbyrke="0";}
	if ($id_item!="")
	{	# EDIT
		$sql = "update $tbl set inv_date='$txtinv_date',
			no_faktur='$txtno_faktur', id_supplier='$txtid_supplier',
			curr='$txtcurr', amount='$txtamount', tt_date='$txttt_date',
			due_date='$txtdue_date', pay_date='$txtpay_date', pay_bank='$txtpay_bank',
			byr_ke='$txtbyrke' 
			where $fldid='$id_item'";
		insert_log($sql,$user);
		if($auto_ap_ar=="Y")
		{ if($mode=="AP")
			{	$txtbpbno=flookup("inv_no",$tbl,"$fldid='$id_item'");
				insert_jurnal_paid("AP",$txtbpbno,$user);
			}
			else
			{	$txtbppbno=flookup("inv_no",$tbl,"$fldid='$id_item'");
				insert_jurnal_paid("AR",$txtbppbno,$user);
			}
		}
		echo "<script>
			alert('Data berhasil dirubah');
			window.location.href='../fin/?mod=$mod&mode=$mode';
		</script>";
	}
	else
	{	# ADD NEW
		$cek = flookup("count(*)",$tbl,"$fld='$txttrx_no' and inv_no='$txtinv_no' and bcno='$txtbc_no'");
		if ($cek=="0")
		{	$sql = "insert into $tbl ($fld,inv_date,inv_no,bcno,no_faktur,id_supplier,curr,amount,tt_date,due_date,pay_date,
				pay_bank,byr_ke) values ('$txttrx_no','$txtinv_date','$txtinv_no','$txtbc_no','$txtno_faktur','$txtid_supplier','$txtcurr',
				'$txtamount','$txttt_date','$txtdue_date','$txtpay_date','$txtpay_bank','$txtbyrke')";
			insert_log($sql,$user);
			echo "<script>
				 alert('Data berhasil disimpan');
				 window.location.href='../fin/?mod=$mod&mode=$mode';
			</script>";
		}
		else
		{	echo "<script>
				 alert('Data sudah ada');
				 window.location.href='../fin/?mod=$mod&mode=$mode';
			</script>";
		}
	}
}
else if ($mode=="Supplier" OR $mode=="Customer")
{	$txtSupplier = strtoupper($_POST['txtSupplier']);
	$txtAttn = strtoupper($_POST['txtAttn']);
	$txtPhone = strtoupper($_POST['txtPhone']);
	$txtFax = strtoupper($_POST['txtFax']);
	$txtEmail = strtoupper($_POST['txtEmail']);
	$txtarea = substr(strtoupper($_POST['txtarea']),0,1);
	$txtalamat = strtoupper($_POST['txtalamat']);
	$txtalamat2 = strtoupper($_POST['txtalamat2']);
	$txtnpwp = strtoupper($_POST['txtnpwp']);
	$txtstatus_kb = "";
	$txtcountry = strtoupper($_POST['txtcountry']);
	$txttipe_sup = strtoupper($_POST['txttipe_sup']);
	$txttipe_sup = substr($txttipe_sup,0,1);
	if ($id_item=="")
	{	$cek = flookup("count(*)","mastersupplier","Supplier='$txtSupplier' and tipe_sup='$txttipe_sup'");
		if ($cek=="0")
		{	$sql = "insert into mastersupplier (Supplier,Attn,Phone,Fax,Email,area,alamat,alamat2,npwp,status_kb,country,tipe_sup)
				values ('$txtSupplier','$txtAttn','$txtPhone','$txtFax','$txtEmail','$txtarea','$txtalamat','$txtalamat2','$txtnpwp','$txtstatus_kb','$txtcountry','$txttipe_sup')";
			insert_log($sql,$user);
			echo "<script>
				alert('Data berhasil disimpan');
				window.location.href='master_supcust.php?mode=$mode';
			</script>";
		}
		else
		{	echo "<script>
				alert('Data sudah ada');
				window.location.href='master_supcust.php?mode=$mode';
			</script>";
		}
	}
	else
	{	$cek = flookup("count(*)","bpb","id_supplier='$id_item'");
		if ($cek==0) { $cek = flookup("count(*)","bppb","id_supplier='$id_item'"); }	
		if ($cek>=1)
		{	$sql = "update mastersupplier set Attn='$txtAttn',Phone='$txtPhone',Fax='$txtFax',Email='$txtEmail',
				area='$	txtarea',alamat='$txtalamat',alamat2='$txtalamat2',npwp='$txtnpwp',status_kb='$txtstatus_kb',
				country='$txtcountry' where id_supplier='$id_item'";
			insert_log($sql,$user);
			echo "<script>
				alert('Data berhasil dirubah');
				window.location.href='master_supcust.php?mode=$mode';
			</script>";
		}
		else
		{	$sql = "update mastersupplier set Supplier='$txtSupplier',
				Attn='$txtAttn',Phone='$txtPhone',Fax='$txtFax',Email='$txtEmail',
				area='$txtarea',alamat='$txtalamat',alamat2='$txtalamat2',npwp='$txtnpwp',
				status_kb='$txtstatus_kb',country='$txtcountry',tipe_sup='$txttipe_sup' 
				where id_supplier='$id_item'";
			insert_log($sql,$user);
			echo "<script>
				alert('Data berhasil dirubah');
				window.location.href='master_supcust.php?mode=$mode';
			</script>";
		}
	}
}
?>