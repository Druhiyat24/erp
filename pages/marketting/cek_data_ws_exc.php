<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../index.php"); }

if ($mode=='exc')
{ 
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=lap_cek_ws.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0");
}

$mod = $_GET['mod'];

if (isset($_GET['from'])) {$from = date('d M Y',strtotime($_GET['from'])); } else {$from = "";}
if (isset($_GET['to'])) {$to = date('d M Y',strtotime($_GET['to'])); } else {$to = "";}


# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
/*
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";

echo "var tipe = document.form.txttipe.value;";
echo "var from = document.form.txtfrom.value;";
echo "var to = document.form.txtto.value;";

echo "if (tipe == '') { alert('Tipe tidak boleh kosong'); document.form.txttipe.focus();valid = false;}";
echo "else if (from == '') { alert('Dari Tanggal tidak boleh kosong'); document.form.txtfrom.style.backgroundColor='yellow'; document.form.txtfrom.focus();valid = false;}";
echo "else if (to == '') { alert('Sampai Tanggal tidak boleh kosong'); document.form.txtto.style.backgroundColor='yellow'; document.form.txtto.focus();valid = false;}";
echo "else valid = true;";
echo "return valid;";
echo "exit;";
  echo "}";
echo "</script>";
*/
# END COPAS VALIDASI
# COPAS ADD
// $from=date('d M Y');
// $to=date('d M Y');
 ?>
<?php
  # END COPAS ADD
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List Laporan Checking Data WS</h3>
  </div>
  <div>
    Periode : <?php echo $from; ?> - <?php echo $to; ?>
  </div>  
  <div class="box-body"> 
    <table id="tbl_mut_brgjadi" border="1" class="table table-bordered table-striped">
      <thead>
        <tr>
            <th>No</th>
            <th>WS</th>
            <th>Buyer</th>
            <th>Nama Barang</th>
            <th>Supplier</th>
            <th>Negara</th>
            <th>Jenis SO</th>
            <th>Brand</th>
        </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $fromcri=date('Y-m-d',strtotime($from));
        $tocri=date('Y-m-d',strtotime($to));

        if($from == '' && $to == '')

        {
          $sql="";
        }

        else
        {            
         $sql="select ac.kpno,mi.itemdesc, ms.supplier, ms.country, po.qty_po, ac.jns_so, ac.brand,ac.buyer,cost_date  from bom_jo_item k
inner join masteritem mi on k.id_item  = mi.id_gen
left join mastersupplier ms on k.id_supplier = ms.Id_Supplier
left join mastersupplier ms_n on k.id_supplier2 = ms_n.id_supplier
left join 
(
select id_jo, id_gen, sum(qty) qty_po, id_supplier from po_item pi 
inner join po_header ph on pi.id_po = ph.id
where pi.cancel = 'N'
group by id_jo, id_gen, id_supplier
) po on k.id_jo = po.id_jo and k.id_item = po.id_gen
inner join (
select ac.id_buyer,ac.styleno,jd.id_jo, ac.kpno, so.jns_so, ac.brand, ms.supplier buyer, cost_date from jo_det jd
         inner join so on jd.id_so = so.id
         inner join act_costing ac on so.id_cost = ac.id
         inner join mastersupplier ms on ac.id_buyer = ms.id_supplier
         where jd.cancel = 'N'
         group by id_cost order by id_jo asc
) ac on k.id_jo = ac.id_jo
where k.cancel = 'N' and cost_date >= '$fromcri' and cost_date <= '$tocri' and qty_po is not null and qty_po != '0'
group by k.id_item
order by ac.kpno asc


";
 }

// select ms.goods_code,ms.itemname,ms.styleno,ms.kpno,ms.color,ms.size,ms.country,mutasi.id_item, mutasi.id_so_det, sum(saldo_awal) as saldo_awal, sum(penerimaan) as penerimaan, sum(pengeluaran) as pengeluaran, sum(saldo_awal) + sum(penerimaan) - sum(pengeluaran) as saldo_akhir from
// (
// select id_item , id_so_det, saldo as saldo_awal, '0' as penerimaan, '0' as pengeluaran from saldoawal_fg sa 
// where sa.periode = '$fromcri'
// union
// select id_item, id_so_det, '0' as saldo_awal, sum(qty) as penerimaan, '0' as pengeluaran from bpb where 
// bpbdate >= '$fromcri' and bpbdate <= '$tocri'
// and bpbno like 'FG%'
// group by id_item, id_so_det
// union
// select id_item, id_so_det, '0' as saldo_awal, '0' as penerimaan, sum(qty) as pengeluaran from bppb where 
// bppbdate >= '$fromcri' and bppbdate <= '$tocri'
// and bppbno like 'SJ-FG%'
// group by id_item, id_so_det
// ) 
// mutasi
// inner join masterstyle ms on mutasi.id_item = ms.id_item and mutasi.id_so_det = ms.id_so_det
// group by mutasi.id_item, mutasi.id_so_det




 // having sum(saldo_awal) + sum(penerimaan) - sum(pengeluaran) != '0'           
        #echo $sql;
        $query = mysql_query($sql);
        $no = 1; 
        while($data = mysql_fetch_array($query))
        {
        echo "<tr>"; 
        echo "<td>$no</td>";
        echo "<td>$data[kpno]</td>";
        echo "<td>$data[buyer]</td>";
        echo "<td>$data[itemdesc]</td>";
        echo "<td>$data[supplier]</td>";
        echo "<td>$data[country]</td>";
        echo "<td>$data[jns_so]</td>";
        echo "<td>$data[brand]</td>";
        echo "</tr>";    
        $no++; // menambah nilai nomor urut
        }
                // echo "<td>$data[tgl_input]</td>";
        ?>
      </tbody>
    </table>
  </div>
</div>