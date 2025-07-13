<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../index.php"); }

$mod = $_GET['mod'];

if (isset($_GET['from'])) {$from = date('d M Y',strtotime($_GET['from'])); } else {$from = "";}
if (isset($_GET['to'])) {$to = date('d M Y',strtotime($_GET['to'])); } else {$to = "";}
if (isset($_GET['jenis_trans'])) {$jenis_trans = $_GET['jenis_trans']; } else {$jenis_trans = "All";}

if(isset($_POST['submit'])) //KLIK SUBMIT
{ $from=date('d M Y',strtotime($_POST['txtfrom']));
  $to=date('d M Y',strtotime($_POST['txtto']));
  $jenis_trans=$_POST['jenis_trans'];
  echo "<script>
    window.location.href='index.php?mod=$mod&from=$from&to=$to&jenis_trans=$jenis_trans';
    </script>";
}

if(isset($_POST['submitexc'])) //KLIK SUBMIT
{ $fromexc=date('d M Y',strtotime($_POST['txtfrom']));
  $toexc=date('d M Y',strtotime($_POST['txtto']));
  $jenis_trans=$_POST['jenis_trans'];
  echo "<script>
  window.open ('index.php?mod=lap_gen_req_exc&&mode=exc&dest=xls', '_blank');
    </script>";
     // window.location.href='index.php?mod=777&from=$from&to=$to&jenis_trans=$jenis_trans&mode=exc&dest=xls';   
}


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
  <div class='box'>
    <div class='box-body'>  
      <div class='row'>
        <?php 
         echo "<form method='post' name='form'>";
        ?>
<!--         <div class='col-md-2'>
          <div class='form-group'>
            <label>Tanggal Awal *</label>
            <input type='text' class='form-control' autocomplete='off' id='datepicker1' name='txtfrom' 
              placeholder='Masukkan Dari Tanggal' value='<?php echo $from;?>'>
          </div>
        </div> -->
<!--         <div class='col-md-2'>
          <div class='form-group'>
            <label>Tanggal Akhir *</label>
            <input type='text' class='form-control' autocomplete='off' id='datepicker2' name='txtto' 
              placeholder='Masukkan Sampai Tanggal' value='<?php echo $to; ?>'>
          </div>
        </div> -->
<!--         <div class='col-md-2'>
          <div class='form-group'>
            <label>Jenis Transaksi *</label>
<select class='form-control select2' id='jenis_trans'  name='jenis_trans' value='<?php echo $jenis_trans;?>'>
    <option value="All" <?php if($jenis_trans=="All"){echo "selected";} ?>>All</option>
    <option value="Penerimaan" <?php if($jenis_trans=="Penerimaan"){echo "selected";} ?>>Penerimaan</option>
    <option value="Pengeluaran" <?php if($jenis_trans=="Pengeluaran"){echo "selected";} ?>>Pengeluaran</option>
    <option value="Konfirmasi" <?php if($jenis_trans=="Konfirmasi"){echo "selected";} ?>>Konfirmasi</option>
    <option value="Cancel" <?php if($jenis_trans=="Cancel"){echo "selected";} ?>>Cancel</option>
</select> 
        </div>                
        </div> -->
    </div>
    <!-- <button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button> -->
    <button type='submit' name='submitexc' class='btn btn-success'>Export excel</button>
  </div>
        </form>
</div>
<?php
  # END COPAS ADD
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List Log</h3>
  </div>
  <div class="box-body"> 
    <table id="examplefix" border="1" class="table table-bordered table-striped responsiv">
      <thead>
        <tr>
            <th>Nomor Trans</th>
            <th>Tgl Req</th>
            <th>Created By</th>
            <th>Notes</th>
            <th>Item Name</th>
            <th>Qty</th>
            <th>Harga Req</th>
            <th>App 1</th>
            <th>App 2</th>
            <th>PO</th>
        </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $fromcri=date('Y-m-d',strtotime($from));
        $tocri=date('Y-m-d 23:59:59',strtotime($to));
            
        if ($from == '')
        {  
        $from_fix=date('Y-m-d');
        }
        else
        {
        $from_fix=$fromcri; 
        } 

        if ($to == '')
        {  
        $to_fix=date('Y-m-d 23:59:59');
        }
        else
        {
        $to_fix=$tocri; 
        }

         $sql="select * from
(
select a.id,
a.reqno,
a.reqdate,
a.notes,
mi.itemdesc,
a.username created_by,
a.app,
a.app_by,
a.app_date,
a.app2,
a.app_by2,
app_date2,
a.cancel_h,
a.jenis_po,
b.id id_reqnon_item,
b.id_item,
b.qty qty_req,
b.unit,
b.curr,
b.price price_req,
b.cancel
from reqnon_header a
inner join reqnon_item b on a.id = b.id_reqno
inner join masteritem mi on b.id_item = mi.id_item
where reqdate >= '2023-01-01'
)  gen_req
left join 
(
select 
pono,
podate,
supplier,
s.id_jo,
s.id_gen,
s.qty,
s.unit,
s.curr curr_po,
s.price price_po,
s.cancel
from po_header a 
inner join po_item s on a.id=s.id_po 
inner join mastersupplier d on a.id_supplier=d.id_supplier  
where jenis='N' and podate >= '2023-01-01'
) po on gen_req.id = po.id_jo and gen_req.id_item = po.id_gen
order by reqdate desc
";                       
        #echo $sql;
        $query = mysql_query($sql);
        $no = 1; 
        while($data = mysql_fetch_array($query))
        {
        echo "<tr>"; 
        echo "<td>$data[reqno]</td>";
        echo "<td>$data[reqdate]</td>";
        echo "<td>$data[created_by]</td>";
        echo "<td>$data[notes]</td>";
        echo "<td>$data[itemdesc]</td>";
        echo "<td>$data[qty_req]</td>";
        echo "<td>$data[price_req]</td>";
        echo "<td>".$data['app_by']." (".$data['app'].")"."</td>";   
        echo "<td>".$data['app_by2']." (".$data['app2'].")"."</td>"; 
        echo "<td>$data[pono]</td>";     
        echo "</tr>";    
        $no++; // menambah nilai nomor urut
        }
                // echo "<td>$data[tgl_input]</td>";
        ?>
      </tbody>
    </table>
  </div>
</div>