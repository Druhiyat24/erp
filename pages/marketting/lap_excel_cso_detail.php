<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}
if (isset($_SESSION['username'])) {
  $user = $_SESSION['username'];
} else {
  header("location:../../index.php");
}


if (isset($_GET['from'])) {
  $from = date('d M Y', strtotime($_GET['from']));
} else {
  $from = "";
}
if (isset($_GET['to'])) {
  $to = date('d M Y', strtotime($_GET['to']));
} else {
  $to = "";
}
;


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=LAPORAN COSTING vs SO DETAIL.xls"); //ganti nama sesuai keperluan 
header("Pragma: no-cache");
header("Expires: 0");

?>
<?php
# END COPAS ADD
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">LIST DATA COSTING DETAIL</h3>
  </div>
  <div>
    Periode : <?php echo $from; ?> - <?php echo $to; ?>
  </div>
  <div class="box-body">
    <table border="1" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th rowspan="2">No</th>
          <th rowspan="2">WS</th>
          <th rowspan="2">Buyer</th>
          <th rowspan="2">Style</th>
          <th rowspan="2">Product Type</th>
          <th rowspan="2">Season</th>
          <th rowspan="2">Curr</th>
          <th colspan="4" style="text-align: left;">Sales Order</th>
          <th colspan="31" style="text-align: left;">Costing Breakdown</th>
          <th rowspan="2">Sales Price - Total Cost</th>
        </tr>
        <tr>
         <th>SO Date</th>
         <th>Status SO</th>
         <th>Qty SO</th>
         <th>Sales Price</th>
         <th>CT Date</th>
         <th>Status</th>
         <th>Qty</th>
         <th>Fabric</th>
         <th>Acc Sewing</th>
         <th>Acc Packing</th>
         <th>Total Material</th>
         <th>CMT</th>
         <th>Embrodery</th>
         <th>Washing</th>
         <th>Printing</th>
         <th>Wrapped Button</th>
         <th>Complexity Makloon Button</th>
         <th>Label Print</th>
         <th>Laser Cutting</th>
         <th>Total Manufaturing</th>
         <th>Development</th>
         <th>Overhead</th>
         <th>Marketing</th>
         <th>Shipping</th>
         <th>Import</th>
         <th>Handing</th>
         <th>Testing</th>
         <th>Fabric Handling</th>
         <th>Service Charge</th>
         <th>Clearance Cost</th>
         <th>Unexpected Cost</th>
         <th>Management Fee</th>
         <th>Profit</th>
         <th>Total Others</th>
         <th>Total Cost</th>
       </tr>
     </thead>
     <tbody>
      <?php
          # QUERY TABLE
      $fromcri = date('Y-m-d', strtotime($from));
      $tocri = date('Y-m-d', strtotime($to));


      $sql = "select a.cost_no,kpno,supplier,styleno,product_item,season_desc,curr,so_date,status,qty_so,price_so,cost_date,status_cost,qty_cost,COALESCE(ttl_fabric,0) ttl_fabric,COALESCE(ttl_accsew,0) ttl_accsew,COALESCE(ttl_accpack,0) ttl_accpack,(COALESCE(ttl_fabric,0) + COALESCE(ttl_accsew,0) + COALESCE(ttl_accpack,0)) ttl_material,COALESCE(ttl_cmt,0) ttl_cmt,COALESCE(ttl_embro,0) ttl_embro,COALESCE(ttl_wash,0) ttl_wash,COALESCE(ttl_print,0) ttl_print,COALESCE(ttl_wrapbut,0) ttl_wrapbut,COALESCE(ttl_compbut,0) ttl_compbut,COALESCE(ttl_label,0) ttl_label,COALESCE(ttl_laser,0) ttl_laser,(COALESCE(ttl_cmt,0) + COALESCE(ttl_embro,0) + COALESCE(ttl_wash,0) + COALESCE(ttl_print,0) + COALESCE(ttl_wrapbut,0) + COALESCE(ttl_compbut,0) + COALESCE(ttl_label,0) + COALESCE(ttl_laser,0)) ttl_manufacturing,COALESCE(ttl_develop,0) ttl_develop,COALESCE(ttl_overhead,0) ttl_overhead,COALESCE(ttl_market,0) ttl_market,COALESCE(ttl_shipp,0) ttl_shipp,COALESCE(ttl_import,0) ttl_import,COALESCE(ttl_handl,0) ttl_handl,COALESCE(ttl_test,0) ttl_test,COALESCE(ttl_fabhandl,0) ttl_fabhandl,COALESCE(ttl_service,0) ttl_service, COALESCE(ttl_clearcost,0) ttl_clearcost ,COALESCE(ttl_development,0) ttl_development ,COALESCE(ttl_unexcost,0) ttl_unexcost ,COALESCE(ttl_managementfee,0) ttl_managementfee ,COALESCE(ttl_profit,0) ttl_profit ,(COALESCE(ttl_develop,0) + COALESCE(ttl_overhead,0) + COALESCE(ttl_market,0) + COALESCE(ttl_shipp,0) + COALESCE(ttl_import,0) + COALESCE(ttl_handl,0) + COALESCE(ttl_test,0) + COALESCE(ttl_fabhandl,0) + COALESCE(ttl_service,0) + COALESCE(ttl_clearcost,0) + COALESCE(ttl_development,0) + COALESCE(ttl_unexcost,0) + COALESCE(ttl_managementfee,0) + COALESCE(ttl_profit,0)) ttl_others
           from (select a.cost_no,a.kpno,b.supplier,styleno,product_item,season_desc,if(so.curr is null,a.curr,so.curr) curr,so_date,IF(so.cancel_h = 'Y','CANCEL','-') status,so.qty qty_so,so.fob price_so,cost_date,a.status status_cost, a.qty qty_cost  from act_costing a INNER JOIN mastersupplier b ON a.id_buyer=b.Id_Supplier inner join masterproduct mp on a.id_product=mp.id left join so on so.id_cost = a.id left join masterseason ms on ms.id_season = so.id_season where cost_date BETWEEN '$fromcri' and '$tocri' GROUP BY cost_no) a left join (select cost_no, sum(ttl_fabric) ttl_fabric, sum(ttl_accsew) ttl_accsew, sum(ttl_accpack) ttl_accpack from (select cost_no,case when mattype = 'FABRIC' then total end as ttl_fabric,
           case when mattype = 'ACCESORIES SEWING' then total end as ttl_accsew,
           case when mattype = 'ACCESORIES PACKING' then total end as ttl_accpack from (SELECT cost_no,mattype,IF(curr = 'IDR',val_idr,val_usd) total from act_material where cost_date BETWEEN '$fromcri' and '$tocri') a) a GROUP BY cost_no) b on b.cost_no = a.cost_no left join (select cost_no, sum(ttl_cmt) ttl_cmt, sum(ttl_embro) ttl_embro, sum(ttl_wash) ttl_wash, sum(ttl_print) ttl_print, sum(ttl_wrapbut) ttl_wrapbut, sum(ttl_compbut) ttl_compbut, sum(ttl_label) ttl_label, sum(ttl_laser) ttl_laser from (select cost_no,case when mattype = 'CMT' then total end as ttl_cmt,
           case when mattype = 'EMBRODEIRY' then total end as ttl_embro,
           case when mattype = 'WASHING' then total end as ttl_wash,
           case when mattype = 'PRINTING' then total end as ttl_print,
           case when mattype = 'WRAPPED BUTTON' then total end as ttl_wrapbut,
           case when mattype = 'COMPLEXITY MAKLOON BUTTON' then total end as ttl_compbut,
           case when mattype = 'LABEL PRINT' then total end as ttl_label,
           case when mattype = 'LASER CUTTING' then total end as ttl_laser from (SELECT cost_no,mattype,IF(curr = 'IDR',val_idr,val_usd) total from act_manufacturing where cost_date BETWEEN '$fromcri' and '$tocri') a) a GROUP BY cost_no) c on c.cost_no = a.cost_no left join (select cost_no, sum(ttl_develop) ttl_develop, sum(ttl_overhead) ttl_overhead, sum(ttl_market) ttl_market, sum(ttl_shipp) ttl_shipp, sum(ttl_import) ttl_import, sum(ttl_handl) ttl_handl, sum(ttl_test) ttl_test, sum(ttl_fabhandl) ttl_fabhandl, sum(ttl_service) ttl_service, sum(ttl_clearcost) ttl_clearcost , sum(ttl_development) ttl_development, sum(ttl_unexcost) ttl_unexcost, sum(ttl_managementfee) ttl_managementfee, sum(ttl_profit) ttl_profit from (select cost_no,case when mattype = 'DEVELOPMENT' then total end as ttl_develop,
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
            from (SELECT cost_no,mattype,IF(curr = 'IDR',val_idr,val_usd) total from act_others where cost_date BETWEEN '$fromcri' and '$tocri') a) a GROUP BY cost_no) d on d.cost_no = a.cost_no ";



      $query = mysql_query($sql);
      $no = 1;
      $total_costing = 0;
      $total_bal = 0;
      while ($data = mysql_fetch_array($query)) {
        $ttl_material = $data[ttl_material];
        $ttl_manufacturing = $data[ttl_manufacturing];
        $ttl_others = $data[ttl_others];
        $price_so = $data[price_so];
        $total_costing = $ttl_material + $ttl_manufacturing + $ttl_others;
        $total_bal = $price_so - $total_costing;

        echo "<tr>";
        echo "<td>$no</td>";
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
        echo "<td>".number_format($data[ttl_fabric],4)."</td>";
        echo "<td>".number_format($data[ttl_accsew],4)."</td>";
        echo "<td>".number_format($data[ttl_accpack],4)."</td>";
        echo "<td>".number_format($data[ttl_material],4)."</td>";
        echo "<td>".number_format($data[ttl_cmt],4)."</td>";
        echo "<td>".number_format($data[ttl_embro],4)."</td>";
        echo "<td>".number_format($data[ttl_wash],4)."</td>";
        echo "<td>".number_format($data[ttl_print],4)."</td>";
        echo "<td>".number_format($data[ttl_wrapbut],4)."</td>";
        echo "<td>".number_format($data[ttl_compbut],4)."</td>";
        echo "<td>".number_format($data[ttl_label],4)."</td>";
        echo "<td>".number_format($data[ttl_laser],4)."</td>";
        echo "<td>".number_format($data[ttl_manufacturing],4)."</td>";
        echo "<td>".number_format($data[ttl_develop],4)."</td>";
        echo "<td>".number_format($data[ttl_overhead],4)."</td>";
        echo "<td>".number_format($data[ttl_market],4)."</td>";
        echo "<td>".number_format($data[ttl_shipp],4)."</td>";
        echo "<td>".number_format($data[ttl_import],4)."</td>";
        echo "<td>".number_format($data[ttl_handl],4)."</td>";
        echo "<td>".number_format($data[ttl_test],4)."</td>";
        echo "<td>".number_format($data[ttl_fabhandl],4)."</td>";
        echo "<td>".number_format($data[ttl_service],4)."</td>";
        echo "<td>".number_format($data[ttl_clearcost],4)."</td>";
        echo "<td>".number_format($data[ttl_unexcost],4)."</td>";
        echo "<td>".number_format($data[ttl_managementfee],4)."</td>";
        echo "<td>".number_format($data[ttl_profit],4)."</td>";
        echo "<td>".number_format($data[ttl_others],4)."</td>";
        echo "<td>".number_format($total_costing,4)."</td>";
        echo "<td>".number_format($total_bal,4)."</td>";
        echo "</tr>";
            $no++; // menambah nilai nomor urut
          }
          // echo "<td>$data[tgl_input]</td>";
          ?>
        </tbody>
      </table>
    </div>
  </div>