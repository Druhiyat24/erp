<?PHP
include ("conn.php");
include ("fungsi.php");

if (isset($_GET['id'])) { $id_item=$_GET['id']; } else { $id_item=""; }

$txtcurr = strtoupper($_POST['txtcurr']);
$txtnama_bank = strtoupper($_POST['txtnama_bank']);
$txtno_rek = strtoupper($_POST['txtno_rek']);
$txtnama_rek = strtoupper($_POST['txtnama_rek']);
if ($id_item=="")
{	$cek = flookup("count(*)","acc_masterbank","curr='$txtcurr' and nama_bank='$txtnama_bank' and no_rek='$txtno_rek'");
	if ($cek=="0")
	{	$sql = "insert into acc_masterbank (curr,nama_bank,no_rek,nama_rek)
			values ('$txtcurr','$txtnama_bank','$txtno_rek','$txtnama_rek')";
		insert_log($sql,$user);
		echo "<script>
			alert('Data berhasil disimpan');
			window.location.href='masterbank.php';
		</script>";
	}
	else
	{ 	echo "<script>
			alert('Data sudah ada');
			window.location.href='masterbank.php';
		</script>";
	}
}
else
{	$cek = flookup("count(*)","acc_masterbank","curr='$txtcurr' and nama_bank='$txtnama_bank' and no_rek='$txtno_rek'");
	if ($cek=="0")
	{	$sql = "update acc_masterbank set curr='$txtcurr',
			nama_bank='$txtnama_bank',
			no_rek='$txtno_rek',
			nama_rek='$txtnama_rek' where id_bank='$id_item'";
		insert_log($sql,$user);
		echo "<script>
			alert('Data berhasil dirubah');
			window.location.href='masterbank.php';
		</script>";
	}
	else
	{ 	echo "<script>
			alert('Data sudah ada,  tidak bisa diubah');
			window.location.href='masterbank.php';
		</script>";
	}
}
?>