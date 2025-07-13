<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../index.php"); }

if ($mode=='exc')
{ 
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=lap_mutasi_brgjadi.xls");//ganti nama sesuai keperluan 
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
    <h3 class="box-title">List Laporan Mutasi Barang Jadi</h3>
  </div>
  <div>
    Periode : <?php echo $from; ?> - <?php echo $to; ?>
  </div>  
  <div class="box-body"> 
    <table id="tbl_mut_brgjadi" border="1" class="table table-bordered table-striped">
      <thead>
        <tr>
            <th>No</th>
            <th>Id Item</th>
            <th>Id So Det</th>
            <th>Kode Barang</th>
            <th>Style</th>
            <th>No WS</th>
            <th>Color</th>
            <th>Size</th>
            <th>Dest</th>
            <th>Unit</th>
            <th>Saldo Awal</th>
            <th>Penerimaan</th>
            <th>Pengeluaran</th>
            <th>Saldo Akhir</th>
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
         $sql="select ms.goods_code,ms.itemname,ms.styleno,ms.kpno,ms.color,ms.size,ms.country,mutasi.id_item, mutasi.id_so_det, sum(saldo_awal) as saldo_awal, sum(penerimaan) as penerimaan, sum(pengeluaran) as pengeluaran, sum(saldo_awal) + sum(penerimaan) - sum(pengeluaran) as saldo_akhir from
(
select * from
(
select saldoawal.id_item,saldoawal.id_so_det,sum(saldo_awal) + sum(penerimaan) -  sum(pengeluaran) saldo_awal, '0' penerimaan, '0' pengeluaran
from
(
select id_item,id_so_det, saldo as saldo_awal, '0' as penerimaan, '0' as pengeluaran from saldoawal_fg where periode = '2022-10-01'
union
select id_item, id_so_det, '0' as saldo_awal, sum(qty) as penerimaan, '0' as pengeluaran from bpb where 
bpbdate >='2022-10-01' and bpbdate < '$fromcri'
and bpbno like 'FG%'
group by id_item, id_so_det
UNION
select id_item, id_so_det, '0' as saldo_awal, '0' as penerimaan, sum(qty) as pengeluaran from bppb where 
bppbdate >='2022-10-01' and bppbdate <'$fromcri'
and bppbno like 'SJ-FG%'
group by id_item, id_so_det
)
saldoawal
inner join masterstyle ms on saldoawal.id_item = ms.id_item and saldoawal.id_so_det = ms.id_so_det
group by saldoawal.id_item, saldoawal.id_so_det
) sa
union
select id_item, id_so_det, '0' as saldo_awal, sum(qty) as penerimaan, '0' as pengeluaran from bpb where 
bpbdate >= '$fromcri' and bpbdate <= '$tocri'
and bpbno like 'FG%'
group by id_item, id_so_det
union
select id_item, id_so_det, '0' as saldo_awal, '0' as penerimaan, sum(qty) as pengeluaran from bppb where 
bppbdate >= '$fromcri' and bppbdate <= '$tocri'
and bppbno like 'SJ-FG%'
group by id_item, id_so_det
) 
mutasi
inner join masterstyle ms on mutasi.id_item = ms.id_item and mutasi.id_so_det = ms.id_so_det
group by mutasi.id_item, mutasi.id_so_det
having sum(saldo_awal) != '0' or sum(penerimaan) != '0' or sum(pengeluaran) != '0' or sum(saldo_awal) + sum(penerimaan) - sum(pengeluaran) != '0'


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
        echo "<td>$data[id_item]</td>";
        echo "<td>$data[id_so_det]</td>";
        echo "<td>$data[goods_code]</td>";
        echo "<td>$data[styleno]</td>";
        echo "<td>$data[kpno]</td>";
        echo "<td>$data[color]</td>";
        echo "<td>$data[size]</td>";
        echo "<td>$data[country]</td>";
        echo "<td>Pcs</td>";
        echo "<td>$data[saldo_awal]</td>";
        echo "<td>$data[penerimaan]</td>";
        echo "<td>$data[pengeluaran]</td>";
        echo "<td>$data[saldo_akhir]</td>";
        echo "</tr>";    
        $no++; // menambah nilai nomor urut
        }
                // echo "<td>$data[tgl_input]</td>";
        ?>
      </tbody>
    </table>
  </div>
</div>