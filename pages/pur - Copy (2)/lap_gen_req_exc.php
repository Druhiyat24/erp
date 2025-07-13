<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { header("location:../../index.php"); }

if ($mode=='exc')
{ 
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=lap_general_request.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0");
}

$mod = $_GET['mod'];

// if (isset($_GET['from'])) {$from = date('d M Y',strtotime($_GET['from'])); } else {$from = "";}
// if (isset($_GET['to'])) {$to = date('d M Y',strtotime($_GET['to'])); } else {$to = "";}
// if (isset($_GET['jenis_trans'])) {$jenis_trans = $_GET['jenis_trans']; } else {$jenis_trans = "All";}


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
    <h3 class="box-title">List General Request</h3>
  </div>
<!--   <div>
    Periode : <?php echo $from; ?> - <?php echo $to; ?>
  </div>   -->
  <div class="box-body"> 
    <table id="tbl_list_in_out" border="1" class="table table-bordered table-striped">
      <thead>
        <tr>
            <th>Nomor Trans</th>
            <th>Tgl Req</th>
            <th>Created By</th>
            <th>Notes</th>
            <th>Item Name</th>
            <th>Qty</th>
            <th>Unit</th>
            <th>Harga Req</th>
            <th>App</th>
            <th>App By</th>
            <th>Tgl. App</th>
            <th>App 2</th>
            <th>App By 2</th>
            <th>Tgl. App</th>
            <th>Jenis Req</th>
            <th>PO</th>
            <th>Tgl. PO</th>
            <th>Supplier</th>
            <th>Qty PO</th>
            <th>Harga PO</th>      
            <th>Curr PO</th>  
        </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE

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
          $app = $data[app];
          $app2 = $data[app2];
          $po = $data[pono];

          if ($app == 'A' && $po != '')
          {
          $color = 'style="background-color:MediumSeaGreen"';
          }
          else
          {
          $color = 'style="background-color:white"';        
          }

          // if ($cek == '0') {
          //   $status = 'BELUM';
          //   $color = 'style="background-color:#C9A9A6"';
          // } else {
          //   $status = 'SUDAH';
          //   $color = 'style="background-color:#737CA1"';
          // }

        echo "<tr $color>"; 
        echo "<td>$data[reqno]</td>";
        echo "<td>$data[reqdate]</td>";
        echo "<td>$data[created_by]</td>";
        echo "<td>$data[notes]</td>";
        echo "<td>$data[itemdesc]</td>";
        echo "<td>$data[qty_req]</td>";
        echo "<td>$data[unit]</td>";
        echo "<td>$data[price_req]</td>";
        echo "<td>$data[app]</td>";   
        echo "<td>$data[app_by]</td>";      
        echo "<td>$data[app_date]</td>"; 
        echo "<td>$data[app2]</td>"; 
        echo "<td>$data[appby2]</td>"; 
        echo "<td>$data[app_date2]</td>"; 
        echo "<td>$data[jenis_po]</td>"; 
        echo "<td>$data[pono]</td>"; 
        echo "<td>$data[podate]</td>"; 
        echo "<td>$data[supplier]</td>"; 
        echo "<td>$data[qty]</td>"; 
        echo "<td>$data[price_po]</td>"; 
        echo "<td>$data[curr_po]</td>";

        echo "</tr>";    
        $no++; // menambah nilai nomor urut
        }
                // echo "<td>$data[tgl_input]</td>";
        ?>
      </tbody>
    </table>
  </div>
</div>