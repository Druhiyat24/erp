<?PHP
include "../../include/conn.php";
include "fungsi.php";
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

if (isset($_GET['parfromv']))
{
	$rpt = "wip";
	header("Content-type: application/octet-stream"); 
	header("Content-Disposition: attachment; filename=$rpt.xls");//ganti nama sesuai keperluan 
	header("Pragma: no-cache"); 
	header("Expires: 0");
}

$sql = mysql_query ("select * from mastercompany");
$rs = mysql_fetch_array($sql);
$nm_company= $rs['company'];

$user = $_SESSION['username'];
$sesi = $_SESSION['sesi'];

if (isset($_GET['parfromv']))
{	$tglf = $_GET['parfrom'];
	$perf = date('d F Y', strtotime($tglf));
	$tglt = $_GET['parto'];
	$pert = date('d F Y', strtotime($tglt));
}
else
{	$tglf = fd($_POST['txtfrom']);
	$perf = date('d F Y', strtotime($tglf));
	$tglt = fd($_POST['txtto']);
	$pert = date('d F Y', strtotime($tglt));
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Lap. Posisi Barang Dalam Proses (WIP)</title>
<style type="text/css">
<!--
.style1 {font-family: Tahoma}
body,td,th {
	font-family: Tahoma;
}
.style2 {font-size: small}
.style4 {font-size: x-small}
-->
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body>
<table width="420" border="0">
  <tr>
    <td>KAWASAN BERIKAT <?PHP echo strtoupper($nm_company);?></td>
  </tr>
  <tr>
    <td><span class="style1">C. LAPORAN POSISI BARANG DALAM PROSES (WIP)</span></td>
  </tr>
  <tr>
    <td><span class="style1"><?PHP echo "PERIODE $perf To $pert";?></span></td>
  </tr>
</table>
<?PHP echo "<a href='indexa.php?uid=$user&sesi=$sesi&mode='>Back</a>"; echo "&nbsp&nbsp&nbsp&nbsp"; 
	echo "<a class='btn btn-info btn-sm' href='tampil_wip.php?uid=$user&sesi=$sesi&parfrom=$tglf&parto=$tglt&parfromv=$perf&partov=$pert&rptid=wip'>Save To Excel</a>";
?>
<table width="708" border="1">
  <tr class="style1">
	<td width="38" class="style1 style4">NO.</td>
    <td width="167" nowrap><span class="style4">KODE BARANG</span></td>
    <td width="133" nowrap><span class="style4">NAMA BARANG</span></td>
    <td width="49"><span class="style4">SAT</span></td>
    <td width="91"><span class="style4">JUMLAH</span></td>
    <td width="190" nowrap><span class="style4">KETERANGAN</span></td>
  </tr>
  	<?PHP
  	if ($nm_company=="PT. Seyang Indonesia") 
		{
			$grup = "s.itemdesc";
			$sql = "drop table if exists wip_new";
			mysql_query ($sql);
			$sql = "create table wip_new select kpno,matclass,itemdesc,sum(qty) QtySet,0 QtySetFG,unit from bpb a inner join masteritem s on a.id_item=s.id_item 
				inner join mastersupplier d on a.id_supplier=d.id_supplier where bpbdate between '$tglf' and '$tglt' and mattype='C' group by kpno,itemdesc";
			mysql_query ($sql);
			$sql = "drop table if exists wip_new_fg";
			mysql_query ($sql);
			$sql = "create table wip_new_fg select kpno,sum(qty) QtySetFG from bpb where bpbdate between '$tglf' and '$tglt' and bpbno like 'FG%' 
				group by kpno ";
			mysql_query ($sql);
			$sql = "update wip_new a inner join wip_new_fg s on a.kpno=s.kpno set a.qtysetfg=s.qtysetfg ";
			mysql_query ($sql);
			$sql = mysql_query ("SELECT a.kpno kode_brg,a.matclass,a.itemdesc nama_brg,concat(a.kpno,'+',a.itemdesc) GroupNya,a.qtyset-a.qtysetfg qty_nya,unit FROM wip_new a ");
		}
	else
		{	if ($nm_company=="PT. Seowon Manufacturing Indonesia" OR $nm_company=="PT. Youngil Leather Indonesia" 
				OR $nm_company=="PT. Tunggal Indotama Abadi" OR $nm_company=="PT. Inwoo S & B Indonesia"
				OR $nm_company=="PT. Global Hanstama Jaya" OR $nm_company=="PT. Diansurya Global"
				OR $nm_company=="PT. Gaya Indah Kharisma" OR $nm_company=="PT. Geumcheon Indo"
				OR $nm_company=="PT. Shinwon Chemical Products Indonesia"
				OR $nm_company=="PT. E-Jade Global")
			{	$grup = "s.itemdesc"; }
			else
			{	$grup = "s.matclass";}
			$que = "SELECT a.*,s.*,if(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0',s.goods_code,concat(s.mattype,' ',s.id_item)) kode_brg,$grup GroupNya,sum(a.qty) qty_nya 
				FROM bppb a INNER JOIN masteritem s ON a.id_item = s.id_item where a.bppbdate between '$tglf' and '$tglt' 
				and s.mattype not in ('FG','M','S','L') and stock_card!='FG' and 
				a.tujuan!='DIKEMBALIKAN' and bppbno not like 'SJ-FG%' group by $grup ";
			$sql = mysql_query ($que);
		}
	$no = 1; #nomor awal
	while($data = mysql_fetch_array($sql)){ #ketika data tabel mahasiswa di array kan maka lakukan perulangan hingga mahasiswa terakhir
	$kode_barang = $data['kode_brg']; #dapatkan id mahasiswa dari data array (row) 'id'
	$nm_barang = $data['GroupNya']; #dapatkan nama mahasiswa dari data array (row) 'nama'
	$satuan = $data['unit']; #dapatkan jurusan mahasiswa dari data array (row) 'jurusan' 
	$jumlah = $data['qty_nya']; #dapatkan kelamin mahasiswa dari data array (row) 'kelamin' 
	echo "
	<tr>
		<td align='center'><span class='style4'>$no</span></td>
		<td><span class='style4'>$kode_barang</span></td>
		<td nowrap><span class='style4'>$nm_barang</span></td>
		<td align='center'><span class='style4'>$satuan</span></td>
		<td align='right'><span class='style4'>$jumlah</span></td>
		<td align='center'></td>
	</tr>
	"; #muculkan semua data mahasiswa dalam bentuk tabel
	$no++; #$no bertambah 1
	}
  	?>
</table>
<?PHP echo "<a href='indexa.php?uid=$user&sesi=$sesi&mode='>Back</a>"; ?>
<p>&nbsp;</p>
</body>
</html>