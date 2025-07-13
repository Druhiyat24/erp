<?PHP
include ("conn.php");
include ("fungsi.php");

if (isset($_GET['id'])) { $id_item=$_GET['id']; } else { $id_item=""; }

$sql="delete from acc_masterbank where  id_bank='$id_item'";
insert_log($sql,$user);
$cek = flookup("count(*)","acc_pay","pay_bank='$id_item'");
if ($cek=="0") { $cek = flookup("count(*)","acc_rec","pay_bank='$id_item'"); }
if ($cek=="0")
{	$sql = "delete from acc_masterbank where id_bank='$id_item'";
	insert_log($sql,$user);
	echo "<script>
		alert('Data berhasil dihapus');
		window.location.href='masterbank.php';
	</script>";
}
else
{ 	echo "<script>
		alert('Data tidak bisa dihapus, karena sudah ada transaksi');
		window.location.href='masterbank.php';
	</script>";
}
?>