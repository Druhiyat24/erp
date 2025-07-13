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
  header("Content-Disposition: attachment; filename=lap_list_barcode.xls");//ganti nama sesuai keperluan 
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
    <h3 class="box-title">List BPB Barcode</h3>
  </div>
  <div>
    Periode : <?php echo $from; ?> - <?php echo $to; ?>
  </div>  
  <div class="box-body"> 
    <table id="tbl_mut_brgjadi" border="1" class="table table-bordered table-striped" style="font-family:Calibri;">
      <thead>
        <tr>
            <th>No BPB</th>
            <th>Tgl. BPB</th>
            <th>Tujuan</th>
            <th>No Barcode</th>
            <th>Roll No</th>
            <th>Lot No</th>
            <th>Roll Qty</th>
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
select br.id, roll_no, lot_no, roll_qty, br.unit, bpbno_int, brh.id_item, brh.id_jo, ac.kpno, mi.goods_code, mi.itemdesc,supplier,bpbdate
from bpb_roll_h brh
inner join (select * from bpb where bpbno like'F%' and bpbdate >= '$fromcri' and bpbdate <= '$tocri' group by bpbno) bpb on brh.bpbno = bpb.bpbno
inner join bpb_roll br on brh.id = br.id_h
inner join jo_det jd on brh.id_jo = jd.id_jo
inner join so on jd.id_so = so.id
inner join act_costing ac on so.id_cost = ac.id
inner join masteritem mi on brh.id_item = mi.id_item
inner join mastersupplier ms on bpb.id_supplier = ms.id_supplier
";
 } 
        #echo $sql;
        $query = mysql_query($sql);
        $no = 1; 
        while($data = mysql_fetch_array($query))
        {
        echo "<tr>"; 
        // echo "<td>$no</td>";
        echo "<td>$data[bpbno_int]</td>";
        echo"<td>".fd_view($data[bpbdate])."</td>";
        echo "<td>$data[supplier]</td>";       
        echo "<td>$data[id]</td>";
        echo "<td class='text'>$data[roll_no]</td>";
        echo "<td class='text'>$data[lot_no]</td>";
        echo "<td>$data[roll_qty]</td>";
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