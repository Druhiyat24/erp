<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../index.php"); }

$mod = $_GET['mod'];

if (isset($_GET['from'])) {$from = date('d M Y',strtotime($_GET['from'])); } else {$from = "";}
if (isset($_GET['to'])) {$to = date('d M Y',strtotime($_GET['to'])); } else {$to = "";}

if(isset($_POST['submit'])) //KLIK SUBMIT
{ $from=date('d M Y',strtotime($_POST['txtfrom']));
  $to=date('d M Y',strtotime($_POST['txtto']));
  echo "<script>
    window.location.href='index.php?mod=$mod&from=$from&to=$to';
    </script>";
}

if(isset($_POST['submitexc'])) //KLIK SUBMIT
{ $fromexc=date('d M Y',strtotime($_POST['txtfrom']));
  $toexc=date('d M Y',strtotime($_POST['txtto']));
  echo "<script>
  window.open ('index.php?mod=cek_data_ws_exc&from=$fromexc&to=$toexc&mode=exc&dest=xls', '_blank');
    </script>";
     // window.location.href='index.php?mod=777&from=$from&to=$to&jenis_trans=$jenis_trans&mode=exc&dest=xls';   
}


echo "<script type='text/javascript'>";


echo "</script>";

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
        <div class='col-md-2'>
          <div class='form-group'>
            <label>Tanggal Awal *</label>
            <input type='text' class='form-control' autocomplete='off' id='datepickertgl_awal' name='txtfrom' 
              placeholder='Masukkan Dari Tanggal' value='<?php echo $from;?>'>
          </div>
        </div>
        <div class='col-md-2'>
          <div class='form-group'>
            <label>Tanggal Akhir *</label>
            <input type='text' class='form-control' autocomplete='off' id='datepickertgl_akhir' name='txtto' 
              placeholder='Masukkan Sampai Tanggal' value='<?php echo $to; ?>'>
          </div>
        </div>
    </div>
    <button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button>
    <button type='submit' name='submitexc' class='btn btn-success'>Export excel</button>
  </div>
        </form>
</div>
<?php
  # END COPAS ADD
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List Checking WS</h3>
  </div>
  <div class="box-body"> 
    <table id="example1" border="1" class="table table-bordered table-striped">
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
         $sql="
select ac.kpno,mi.itemdesc, ms.supplier, ms.country, po.qty_po, ac.jns_so, ac.brand,ac.buyer,cost_date  from bom_jo_item k
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
select ac.id_buyer,ac.styleno,jd.id_jo, ac.kpno, so.jns_so, ac.brand, ms.supplier buyer,cost_date from jo_det jd
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