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
$jenis_tanggal = $_GET['jenis_tanggal'];
if ($jenis_tanggal == 'tanggal_terima') {
    $kata = 'TANGGAL TERIMA ';
}else{
    $kata = 'TANGGAL PABEAN ';
}
// Set header Excel
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=laporan_bc23.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Tampilkan header HTML Excel
echo "<div class='box'>";
echo "<div class='box-body'>";

        // if ($rpt == "bc30" or $st_company == "GB" or $rpt == "bc33") {
            echo "KAWASAN BERIKAT ";
            echo strtoupper($nm_company);
            echo "<br>";
            echo "A. LAPORAN PEMASUKAN BARANG PER DOKUMEN PABEAN";
            echo "<br>";
        // }
        echo "PERIODE "; echo $kata;
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
                        <th colspan='2'>BUKTI PENERIMAAN BARANG</th>
                        <th rowspan='2'>PEMASOK / PENGIRIM</th>
                        <th rowspan='2'>KODE BARANG</th>
                        <th rowspan='2'>NAMA BARANG</th>
                        <th rowspan='2'>SAT</th>
                        <th rowspan='2'>JUMLAH</th>
                        <th colspan='2'>NILAI BARANG</th>
                        <th rowspan='2'>RATE</th>
                        <th rowspan='2'>NILAI BARANG IDR</th>
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
if($jenis_tanggal == 'tanggal_terima'){
    $sql = "SELECT jenis_dokumen, bcno, bcdate, trans_no, trans_date, supplier, kode_brg, itemdesc, unit, qty, nilai_barang, a.curr, id_item, matclass, COALESCE(mr.rate,1) rate, (nilai_barang * COALESCE(mr.rate,1)) nilai_barang_idr from (SELECT 'BC 2.3 IMPOR PJT' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no, a.bpbdate trans_date,d.supplier, if(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0',s.goods_code, concat(s.mattype,' ',s.id_item)) kode_brg,s.itemdesc,a.unit,sum(a.qty) qty,IFNULL(NULLIF(TRIM(a.curr_bc), ''), a.curr) curr, round(sum(IFNULL(NULLIF(TRIM(a.price_bc), ''), price)*a.qty),2) nilai_barang,a.id_item, satuan_bc, qty_bc, s.matclass from bpb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier where a.cancel='N' and bpbdate between '$tglf' AND '$tglt' and left(bpbno,2)<>'FG' and d.area='I' and a.invno like '%PJT%' group by bcno,bpbno,a.id_item,price
UNION ALL
SELECT 'BC 2.3 IMPOR PJT' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no, a.bpbdate trans_date,d.supplier, if(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0',s.goods_code, concat('FG ',s.id_item)) kode_brg,s.itemname itemdesc,a.unit,sum(a.qty) qty,IFNULL(NULLIF(TRIM(a.curr_bc), ''), a.curr) curr, round(sum(IFNULL(NULLIF(TRIM(a.price_bc), ''), price)*a.qty),2) nilai_barang,a.id_item, satuan_bc, qty_bc,'BARANG JADI' matclass from bpb a inner join masterstyle s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier where a.cancel='N' and bpbdate between '$tglf' AND '$tglt' and left(bpbno,2)='FG' and d.area='I' and a.invno like '%PJT%' group by bcno,bpbno,a.id_item,price order by bcdate,bcno
) a LEFT JOIN (SELECT tanggal, curr, rate FROM ap_masterrate where v_codecurr = 'PAJAK' GROUP BY tanggal, curr) mr ON mr.tanggal = a.bcdate AND mr.curr = a.curr";
}else{
    $sql = "SELECT jenis_dokumen, bcno, bcdate, trans_no, trans_date, supplier, kode_brg, itemdesc, unit, qty, nilai_barang, a.curr, id_item, matclass, COALESCE(mr.rate,1) rate, (nilai_barang * COALESCE(mr.rate,1)) nilai_barang_idr from (SELECT 'BC 2.3 IMPOR PJT' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no, a.bpbdate trans_date,d.supplier, if(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0',s.goods_code, concat(s.mattype,' ',s.id_item)) kode_brg,s.itemdesc,a.unit,sum(a.qty) qty,IFNULL(NULLIF(TRIM(a.curr_bc), ''), a.curr) curr, round(sum(IFNULL(NULLIF(TRIM(a.price_bc), ''), price)*a.qty),2) nilai_barang,a.id_item, satuan_bc, qty_bc, s.matclass from bpb a inner join masteritem s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier where a.cancel='N' and bcdate between '$tglf' AND '$tglt' and left(bpbno,2)<>'FG' and d.area='I' and a.invno like '%PJT%' group by bcno,bpbno,a.id_item,price
UNION ALL
SELECT 'BC 2.3 IMPOR PJT' jenis_dokumen,lpad(a.bcno,6,'0') bcno,a.bcdate,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trans_no, a.bpbdate trans_date,d.supplier, if(s.goods_code<>'' AND s.goods_code<>'-' AND s.goods_code<>'0',s.goods_code, concat('FG ',s.id_item)) kode_brg,s.itemname itemdesc,a.unit,sum(a.qty) qty,IFNULL(NULLIF(TRIM(a.curr_bc), ''), a.curr) curr, round(sum(IFNULL(NULLIF(TRIM(a.price_bc), ''), price)*a.qty),2) nilai_barang,a.id_item, satuan_bc, qty_bc,'BARANG JADI' matclass from bpb a inner join masterstyle s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier where a.cancel='N' and bcdate between '$tglf' AND '$tglt' and left(bpbno,2)='FG' and d.area='I' and a.invno like '%PJT%' group by bcno,bpbno,a.id_item,price order by bcdate,bcno
) a LEFT JOIN (SELECT tanggal, curr, rate FROM ap_masterrate where v_codecurr = 'PAJAK' GROUP BY tanggal, curr) mr ON mr.tanggal = a.bcdate AND mr.curr = a.curr";
}

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
    echo "<td>{$row['rate']}</td>";
    echo "<td>{$row['nilai_barang_idr']}</td>";
    echo "</tr>";
    $no++;
}

echo "</tbody></table>";
?>
