<?php
session_start();
include "../../include/conn.php";

if (empty($_SESSION['username'])) {
    header("location:../../index.php");
    exit;
}

$user = $_SESSION['username'];
$rpt = "bc30"; // hardcoded, atau bisa ambil dari $_GET['rptid']
$tglf = $_GET['parfrom'];
$tglt = $_GET['parto'];

// Set header Excel
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=laporan_bc30.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Tampilkan header HTML Excel
echo "<div class='box'>";
echo "<div class='box-body'>";

		if ($rpt == "bc30" or $st_company == "GB") {
			echo "GUDANG BERIKAT ";
			echo strtoupper($nm_company);
			echo "<br>";
			echo "B. LAPORAN PENGELUARAN BARANG PER DOKUMEN PABEAN";
			echo "<br>";
		}
		echo "PERIODE ";
		echo strtoupper(date('d F Y', strtotime($tglf)));
		echo " S/D ";
		echo strtoupper(date('d F Y', strtotime($tglt)));

echo "</div>";
echo "</div>";
echo "<table border='1'>";
echo "<thead>
					<tr>
						<th rowspan='2'>NO</th>
						<th rowspan='2'>JENIS DOKUMEN</th>
						<th rowspan='2'>KATEGORI BARANG</th>
						<th colspan='2'>DOKUMEN PABEAN</th>
						<th colspan='2'>BUKTI PENGELUARAN BARANG</th>
						<th rowspan='2'>PEMBELI / PENERIMA</th>
						<th rowspan='2'>KODE BARANG</th>
						<th rowspan='2'>NAMA BARANG</th>
						<th rowspan='2'>SAT</th>
						<th rowspan='2'>JUMLAH</th>
						<th colspan='2'>NILAI BARANG</th>
					</tr>
					<tr>
						<th>NOMOR</th>
						<th>TANGGAL</th>
						<th>NOMOR</th>
						<th>TANGGAL</th>	
						<th>CURR</th>
						<th>NILAI</th>					
					</tr>
</thead><tbody>";

// Gabungan SQL 3 UNION
$sql = "
SELECT * FROM (
    SELECT 'BC 3.0' jenis_dokumen, LPAD(a.bcno,6,'0') bcno, a.bcdate,
    IF(a.bppbno_int!='',a.bppbno_int,a.bppbno) trans_no,
    a.bppbno, a.bppbdate trans_date, d.supplier,
    IF(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0', s.goods_code, CONCAT('FG ',s.id_item)) kode_brg,
    s.itemname itemdesc, a.unit, a.qty,
    ROUND(a.qty*IFNULL(a.price_bc,a.price),2) nilai_barang,
    a.curr, IF(a.price_bc IS NULL OR a.price_bc='0', a.price, a.price_bc) price,
    'FG' mattype, s.id_item, 'BARANG JADI' matclass
    FROM bppb a
    INNER JOIN masterstyle s ON a.id_item=s.id_item
    LEFT JOIN mastersupplier d ON a.id_supplier=d.id_supplier
    WHERE bppbdate BETWEEN '$tglf' AND '$tglt'
    AND MID(bppbno,4,2)='FG' AND jenis_dok='BC 3.0' AND a.cancel = 'N'

    UNION

    SELECT 'BC 3.0', LPAD(a.bcno,6,'0'), a.bcdate,
    IF(a.bppbno_int!='',a.bppbno_int,a.bppbno), a.bppbno, a.bppbdate,
    d.supplier,
    IF(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0', s.goods_code, CONCAT('F ',s.id_item)),
    s.itemdesc, a.unit, a.qty,
    ROUND(a.qty*IFNULL(a.price_bc,a.price),2),
    a.curr, IF(a.price_bc IS NULL OR a.price_bc='0', a.price, a.price_bc),
    'F', s.id_item, 'FABRIC'
    FROM bppb a
    INNER JOIN masteritem s ON a.id_item=s.id_item
    LEFT JOIN mastersupplier d ON a.id_supplier=d.id_supplier
    WHERE bppbdate BETWEEN '$tglf' AND '$tglt'
    AND LEFT(bppbno_int,2)='GK' AND jenis_dok='BC 3.0' AND a.cancel = 'N'

    UNION

    SELECT 'BC 3.0', LPAD(a.bcno,6,'0'), a.bcdate,
    IF(a.bppbno_int!='',a.bppbno_int,a.bppbno), a.bppbno, a.bppbdate,
    d.supplier,
    IF(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0', s.goods_code, CONCAT('F ',s.id_item)),
    s.itemdesc, a.unit, a.qty,
    ROUND(a.qty*IFNULL(a.price_bc,a.price),2),
    a.curr, IF(a.price_bc IS NULL OR a.price_bc='0', a.price, a.price_bc),
    'F', s.id_item, s.matclass
    FROM bppb a
    INNER JOIN masteritem s ON a.id_item=s.id_item
    LEFT JOIN mastersupplier d ON a.id_supplier=d.id_supplier
    WHERE bppbdate BETWEEN '$tglf' AND '$tglt'
    AND LEFT(bppbno_int,3)='GEN' AND jenis_dok='BC 3.0' AND a.cancel = 'N'
) a
ORDER BY bcdate, bcno, trans_no
";

$query = mysql_query($sql);
$no = 1;
while ($row = mysql_fetch_assoc($query)) {
    $tgl_bc = ($row['bcdate'] != '0000-00-00') ? date('d-M-Y', strtotime($row['bcdate'])) : '';
    $tgl_trans = ($row['trans_date'] != '0000-00-00') ? date('d-M-Y', strtotime($row['trans_date'])) : '';

    echo "<tr>";
    echo "<td>$no</td>";
    echo "<td>{$row['jenis_dokumen']}</td>";
    echo "<td>{$row['matclass']}</td>";
    echo "<td>{$row['bcno']}</td>";
    echo "<td>$tgl_bc</td>";
    echo "<td>{$row['trans_no']}</td>";
    echo "<td>$tgl_trans</td>";
    echo "<td>{$row['supplier']}</td>";
    echo "<td>{$row['id_item']}</td>";
    echo "<td>{$row['itemdesc']}</td>";
    echo "<td>{$row['unit']}</td>";
    echo "<td>{$row['qty']}</td>";
    echo "<td>{$row['curr']}</td>";
    echo "<td>{$row['nilai_barang']}</td>";
    echo "</tr>";
    $no++;
}

echo "</tbody></table>";
?>
