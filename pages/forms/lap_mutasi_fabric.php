<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}
if (isset($_SESSION['username'])) {
  $user = $_SESSION['username'];
} else {
  header("location:../../index.php");
}


if (isset($_GET['parfrom'])) {
  $parfrom = date('d M Y', strtotime($_GET['parfrom']));
} else {
  $parfrom = "";
}
if (isset($_GET['parto'])) {
  $parto = date('d M Y', strtotime($_GET['parto']));
} else {
  $parto = "";
}


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=mut.xls"); //ganti nama sesuai keperluan 
header("Pragma: no-cache");
header("Expires: 0");

?>
<?php
# END COPAS ADD
?>
<div class="box">
  <div class="box-header">
    KAWASAN BERIKAT PT. NIRWANA ALABARE GARMENT   
  </div>
  <div class="box-header">
LAPORAN PERTANGGUNGJAWABAN MUTASI BAHAN BAKU DAN BAHAN PENOLONG (FABRIC)    
  </div>
  <div>
    PERIODE  <?php echo $parfrom; ?> S/D <?php echo $parto; ?>
  </div>
    <div class="box-body">
      <table border="1" class="table table-bordered table-striped" style="font-size:12px;">
        <thead>
          <tr>
            <th>NO.</th>
            <th>ID ITEM</th>
            <th>KODE BARANG</th>
            <th>NAMA BARANG</th>
            <th>SAT</th>
            <th>SALDO AWAL</th>
            <th>PEMASUKAN</th>
            <th>PENGELUARAN</th>
            <th>PENYESUAIAN</th>
            <th>SALDO AKHIR</th>
            <th>STOCK OPNAME</th>
            <th>SELISIH</th>
            <th>KETERANGAN</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $tglf = date('Y-m-d', strtotime($parfrom));
          $tglt = date('Y-m-d', strtotime($parto));

          $sql = "SELECT '' vmat,'F' matclass,id_item, goods_code kode_brg, itemdesc nama_brg, satuan unit, sum(sal_awal) saldo_awal, sum(qty_in) qtyrcv, sum(qty_out) qtyout, sum(sal_akhir) sal_akhir, GROUP_CONCAT(IF(sal_akhir > 0,CONCAT(kode_lok,'(',sal_akhir,') '),null)) keterangan from (select kode_lok,id_jo,no_ws,styleno,buyer,id_item,goods_code,itemdesc,satuan,round((sal_awal - qty_out_sbl),2) sal_awal,round(qty_in,2) qty_in,ROUND(qty_out_sbl,2) qty_out_sbl,ROUND(qty_out,2) qty_out, round((sal_awal + qty_in - qty_out_sbl - qty_out),2) sal_akhir from (select a.kode_lok kode_lok,a.id_jo,no_ws,styleno,buyer,a.id_item,goods_code,itemdesc,a.satuan,sal_awal,qty_in,coalesce(qty_out_sbl,'0') qty_out_sbl,coalesce(qty_out,'0') qty_out from (select b.kode_lok,b.id_jo,b.no_ws,b.styleno,b.buyer,b.id_item,b.goods_code,b.itemdesc,b.satuan, sal_awal, qty_in from (select id_item,unit from whs_sa_fabric  group by id_item,unit
        UNION
        select id_item,unit from whs_inmaterial_fabric_det group by id_item,unit) a left join
      (select kode_lok,id_jo,no_ws,styleno,buyer,id_item,goods_code,itemdesc,satuan, sum(sal_awal) sal_awal,sum(qty_in) qty_in from (select 'TR' id,a.kode_lok,a.id_jo,a.no_ws,jd.styleno,mb.supplier buyer,a.id_item,b.goods_code,b.itemdesc,a.satuan, sum(qty_sj) sal_awal,'0' qty_in from whs_lokasi_inmaterial a 
        inner join whs_inmaterial_fabric bpb on bpb.no_dok = a.no_dok
        inner join masteritem b on b.id_item = a.id_item
        inner join (select ac.id_buyer,ac.styleno,jd.id_jo, ac.kpno from jo_det jd inner join so on jd.id_so = so.id inner join act_costing ac on so.id_cost = ac.id where jd.cancel = 'N' group by id_cost order by id_jo asc) jd on a.id_jo = jd.id_jo
        inner join mastersupplier mb on jd.id_buyer = mb.id_supplier where a.status = 'Y' and bpb.tgl_dok < '$tglf' group by a.kode_lok, a.id_item, a.id_jo, a.satuan
        UNION
        select 'SAM' id,lk.kode_lok,lk.id_jo,lk.no_ws,jd.styleno,mb.supplier buyer,lk.id_item,b.goods_code,b.itemdesc,lk.satuan, sum(qty_sj) sal_awal,'0' qty_in from whs_mut_lokasi_h a 
        inner join whs_lokasi_inmaterial lk on lk.no_dok = a.no_mut
        inner join masteritem b on b.id_item = lk.id_item
        inner join (select ac.id_buyer,ac.styleno,jd.id_jo, ac.kpno from jo_det jd inner join so on jd.id_so = so.id inner join act_costing ac on so.id_cost = ac.id where jd.cancel = 'N' group by id_cost order by id_jo asc) jd on lk.id_jo = jd.id_jo
        inner join mastersupplier mb on jd.id_buyer = mb.id_supplier where lk.status = 'Y' and a.tgl_mut < '$tglf' group by lk.kode_lok, lk.id_item, lk.id_jo, lk.satuan
        UNION
        select 'SA' id,a.kode_lok,a.id_jo,a.no_ws,jd.styleno,mb.supplier buyer,a.id_item,b.goods_code,b.itemdesc,a.unit, round(sum(qty),2) sal_awal,'0' qty_in from whs_sa_fabric a
        inner join masteritem b on b.id_item = a.id_item
        left join (select ac.id_buyer,ac.styleno,jd.id_jo, ac.kpno from jo_det jd inner join so on jd.id_so = so.id inner join act_costing ac on so.id_cost = ac.id where jd.cancel = 'N' group by id_jo order by id_jo asc) jd on a.id_jo = jd.id_jo
        left join mastersupplier mb on jd.id_buyer = mb.id_supplier where a.qty > 0  group by a.kode_lok, a.id_item, a.id_jo, a.unit
        UNION 
        select 'TRI' id,a.kode_lok,a.id_jo,a.no_ws,jd.styleno,mb.supplier buyer,a.id_item,b.goods_code,b.itemdesc,a.satuan,'0' sal_awal, round(sum(qty_sj),2) qty_in from whs_lokasi_inmaterial a 
        inner join whs_inmaterial_fabric bpb on bpb.no_dok = a.no_dok
        inner join masteritem b on b.id_item = a.id_item
        inner join (select ac.id_buyer,ac.styleno,jd.id_jo, ac.kpno from jo_det jd inner join so on jd.id_so = so.id inner join act_costing ac on so.id_cost = ac.id where jd.cancel = 'N' group by id_cost order by id_jo asc) jd on a.id_jo = jd.id_jo
        inner join mastersupplier mb on jd.id_buyer = mb.id_supplier where a.status = 'Y' and bpb.tgl_dok BETWEEN '$tglf' and '$tglt' group by a.kode_lok, a.id_item, a.id_jo, a.satuan
        UNION
        select 'TRM' id,lk.kode_lok,lk.id_jo,lk.no_ws,jd.styleno,mb.supplier buyer,lk.id_item,b.goods_code,b.itemdesc,lk.satuan, '0' sal_awal, sum(qty_sj) qty_in from whs_mut_lokasi_h a 
        inner join whs_lokasi_inmaterial lk on lk.no_dok = a.no_mut
        inner join masteritem b on b.id_item = lk.id_item
        inner join (select ac.id_buyer,ac.styleno,jd.id_jo, ac.kpno from jo_det jd inner join so on jd.id_so = so.id inner join act_costing ac on so.id_cost = ac.id where jd.cancel = 'N' group by id_cost order by id_jo asc) jd on lk.id_jo = jd.id_jo
        inner join mastersupplier mb on jd.id_buyer = mb.id_supplier where lk.status = 'Y' and a.tgl_mut BETWEEN '$tglf' and '$tglt' group by lk.kode_lok, lk.id_item, lk.id_jo, lk.satuan) a group by a.kode_lok, a.id_item, a.id_jo, a.satuan

      ) b on b.id_item = a.id_item and b.satuan = a.unit where kode_lok is not null) a left join (select kode_lok,id_item,id_jo,satuan,ROUND(sum(qty_out_sbl),2) qty_out_sbl,ROUND(sum(qty_out),2) qty_out from (select id,kode_lok,id_item,id_jo,satuan,qty_out_sbl,'0' qty_out from (select 'OMB' id,b.kode_lok,b.id_item,b.id_jo,satuan,sum(a.qty_mutasi) qty_out_sbl from whs_mut_lokasi a inner join (select no_barcode,kode_lok,id_item,id_jo,satuan FROM whs_lokasi_inmaterial GROUP BY no_barcode
        UNION
        select no_barcode,kode_lok,id_item,id_jo,unit satuan FROM whs_sa_fabric GROUP BY no_barcode) b on a.idbpb_det = b.no_barcode where a.status = 'Y' and tgl_mut < '$tglf' group by b.kode_lok,b.id_item,b.id_jo,satuan
      UNION
      select 'OTB' id,no_rak kode_lok,id_item,id_jo,satuan,round(sum(qty_out),2) qty_out_sbl from whs_bppb_det a inner join whs_bppb_h b on b.no_bppb = a.no_bppb where a.status = 'Y' and tgl_bppb < '$tglf' group by no_rak, id_item, id_jo, satuan) a
      UNION
      select id,kode_lok,id_item,id_jo,satuan,'0' qty_out_sbl, qty_out from (select 'OM' id,b.kode_lok,b.id_item,b.id_jo,satuan,sum(a.qty_mutasi) qty_out from whs_mut_lokasi a inner join (select no_barcode,kode_lok,id_item,id_jo,satuan FROM whs_lokasi_inmaterial GROUP BY no_barcode
        UNION
        select no_barcode,kode_lok,id_item,id_jo,unit satuan FROM whs_sa_fabric GROUP BY no_barcode) b on a.idbpb_det = b.no_barcode where a.status = 'Y' and tgl_mut BETWEEN '$tglf' and '$tglt' group by b.kode_lok,b.id_item,b.id_jo,satuan
      UNION
      select 'OT' id,no_rak kode_lok,id_item,id_jo,satuan,round(sum(qty_out),2) qty_out from whs_bppb_det a inner join whs_bppb_h b on b.no_bppb = a.no_bppb where a.status = 'Y' and tgl_bppb BETWEEN '$tglf' and '$tglt' group by no_rak, id_item, id_jo, satuan) a) a group by kode_lok, id_item, id_jo, satuan) b on b.kode_lok = a.kode_lok and b.id_jo = a.id_jo and b.id_item = a.id_item and b.satuan = a.satuan) a) a GROUP BY id_item, satuan";


          // echo $sql;
          $query = mysql_query($sql);
          $no = 1;
          while ($data = mysql_fetch_array($query)) {
            echo "<tr>";
            echo "<td>$no</td>";
            echo "<td>$data[id_item]</td>";
            echo "<td>$data[kode_brg]</td>";
            echo "<td>$data[nama_brg]</td>";
            echo "<td>$data[unit]</td>";
            echo "<td>$data[saldo_awal]</td>";
            echo "<td>$data[qtyrcv]</td>";
            echo "<td>$data[qtyout]</td>";
            echo "<td>0</td>";
            echo "<td>$data[sal_akhir]</td>";
            echo "<td>$data[sal_akhir]</td>";
            echo "<td>0</td>";
            echo "<td>$data[keterangan]</td>";
            echo "</tr>";
            $no++; // menambah nilai nomor urut
          }
          // echo "<td>$data[tgl_input]</td>";
          ?>
        </tbody>
      </table>
    </div>
  </div>