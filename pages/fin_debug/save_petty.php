<?PHP
include ("../../include/conn.php");
include ("../forms/fungsi.php");

if (isset($_GET['id'])) { $id_item=$_GET['id']; } else { $id_item=""; }
$mode="";
$txtjenis_trans = strtoupper($_POST['txtjenis_trans']);
$txttanggal_trans = fd(strtoupper($_POST['txttanggal_trans']));
$txtketerangan = strtoupper($_POST['txtketerangan']);
$txtcurr = strtoupper($_POST['txtcurr']);
$txtamount = strtoupper($_POST['txtamount']);
$cek = flookup("count(*)","acc_pettycash","jenis_trans='$txtjenis_trans' and tanggal_trans='$txttanggal_trans' 
	and keterangan='$txtketerangan'");
if ($cek=="0" and $id_item=="")
{	$sql = "insert into acc_pettycash (jenis_trans,tanggal_trans,keterangan,curr,amount)
		values ('$txtjenis_trans','$txttanggal_trans','$txtketerangan','$txtcurr','$txtamount')";
	insert_log($sql,$user);
	echo "<script>
		 alert('Data berhasil disimpan');
		 window.location.href='../fin/?mod=3&mode=$mode';
	</script>";
}
elseif ($cek=="0" and $id_item!="")
{	$sql = "update acc_pettycash set jenis_trans='$txtjenis_trans',
		tanggal_trans='$txttanggal_trans',keterangan='$txtketerangan',
		curr='$txtcurr',amount='$txtamount' where id='$id_item'";
	insert_log($sql,$user);
	echo "<script>
		 alert('Data berhasil dirubah');
		 window.location.href='../fin/?mod=3&mode=$mode';
	</script>";
}
else
{	echo "<script>
		 alert('Data sudah ada');
		 window.location.href='../fin/?mod=3&mode=$mode';
	</script>";
}
?>