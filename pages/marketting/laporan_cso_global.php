<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

if (isset($_GET['mode'])) { $mode = $_GET['mode']; } else { $mode = ""; }
$rpt = $_GET['mod'];

# START CEK HAK AKSES KEMBALI
// $akses = flookup("lap_inventory", "userpassword", "username='$user'");
// if ($akses == "0") {
//   echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>";
// }
# END CEK HAK AKSES KEMBALI

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$logo_company = $rscomp["logo_company"];
?>
<?php 

  if (isset($_POST['submit'])) //KLIK SUBMIT
  {
    $from = date('Y-m-d', strtotime($_POST['txtfrom']));
    $to = date('Y-m-d', strtotime($_POST['txtto']));
    $tipe_inv = '';
    $nama_supp = '';
    $nama_buyer = '';
    echo "<script>
    window.open ('?mod=lap_excel_cso_global&from=$from&to=$to&dest=xls', '_blank');
    </script>";
  } else {
  }

  if (isset($_POST['submit_cari'])) {
    $from = date('Y-m-d', strtotime($_POST['txtfrom']));
    $to = date('Y-m-d', strtotime($_POST['txtto']));
    $tipe_inv = '';
    $nama_supp = '';
    $nama_buyer = '';
  }

  ?>
<!--   <script type='text/javascript'>
    function getdetail() {
      var tipe_inv = document.form.tipe_inv.value;
      var html = $.ajax({
        type: "POST",
        url: 'ajax_lap_data.php?modeajax=view_list_tipe',
        data: {
          tipe_data: tipe_data,
        },
        async: false
      }).responseText;
      if (html) {
        $("#cbotipe").html(html);
      }
    };
  </script> -->


  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form'>

        </div>
        <div class='row'>
          <div class='col-md-2'>
            <div class='form-group'>
              <label>Dari *</label>
              <input type='text' class='form-control' autocomplete='off' id='datepicker1' name='txtfrom' required placeholder='Masukkan Dari Tanggal' value="<?php 
              $txtfrom = isset($_POST['txtfrom']) ? $_POST['txtfrom']: null;            
              if(!empty($_POST['txtfrom'])) {
                echo $_POST['txtfrom'];
              }
              else{
                echo date("d M Y");
              } ?>">
            </div>
          </div>

          <div class='col-md-2'>
            <div class='form-group'>
              <label>Sampai *</label>
              <input type='text' class='form-control' autocomplete='off' id='datepicker2' name='txtto' required placeholder='Masukkan Dari Tanggal' value="<?php 
              $txtto = isset($_POST['txtto']) ? $_POST['txtto']: null;            
              if(!empty($_POST['txtto'])) {
                echo $_POST['txtto'];
              }
              else{
                echo date("d M Y");
              } ?>">
            </div>
          </div>

          <div class='col-md-6'>
            <div class='form-group' style='padding-top:25px'>
              <button type='submit' name='submit_cari' class='btn btn-info'><i class="fa fa-search" aria-hidden="true"></i> Search</button>
              <button type='submit' name='submit' class='btn btn-success'><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel</button>
            </div>
          </div>
        </div>
      </div>
      <table id="examplefix3" class="display responsive" style="width:100%;font-size:12px;">
        <thead>
          <tr>
            <th>WS</th>
            <th>Buyer</th>
            <th>Style</th>
            <th>Product Type</th>
            <th>Season</th>
            <th>Curr</th>
            <th>SO Date</th>
            <th>Status SO</th>
            <th>Qty SO</th>
            <th>Sales Price</th>
            <th>CT Date</th>
            <th>Status</th>
            <th>Qty</th>
            <th>Material</th>
            <th>Manufaturing</th>
            <th>Others</th>
            <th>Total Cost</th>
            <th>Sales Price - Total Cost</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE


          $query = mysql_query("select a.cost_no,kpno,supplier,styleno,product_item,season_desc,curr,so_date,status,qty_so,price_so,cost_date,status_cost,qty_cost,COALESCE(ttl_fabric,0) ttl_fabric,COALESCE(ttl_accsew,0) ttl_accsew,COALESCE(ttl_accpack,0) ttl_accpack,(COALESCE(ttl_fabric,0) + COALESCE(ttl_accsew,0) + COALESCE(ttl_accpack,0)) ttl_material,COALESCE(ttl_cmt,0) ttl_cmt,COALESCE(ttl_embro,0) ttl_embro,COALESCE(ttl_wash,0) ttl_wash,COALESCE(ttl_print,0) ttl_print,COALESCE(ttl_wrapbut,0) ttl_wrapbut,COALESCE(ttl_compbut,0) ttl_compbut,COALESCE(ttl_label,0) ttl_label,COALESCE(ttl_laser,0) ttl_laser,(COALESCE(ttl_cmt,0) + COALESCE(ttl_embro,0) + COALESCE(ttl_wash,0) + COALESCE(ttl_print,0) + COALESCE(ttl_wrapbut,0) + COALESCE(ttl_compbut,0) + COALESCE(ttl_label,0) + COALESCE(ttl_laser,0)) ttl_manufacturing,COALESCE(ttl_develop,0) ttl_develop,COALESCE(ttl_overhead,0) ttl_overhead,COALESCE(ttl_market,0) ttl_market,COALESCE(ttl_shipp,0) ttl_shipp,COALESCE(ttl_import,0) ttl_import,COALESCE(ttl_handl,0) ttl_handl,COALESCE(ttl_test,0) ttl_test,COALESCE(ttl_fabhandl,0) ttl_fabhandl,COALESCE(ttl_service,0) ttl_service ,COALESCE(ttl_clearcost,0) ttl_clearcost ,COALESCE(ttl_development,0) ttl_development ,COALESCE(ttl_unexcost,0) ttl_unexcost ,COALESCE(ttl_managementfee,0) ttl_managementfee ,COALESCE(ttl_profit,0) ttl_profit ,(COALESCE(ttl_develop,0) + COALESCE(ttl_overhead,0) + COALESCE(ttl_market,0) + COALESCE(ttl_shipp,0) + COALESCE(ttl_import,0) + COALESCE(ttl_handl,0) + COALESCE(ttl_test,0) + COALESCE(ttl_fabhandl,0) + COALESCE(ttl_service,0) + COALESCE(ttl_clearcost,0) + COALESCE(ttl_development,0) + COALESCE(ttl_unexcost,0) + COALESCE(ttl_managementfee,0) + COALESCE(ttl_profit,0)) ttl_others
           from (select a.cost_no,a.kpno,b.supplier,styleno,product_item,season_desc,if(so.curr is null,a.curr,so.curr) curr,so_date,'' status,so.qty qty_so,so.fob price_so,cost_date,'' status_cost, a.qty qty_cost  from act_costing a INNER JOIN mastersupplier b ON a.id_buyer=b.Id_Supplier inner join masterproduct mp on a.id_product=mp.id left join so on so.id_cost = a.id left join masterseason ms on ms.id_season = so.id_season where cost_date BETWEEN '$from' and '$to' GROUP BY cost_no) a left join (select cost_no, sum(ttl_fabric) ttl_fabric, sum(ttl_accsew) ttl_accsew, sum(ttl_accpack) ttl_accpack from (select cost_no,case when mattype = 'FABRIC' then total end as ttl_fabric,
           case when mattype = 'ACCESORIES SEWING' then total end as ttl_accsew,
           case when mattype = 'ACCESORIES PACKING' then total end as ttl_accpack from (SELECT cost_no,mattype,IF(curr = 'IDR',val_idr,val_usd) total from act_material where cost_date BETWEEN '$from' and '$to') a) a GROUP BY cost_no) b on b.cost_no = a.cost_no left join (select cost_no, sum(ttl_cmt) ttl_cmt, sum(ttl_embro) ttl_embro, sum(ttl_wash) ttl_wash, sum(ttl_print) ttl_print, sum(ttl_wrapbut) ttl_wrapbut, sum(ttl_compbut) ttl_compbut, sum(ttl_label) ttl_label, sum(ttl_laser) ttl_laser from (select cost_no,case when mattype = 'CMT' then total end as ttl_cmt,
           case when mattype = 'EMBRODEIRY' then total end as ttl_embro,
           case when mattype = 'WASHING' then total end as ttl_wash,
           case when mattype = 'PRINTING' then total end as ttl_print,
           case when mattype = 'WRAPPED BUTTON' then total end as ttl_wrapbut,
           case when mattype = 'COMPLEXITY MAKLOON BUTTON' then total end as ttl_compbut,
           case when mattype = 'LABEL PRINT' then total end as ttl_label,
           case when mattype = 'LASER CUTTING' then total end as ttl_laser from (SELECT cost_no,mattype,IF(curr = 'IDR',val_idr,val_usd) total from act_manufacturing where cost_date BETWEEN '$from' and '$to') a) a GROUP BY cost_no) c on c.cost_no = a.cost_no left join ( select cost_no, sum(ttl_develop) ttl_develop, sum(ttl_overhead) ttl_overhead, sum(ttl_market) ttl_market, sum(ttl_shipp) ttl_shipp, sum(ttl_import) ttl_import, sum(ttl_handl) ttl_handl, sum(ttl_test) ttl_test, sum(ttl_fabhandl) ttl_fabhandl, sum(ttl_service) ttl_service, sum(ttl_clearcost) ttl_clearcost , sum(ttl_development) ttl_development, sum(ttl_unexcost) ttl_unexcost, sum(ttl_managementfee) ttl_managementfee, sum(ttl_profit) ttl_profit from (select cost_no,case when mattype = 'DEVELOPMENT' then total end as ttl_develop,
           case when mattype = 'OVERHEAD' then total end as ttl_overhead,
           case when mattype = 'MARKETING' then total end as ttl_market,
           case when mattype = 'SHIPPING' then total end as ttl_shipp,
           case when mattype = 'IMPORT COST' then total end as ttl_import,
           case when mattype = 'HANDLING' then total end as ttl_handl,
           case when mattype = 'TESTING' then total end as ttl_test,
           case when mattype = 'FABRIC HANDLING' then total end as ttl_fabhandl,
           case when mattype = 'SERVICE CHARGE' then total end as ttl_service,
           case when mattype = 'CLEARANCE  COST' then total end as ttl_clearcost,
           case when mattype = 'DEVELOPMENT' then '0' end as ttl_development,
           case when mattype = 'UNEXPECTED COST' then total end as ttl_unexcost,
           case when mattype = 'MANAGEMENT FEE' then total end as ttl_managementfee,
           case when mattype = 'PROFIT' then total end as ttl_profit
            from (SELECT cost_no,mattype,IF(curr = 'IDR',val_idr,val_usd) total from act_others where cost_date BETWEEN '$from' and '$to') a) a GROUP BY cost_no) d on d.cost_no = a.cost_no ");


$no = 1;
$total_costing = 0;
$total_bal = 0;
while ($data = mysql_fetch_array($query)) {
  $total_costing = 0;
  $total_bal = 0;
  $ttl_material = $data[ttl_material];
  $ttl_manufacturing = $data[ttl_manufacturing];
  $ttl_others = $data[ttl_others];
  $price_so = $data[price_so];
  $total_costing = $ttl_material + $ttl_manufacturing + $ttl_others;
  $total_bal = $price_so - $total_costing;


  echo "<tr>";
  echo "<td>$data[kpno]</td>";
  echo "<td>$data[supplier]</td>";
  echo "<td>$data[styleno]</td>";
  echo "<td>$data[product_item]</td>";
  echo "<td>$data[season_desc]</td>";
  echo "<td>$data[curr]</td>";
  echo "<td>$data[so_date]</td>";
  echo "<td>$data[status]</td>";
  echo "<td>$data[qty_so]</td>";
  echo "<td>".number_format($data[price_so],4)."</td>";
  echo "<td>$data[cost_date]</td>";
  echo "<td>$data[status_cost]</td>";
  echo "<td>$data[qty_cost]</td>";
  echo "<td>".number_format($data[ttl_material],4)."</td>";
  echo "<td>".number_format($data[ttl_manufacturing],4)."</td>";
  echo "<td>".number_format($data[ttl_others],4)."</td>";
  echo "<td>".number_format($total_costing,4)."</td>";
  echo "<td>".number_format($total_bal,4)."</td>";
  echo "</tr>";
            $no++; // menambah nilai nomor urut
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</form>
</div>
</div>
