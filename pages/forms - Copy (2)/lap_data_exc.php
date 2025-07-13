<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}
if (isset($_SESSION['username'])) {
  $user = $_SESSION['username'];
} else {
  header("location:../../index.php");
}


if (isset($_GET['from'])) {
  $from = date('d M Y', strtotime($_GET['from']));
} else {
  $from = "";
}
if (isset($_GET['to'])) {
  $to = date('d M Y', strtotime($_GET['to']));
} else {
  $to = "";
}
$tipe_data = $_GET['tipe_data'];
$cbotipe = $_GET['cbotipe'];
$cbojenis = $_GET['cbojenis'];

if ($tipe_data == 'PENERIMAAN DETAIL') {
  $tipe_data_xls = 'Penerimaan_det_';
} else if ($tipe_data == 'PENGELUARAN DETAIL') {
  $tipe_data_xls = 'Pengeluaran_det_';
} else if ($tipe_data == 'MUTASI') {
  $tipe_data_xls = 'Mutasi_';
}

if ($cbotipe == 'A') {
  $cbotipefix = 'ACCESSORIES';
  $cbotipefix_xls = 'Accs';
} else {
  $cbotipefix = $cbotipe;
}

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Lap_$tipe_data_xls$cbojenis_xls$cbotipefix_xls.xls"); //ganti nama sesuai keperluan 
header("Pragma: no-cache");
header("Expires: 0");

?>
<?php
# END COPAS ADD
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">LIST <?php echo $tipe_data; ?> - <?php echo $cbotipefix; ?> - <?php echo $cbojenis; ?></h3>
  </div>
  <div>
    Periode : <?php echo $from; ?> - <?php echo $to; ?>
  </div>
  <?php if ($tipe_data == 'PENERIMAAN DETAIL') { ?>
    <div class="box-body">
      <table border="1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>Nomor BPB</th>
            <th>Tgl BPB</th>
            <th>Supplier</th>
            <th>No PO</th>
            <th>No SJ</th>
            <th>No WS</th>
            <th>Buyer</th>
            <th>Style</th>
            <th>ID Item</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Qty Rak</th>
            <th>Unit</th>
            <th>Rak</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $fromcri = date('Y-m-d', strtotime($from));
          $tocri = date('Y-m-d', strtotime($to));

          $sql = "select bd.*,
          bpb.bpbdate tgl_bpb, 
          jd.kpno, 
          mi.itemdesc, 
          mi.goods_code, 
          concat(mr.kode_rak, ' ', mr.nama_rak)rak, 
          supplier,
          pono,
          invno,
          buyer,
          styleno 
          from bpb_det bd
         inner join (select bpbno, pono, id_supplier,bpbno_int, bpbdate,invno from bpb where bpbdate >= '$fromcri' and bpbdate <= '$tocri' 
         group by bpbno) bpb on bd.bpbno = bpb.bpbno
         inner join masteritem mi on bd.id_item = mi.id_item
				 inner join (select ac.id_buyer, supplier buyer,ac.styleno,jd.id_jo, ac.kpno from jo_det jd
         inner join so on jd.id_so = so.id
         inner join act_costing ac on so.id_cost = ac.id
				 inner join mastersupplier mb on ac.id_buyer = mb.id_supplier
         where jd.cancel = 'N'
         group by id_cost order by id_jo asc) jd on bd.id_jo = jd.id_jo
         inner join master_rak mr on bd.id_rak_loc = mr.id
         inner join mastersupplier ms on bpb.id_supplier = ms.id_supplier 
         where bpb.bpbdate >= '$fromcri' and bpb.bpbdate <= '$tocri'
         order by bpb.bpbdate asc
";

          #echo $sql;
          $query = mysql_query($sql);
          $no = 1;
          while ($data = mysql_fetch_array($query)) {
            $tgl_bpb = date('d M Y', strtotime($data[tgl_bpb]));
            echo "<tr>";
            echo "<td>$no</td>";
            echo "<td>$data[bpbno_int]</td>";
            echo "<td>$tgl_bpb</td>";
            echo "<td>$data[supplier]</td>";
            echo "<td>$data[pono]</td>";
            echo "<td>$data[invno]</td>";
            echo "<td>$data[kpno]</td>";
            echo "<td>$data[buyer]</td>";
            echo "<td>$data[styleno]</td>";
            echo "<td>$data[id_item]</td>";
            echo "<td>$data[goods_code]</td>";
            echo "<td>$data[itemdesc]</td>";
            echo "<td>$data[roll_qty]</td>";
            echo "<td>$data[unit]</td>";
            echo "<td>$data[rak]</td>";
            echo "</tr>";
            $no++; // menambah nilai nomor urut
          }
          // echo "<td>$data[tgl_input]</td>";
          ?>
        </tbody>
      </table>
    </div>
  <?php } else if ($tipe_data == 'PENGELUARAN DETAIL') { ?>

    <div class="box-body">
      <table border="1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>Nomor BPB</th>
            <th>Nomor Request</th>
            <th>Tgl BPB</th>
            <th>Supplier</th>
            <th>No WS</th>
            <th>No WS Aktual</th>
            <th>ID Item</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Qty Rak</th>
            <th>Unit</th>
            <th>Rak</th>
            <th>Ket</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $fromcri = date('Y-m-d', strtotime($from));
          $tocri = date('Y-m-d', strtotime($to));

          $sql = "select bd.*,
          bppb.bppbdate tgl_bppb, 
          jd.kpno, 
          mi.itemdesc,
          mi.goods_code,
          concat(mr.kode_rak, ' ', mr.nama_rak)rak, 
          supplier, 
          idws_act,
          remark,
          bppb.bppbno_req 
          from bppb_det bd
          inner join (select bppbno,bppbno_int,bppbno_req, id_supplier, bppbdate, remark from bppb where bppbdate >= '$fromcri' and bppbdate <= '$tocri' 
          group by bppbno) bppb on bd.bppbno = bppb.bppbno
          left join (select bppbno, idws_act from bppb_req group by bppbno) br on bppb.bppbno_req = br.bppbno
          inner join masteritem mi on bd.id_item = mi.id_item
          inner join (select ac.id_buyer,ac.styleno,jd.id_jo, ac.kpno from jo_det jd
          inner join so on jd.id_so = so.id
          inner join act_costing ac on so.id_cost = ac.id
          where jd.cancel = 'N'
          group by id_cost order by id_jo asc) jd on bd.id_jo = jd.id_jo
          inner join master_rak mr on bd.id_rak_loc = mr.id
          inner join mastersupplier ms on bppb.id_supplier = ms.id_supplier 
          where bppb.bppbdate >= '$fromcri' and bppb.bppbdate <= '$tocri'
          order by bppb.bppbdate asc
				 
";

          # echo $sql;
          $query = mysql_query($sql);
          $no = 1;
          while ($data = mysql_fetch_array($query)) {
            $tgl_bppb = date('d M Y', strtotime($data[tgl_bppb]));
            echo "<tr>";
            echo "<td>$no</td>";
            echo "<td>$data[bppbno_int]</td>";
            echo "<td>$data[bppbno_req]</td>";
            echo "<td>$tgl_bppb</td>";
            echo "<td>$data[supplier]</td>";
            echo "<td>$data[kpno]</td>";
            echo "<td>$data[idws_act]</td>";
            echo "<td>$data[id_item]</td>";
            echo "<td>$data[goods_code]</td>";
            echo "<td>$data[itemdesc]</td>";
            echo "<td>$data[roll_qty]</td>";
            echo "<td>$data[unit]</td>";
            echo "<td>$data[rak]</td>";
            echo "<td>$data[remark]</td>";
            echo "</tr>";
            $no++; // menambah nilai nomor urut
          }
          // echo "<td>$data[tgl_input]</td>";
          ?>
        </tbody>
      </table>
    </div>
  <?php } else if ($tipe_data == 'MUTASI DETAIL') { ?>

    <div class="box-body">
      <table border="1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>Lokasi</th>
            <th>ID Jo</th>
            <th>WS</th>
            <th>Style</th>
            <th>Buyer</th>
            <th>ID Item</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Unit</th>
            <th>Saldo Awal</th>
            <th>Pemasukan</th>
            <th>Pengeluaran</th>
            <th>Saldo Akhir</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $fromcri = date('Y-m-d', strtotime($from));
          $tocri = date('Y-m-d', strtotime($to));

          $sql = "select concat(kode_rak ,' ', nama_rak) lokasi,
          jd.id_jo,
          jd.kpno,
          jd.styleno,
          mb.supplier buyer,
          a.id_item,
          mi.goods_code,
          mi.itemdesc,
          a.unit,
          round(coalesce(sum(a.qty_awal),0),2) qty_awal,
          round(coalesce(sum(a.qty_in),0),2) qty_in,
          round(coalesce(sum(a.qty_out),0),2) qty_out,
          round(coalesce(sum(a.qty_awal),0),2) + round(coalesce(sum(a.qty_in),0),2) - round(coalesce(sum(a.qty_out),0),2) sisa from 
          (
            select sa.id_rak_loc, sa.id_item,sa.id_jo, sa.unit,
            round(coalesce(sum(sa.qty_awal),0),2) + round(coalesce(sum(sa.qty_in_awal),0),2) - round(coalesce(sum(sa.qty_out_awal),0),2) qty_awal, '0' qty_in, '0' qty_out
             from 
            (
            select bd.id_rak_loc, id_item, id_jo,unit,sum(roll_qty) qty_awal, '0' qty_in_awal, sum(qty_mutasi) qty_out_awal from bpb_det bd
            where bpbdate = '2023-02-01' and bpbno = 'saldoawal'
            group by id_rak_loc, id_item, id_jo, unit
            union					
            select bd.id_rak_loc, id_item, id_jo,unit,'0' qty_awal, sum(roll_qty) qty_in_awal, sum(qty_mutasi) qty_out_awal 
            from bpb_det bd
            inner join (select bpbno, pono, id_supplier,bpbno_int, bpbdate,invno from bpb where bpbdate >= '2023-02-01' and bpbdate < '$fromcri' 
            group by bpbno) bpb on bd.bpbno = bpb.bpbno
            where bpb.bpbdate >= '2023-02-01' and bpb.bpbdate <'$fromcri' and bd.bpbno <> 'saldoawal'
            group by id_rak_loc, id_item, id_jo, unit	
            union
            select bd.id_rak_loc, id_item, id_jo,unit,'0' qty_awal, '0' qty_in_awal, sum(roll_qty) qty_out_awal 
            from bppb_det bd
            inner join (select bppbno,bppbdate from bppb 
            where bppbdate >= '2023-02-01' and bppbdate < '$fromcri' 
            group by bppbno) bppb on bd.bppbno = bppb.bppbno
            where bppb.bppbdate >= '2023-02-01' and bppb.bppbdate < '$fromcri' 
            group by id_rak_loc, id_item, id_jo, unit
            ) sa
            group by sa.id_rak_loc, sa.id_item, sa.id_jo, sa.unit
          union
          select bd.id_rak_loc, id_item, id_jo,unit,'0' qty_awal, sum(roll_qty) qty_in, sum(qty_mutasi) qty_out 
          from bpb_det bd
          inner join (select bpbno, pono, id_supplier,bpbno_int, bpbdate,invno from bpb where bpbdate >= '$fromcri' and bpbdate <= '$tocri' 
          group by bpbno) bpb on bd.bpbno = bpb.bpbno
          where bpb.bpbdate >= '$fromcri' and bpb.bpbdate <= '$tocri' and bd.bpbno <> 'saldoawal'
          group by id_rak_loc, id_item, id_jo, unit
          union
          select bd.id_rak_loc, id_item, id_jo,unit,'0' qty_awal, '0' qty_in, sum(roll_qty) qty_out 
          from bppb_det bd
          inner join (select bppbno,bppbdate from bppb 
          where bppbdate >= '$fromcri' and bppbdate <= '$tocri' 
          group by bppbno) bppb on bd.bppbno = bppb.bppbno
          where bppb.bppbdate >= '$fromcri' and bppb.bppbdate <= '$tocri' 
          group by id_rak_loc, id_item, id_jo, unit	          
          ) a
          inner join master_rak mr on a.id_rak_loc = mr.id
          inner join (
          select ac.id_buyer,ac.styleno,jd.id_jo, ac.kpno from jo_det jd
         inner join so on jd.id_so = so.id
         inner join act_costing ac on so.id_cost = ac.id
         where jd.cancel = 'N'
         group by id_cost order by id_jo asc) jd on a.id_jo = jd.id_jo
          inner join mastersupplier mb on jd.id_buyer = mb.id_supplier
          inner join masteritem mi on a.id_item = mi.id_item
          group by a.id_rak_loc, a.id_item, a.id_jo, a.unit	 
";

          #echo $sql;
          $query = mysql_query($sql);
          $no = 1;
          while ($data = mysql_fetch_array($query)) {
            $tgl_bppb = date('d M Y', strtotime($data[tgl_bppb]));
            echo "<tr>";
            echo "<td>$no</td>";
            echo "<td>$data[lokasi]</td>";
            echo "<td>$data[id_jo]</td>";
            echo "<td>$data[kpno]</td>";
            echo "<td>$data[styleno]</td>";
            echo "<td>$data[buyer]</td>";
            echo "<td>$data[id_item]</td>";
            echo "<td>$data[goods_code]</td>";
            echo "<td>$data[itemdesc]</td>";
            echo "<td>$data[unit]</td>";
            echo "<td>$data[qty_awal]</td>";
            echo "<td>$data[qty_in]</td>";
            echo "<td>$data[qty_out]</td>";
            echo "<td>$data[sisa]</td>";
            echo "</tr>";
            $no++; // menambah nilai nomor urut
          }
          // echo "<td>$data[tgl_input]</td>";
          ?>
        </tbody>
      </table>
    </div>
  <?php } ?>
</div>