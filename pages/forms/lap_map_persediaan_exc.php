<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../index.php"); }

if ($mode=='exc')
{ 
  header("Content-type: application/vnd-ms-excel"); 
  header("Content-Disposition: attachment; filename=lap_map_persediaan.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0");
}

$mod = $_GET['mod'];
$pfexc = $_GET['pfexc'];

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
    <h3 class="box-title">List Mapping Persediaan</h3>
  </div>
  <div>
    Periode : <?php echo $from; ?> - <?php echo $to; ?>
  </div>  
  <div class="box-body"> 
    <table id="tbl_mut_brgjadi" border="1" class="table table-bordered table-striped" style="font-family:Calibri;">
      <thead>
        <tr>
            <th>No</th>
			<th>Description</th>
            <th>Id Item</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Saldo Awal</th>
            <th>Penerimaan</th>
            <th>Pengeluaran</th>
            <th>Saldo Akhir</th>
            <th>Unit</th>
        </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $fromcri=date('Y-m-d',strtotime($from));
        $tocri=date('Y-m-d',strtotime($to));
		$filter = $pfexc;
  if ($filter=="ALL")
  {
  $queryfilter = '';
  }
  else
  {  
  $queryfilter = "and mc.n_id = '$pfexc'";
}
        if($from == '' && $to == '' && $filter == '')

        {
          $sql="";
        }

        else
        {            
         $sql="SELECT 
description,
id_item,
goods_code kode_brg,
itemdesc nama_brg,
sum(qty_sa)  saldo_awal,
sum(qty_in) qtyrcv,
sum(qty_out) qtyout,
sum(qty_sa) + sum(qty_in) - sum(qty_out) qty_akhir,
unit 
FROM (
select 
description,
id_item,
goods_code,
itemdesc,
sum(qty_sa) + sum(qty_in) - sum(qty_out) qty_sa,
'0' qty_in,
'0' qty_out,
unit 
from 
(
select description, id_item, kd_barang goods_code, mi.itemdesc, qty qty_sa, '0' qty_in, '0' qty_out, unit   from saldoawal_gd a
inner join masteritem mi on a.kd_barang = mi.goods_code
inner join mapping_category mc on mi.n_code_category = mc.n_id
where periode = '2022-01-01' $queryfilter and non_aktif = 'N'
union 
select mc.description, mi.id_item, mi.goods_code, mi.itemdesc, '0' qty_sa,sum(bpb.qty) qty_in,'0' qty_out, bpb.unit from bpb 
inner join masteritem mi on bpb.id_item = mi.id_item
inner join mapping_category mc on mi.n_code_category = mc.n_id
where bpbdate >= '2022-01-01' and bpbdate < '$fromcri' and mi.mattype = 'N' $queryfilter and non_aktif = 'N' and bpb.bpbno like 'N%'
group by mi.id_item, bpb.unit 
union 
select mc.description, mi.id_item, mi.goods_code, mi.itemdesc, '0' qty_sa,'0' qty_in,sum(bppb.qty) qty_out, bppb.unit from bppb 
inner join masteritem mi on bppb.id_item = mi.id_item
inner join mapping_category mc on mi.n_code_category = mc.n_id
where bppbdate >= '2022-01-01' and bppbdate < '$fromcri' and mi.mattype = 'N' $queryfilter and non_aktif = 'N' and bppb.bppbno like 'SJ-N%'
group by mi.id_item, bppb.unit
) trx
group by id_item, unit
UNION
select mc.description, mi.id_item, mi.goods_code, mi.itemdesc, '0' qty_sa,sum(bpb.qty) qty_in,'0' qty_out, bpb.unit from bpb 
inner join masteritem mi on bpb.id_item = mi.id_item
inner join mapping_category mc on mi.n_code_category = mc.n_id
where bpbdate >= '$fromcri' and bpbdate <= '$tocri' and mi.mattype = 'N' $queryfilter and non_aktif = 'N' and bpb.bpbno like 'N%'
group by mi.id_item, bpb.unit 
UNION
select mc.description, mi.id_item, mi.goods_code, mi.itemdesc, '0' qty_sa,'0' qty_in,sum(bppb.qty) qty_out, bppb.unit from bppb 
inner join masteritem mi on bppb.id_item = mi.id_item
inner join mapping_category mc on mi.n_code_category = mc.n_id
where bppbdate >= '$fromcri' and bppbdate <= '$tocri' and mi.mattype = 'N' $queryfilter and non_aktif = 'N' and bppb.bppbno like 'SJ-N%'
group by mi.id_item, bppb.unit
) mutasi
group by id_item, unit
having sum(qty_sa) != '0' or sum(qty_in) != '0' or sum(qty_out) != '0' or sum(qty_sa) + sum(qty_in) - sum(qty_out) != '0'
order by kode_brg asc

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
		echo "<td>$data[description]</td>";
        echo "<td>$data[id_item]</td>";
        echo "<td>$data[kode_brg]</td>";
        echo "<td>$data[nama_brg]</td>";
        echo "<td>$data[saldo_awal]</td>";
        echo "<td>$data[qtyrcv]</td>";
        echo "<td>$data[qtyout]</td>";
        echo "<td>$data[qty_akhir]</td>";
        echo "<td>$data[unit]</td>";
        echo "</tr>";    
        $no++; // menambah nilai nomor urut
        }
                // echo "<td>$data[tgl_input]</td>";
        ?>
      </tbody>
    </table>
  </div>
</div>