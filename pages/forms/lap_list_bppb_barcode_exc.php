<style>
  .text{
  mso-number-format:"\@";/*force text*/
}
</style>  
<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../index.php"); }

if ($mode=='exc')
{ 
  header("Content-type: application/vnd-ms-excel"); 
  header("Content-Disposition: attachment; filename=lap_list_bppb_barcode.xls");//ganti nama sesuai keperluan 
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
    <h3 class="box-title">List Laporan BPPB Barcode</h3>
  </div>
  <div>
    Periode : <?php echo $from; ?> - <?php echo $to; ?>
  </div>  
  <div class="box-body"> 
    <table id="tbl_mut_brgjadi" border="1" class="table table-bordered table-striped" style="font-family:Calibri;">
      <thead>
        <tr>
            <th>No BPPB</th>
            <th>Tgl BPPB</th>
            <th>Tujuan</th>
            <th>No Barcode</th>
            <th>Roll No</th>
            <th>Lot No</th>
            <th>Qty Out</th>
            <th>Unit</th>
            <th>ID Item</th>
            <th>ID JO</th>
            <th>No WS</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
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
         $sql="
select a.bppbno,
a.bppbno_int,
a.bppbdate,
b.id_roll barcode,
b.qty_roll,
b.unit,
b.id_item,
mi.itemdesc,
mi.goods_code,
ac.kpno,
b.roll_no,
b.lot_no,
b.id_jo,
a.supplier
from
(
select bppbno, bppbno_int, bppbdate , supplier
from bppb 
inner join mastersupplier ms on bppb.id_supplier = ms.id_supplier
where bppbdate >= '$fromcri' and bppb.bppbdate <= '$tocri' and bppbno like 'SJ-F%' and bppbno_int like 'GK/%'
group by bppbno
) a
left join 
(
select a.*,br.unit, br.roll_no,br.lot_no from bppb_barcode_det a 
inner join bppb on a.bppbno = bppb.bppbno
inner join bpb_roll br on a.id_roll = br.id
where bppbdate >= '$fromcri' and bppb.bppbdate <= '$tocri' and a.bppbno like 'SJ-F%' 
and a.bppbno_int like 'GK/%'
) b on a.bppbno = b.bppbno
inner join masteritem mi on b.id_item = mi.id_item 
inner join jo_det jd on b.id_jo = jd.id_jo
inner join so on jd.id_so = so.id
inner join act_costing ac on so.id_cost = ac.id
order by bppbdate asc
";
 } 
        #echo $sql;
        $query = mysql_query($sql);
        $no = 1; 
        while($data = mysql_fetch_array($query))
        {
        echo "<tr>"; 
        // echo "<td>$no</td>";
        echo "<td>$data[bppbno_int]</td>";
        echo"<td>".fd_view($data[bppbdate])."</td>";
        echo "<td>$data[supplier]</td>";
        echo "<td>$data[barcode]</td>";
        echo "<td class='text'>$data[roll_no]</td>";
        echo "<td class='text'>$data[lot_no]</td>";
        echo "<td>$data[qty_roll]</td>";
        echo "<td>$data[unit]</td>";
        echo "<td>$data[id_item]</td>";
        echo "<td>$data[id_jo]</td>";
        echo "<td>$data[kpno]</td>";
        echo "<td>$data[goods_code]</td>";
        echo "<td>$data[itemdesc]</td>";                        
        echo "</tr>";    
        // $no++; // menambah nilai nomor urut
        }
                // echo "<td>$data[tgl_input]</td>";
        ?>
      </tbody>
    </table>
  </div>
</div>