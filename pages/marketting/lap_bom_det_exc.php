<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../index.php"); }

if ($mode=='exc')
{ 
  header("Content-type: application/vnd-ms-excel"); 
  header("Content-Disposition: attachment; filename=lap_bom_detail.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0");
}

$mod = $_GET['mod'];

if (isset($_GET['from'])) {$from = date('d M Y',strtotime($_GET['from'])); } else {$from = "";}
if (isset($_GET['to'])) {$to = date('d M Y',strtotime($_GET['to'])); } else {$to = "";}
if (isset($_GET['txtid_buyer'])) { 
    $txtid_buyer = $_GET['txtid_buyer']; 
} else { 
    $txtid_buyer = "All"; // default
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
<?php
  # END COPAS ADD
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">List Laporan Bom Detail</h3>
  </div>
  <div>
    Periode : <?php echo $from; ?> - <?php echo $to; ?>
  </div>  
  <div class="box-body"> 
    <table id="examplefix" border="1" class="table table-bordered table-striped" style="font-family:Calibri;">
      <thead>
        <tr>
          <th>No</th>
          <th>SO</th>
          <th>No Ws</th>
          <th>Delivery Date</th>
          <th>Buyer</th>
          <th>Style #</th>
          <th>Product Group</th>
          <th>Product Item</th>
          <th>Color</th>
          <th>Size</th>
          <th>Item</th>
          <th>Qty Gmt</th>
          <th>Cons</th>
          <th>Qty Bom</th>
          <th>Unit</th>
          <th>Name</th>
          <th>Rule Bom</th>
          <th>Panel Name</th>
          <th>Dest</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $fromcri=date('Y-m-d',strtotime($from));
        $tocri=date('Y-m-d',strtotime($to));
        $txtid_buyer = isset($_GET['txtid_buyer']) ? $_GET['txtid_buyer'] : 'All';


        if ($txtid_buyer == 'All') {
    $sql_buyer = '';  // no filter
} else {
    $sql_buyer = "AND ms.id_supplier = '$txtid_buyer'";
}

        if($from == '' && $to == '')

        {
          $sql="";
        }

        else
        {            
         $sql="
SELECT 
k.id,
mi.id_item,
mi.itemdesc, sd.qty qty_gmt,k.cons,round(sd.qty*k.cons,2) qty_bom, k.unit,up.fullname,k.cancel,k.rule_bom,k.posno,mpan.nama_panel,k.dest, 'Material' as status, so.so_no, ac.kpno, ac.styleno , mp.product_group, mp.product_item, ms.supplier, 
ac.deldate,
sd.color,
sd.size
from bom_jo_item k
inner join so_det sd on k.id_so_det = sd.id
inner join so on sd.id_so  = so.id
INNER JOIN act_costing ac on so.id_cost = ac.id
inner join masterproduct mp on mp.id = ac.id_product
inner join mastersupplier ms on ms.id_supplier = ac.id_buyer
left join masterpanel mpan on k.id_panel=mpan.id
left join masteritem mi on k.id_item = mi.id_gen
left join userpassword up on k.username=up.username 
where k.status='M' and k.cancel = 'N'  and sd.cancel = 'N' and so.cancel_h = 'N' and ac.aktif = 'Y' and ac.deldate >= '$fromcri' and ac.deldate <= '$tocri' $sql_buyer
UNION ALL
select  
k.id,
mi.id_item,
mi.itemdesc, sd.qty qty_gmt,k.cons,round(sd.qty*k.cons,2) qty_bom, k.unit,up.fullname,k.cancel,k.rule_bom,k.posno,mpan.nama_panel,k.dest, 'Manufacturing' as status, so.so_no, ac.kpno, ac.styleno , mp.product_group, mp.product_item, ms.supplier, 
ac.deldate,
sd.color,
sd.size
from bom_jo_item k
inner join so_det sd on k.id_so_det = sd.id
inner join so on sd.id_so  = so.id
INNER JOIN act_costing ac on so.id_cost = ac.id
inner join masterproduct mp on mp.id = ac.id_product
inner join mastersupplier ms on ms.id_supplier = ac.id_buyer
left join masterpanel mpan on k.id_panel=mpan.id
left join masteritem mi on k.id_item = mi.id_item
left join userpassword up on k.username=up.username 
where k.status='P' and k.cancel = 'N'  and sd.cancel = 'N' and so.cancel_h = 'N' and ac.aktif = 'Y' and ac.deldate >= '$fromcri' and ac.deldate <= '$tocri' $sql_buyer 
ORDER by deldate asc, supplier asc, kpno asc,
    CASE 
        WHEN status = 'Material' THEN 1
        WHEN status = 'Manufacturing' THEN 2
    END
";
 }
      
        #echo $sql;
        $query = mysql_query($sql);
        $no = 1; 
        while($data = mysql_fetch_array($query))
        {
        echo "<tr>"; 
        echo "<td>$no</td>";
        echo "<td>$data[so_no]</td>";
        echo "<td>$data[kpno]</td>";
        echo "<td>$data[deldate]</td>";
        echo "<td>$data[supplier]</td>";
        echo "<td>$data[styleno]</td>";
        echo "<td>$data[product_group]</td>";
        echo "<td>$data[product_item]</td>";
        echo "<td>$data[color]</td>";
        echo "<td>$data[size]</td>";
        echo "<td>$data[itemdesc]</td>";
        echo "<td>$data[qty_gmt]</td>";
        echo "<td>$data[cons]</td>";
        echo "<td>$data[qty_bom]</td>";
        echo "<td>$data[unit]</td>";
        echo "<td>$data[fullname]</td>";
        echo "<td>$data[rule_bom]</td>";
        echo "<td>$data[nama_panel]</td>";
        echo "<td>$data[dest]</td>";
        echo "<td>$data[status]</td>";
        echo "</tr>";    
        $no++; // menambah nilai nomor urut
        }
                // echo "<td>$data[tgl_input]</td>";
        ?>
      </tbody>
    </table>
  </div>
</div>