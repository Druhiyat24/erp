<?php 
if(isset($_GET['dest']))
{ $excel = "Y";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=lap_cost_vs_sales.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0");
}
else
{ $excel = "N"; }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$mod = $_GET['mod'];
if ($excel=="Y")
{ $from = fd_view($_GET['from']);
  $to = fd_view($_GET['to']);
  if (isset($_GET['id_buyer'])) {$buyer = $_GET['id_buyer'];} else {$buyer = "";}
  if (isset($_GET['status'])) {$status = $_GET['status'];} else {$status = "";}
}
else
{ $from = fd_view($_POST['txtfrom']);
  $to = fd_view($_POST['txtto']);
  if (isset($_POST['txtid_buyer'])) {$buyer = $_POST['txtid_buyer'];} else {$buyer = "";}
  if (isset($_POST['txtstatus'])) {$status = $_POST['txtstatus'];} else {$status = "";}
}
if ($buyer=="") 
{ $buyer_cap="All"; 
  $buyer_sql="";
} 
else 
{ $buyer_cap=flookup("supplier","mastersupplier","id_supplier='$buyer'");
  $buyer_sql=" and id_buyer='$buyer'";
}
if ($status=="") 
{ $status_cap="All"; 
  $status_sql="";
} 
else 
{ $status_cap=$status;
  if($status=="CANCEL")
  {
    $status_sql=" and so.cancel_h='Y' ";
  }
  else
  {
    $status_sql=" and so.cancel_h='N' ";
  }
}
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="N") { echo "<a href='?mod=$mod&from=".fd($from)."&to=".fd($to)."&id_buyer=".$buyer."&status=".$status."&dest=xls'>Save To Excel</a></br>"; }
    echo "Delivery Date : ".$from." - ".$to." Buyer ".$buyer_cap." Status ".$status; 
  echo "</div>";
echo "</div>";
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="Y") {$tbl_border="border='1'";} else {$tbl_border="";}
    echo "<table id='examplefix' $tbl_border class='display responsive' style='width:100%;font-size:10px;'>";
    	echo "<thead>";
        echo "<tr>";
          ?>
          <th>No</th>
          <th>Costing #</th>
          <th>Costing Date</th>
          <th>Buyer</th>
          <th>Style #</th>
          <th class="header columnsort">Delv. Date</th>
          <th>Product Group</th>
          <th>Product Item</th>
          <th>WS #</th>
          <th>SO #</th>
          <th>SO Date</th>
          <th>Buyer PO</th>
          <th>Qty Costing</th>
          <th>Qty Sales</th>
          <th>Unit</th>
          <th>Fob Costing</th>
          <th>Fob Sales</th>
          <th>Val Costing</th>
          <th>Val Sales</th>
          <th>Est P/L</th>
          <th>CM Px</th>
          <th>Mkt</th>
          <?php
        echo "</tr>";
      echo "</thead>";
        $que="select a.deal_allow,a.vat,a.ga_cost,a.id,cost_no,cost_date,supplier,styleno,
          deldate,a.status,mp.product_group,mp.product_item,a.kpno,a.qty qty_cost,
          so.qty qty_so,a.unit,so.fob px_so,so.tax tax_so, 
          ms.shipmode,up.kode_mkt,so.so_no,so.so_date,so.buyerno,a.cfm_price,a.curr cfm_price_curr,
          so.cancel_h from 
          act_costing a inner join mastersupplier f on a.id_buyer=f.id_supplier
          inner join masterproduct mp on a.id_product=mp.id
          inner join mastershipmode ms on a.id_smode=ms.id
          inner join userpassword up on a.username=up.username
          left join so on a.id=so.id_cost
          where deldate between '".fd($from)."' and '".fd($to)."' $buyer_sql $status_sql ";
        #echo $que;
        $sql=mysql_query($que);
        $no = 1;
        $t_cost = 0;
        $t_sales = 0; 
        while($rs = mysql_fetch_array($sql))
        { $id_item=$rs['id'];
          $ga=$rs['ga_cost'];
          $vat=$rs['vat'];
          $deal=$rs['deal_allow'];
          $deldate = $rs['deldate'];
          // $tot_cd = flookup("sum((if(jenis_rate='B',price/rate_beli,price)*cons)+((if(jenis_rate='B',price/rate_beli,price)*cons)*allowance/100))",
          //     "act_costing_mat a inner join act_costing s on a.id_act_cost=s.id
          //     inner join masterrate d on 'USD'=d.curr and s.cost_date=d.tanggal","id_act_cost='$id_item'");
          $tot_mf = flookup("sum(round((if(jenis_rate='B',price/rate_beli,price)*cons)+((if(jenis_rate='B',price/rate_beli,price)*cons)*allowance/100),4))",
              "act_costing_mfg a inner join act_costing s on a.id_act_cost=s.id 
              inner join mastercf mcf on a.id_item=mcf.id 
              inner join masterrate d on 'USD'=d.curr and '".fd($deldate)."'=d.tanggal","id_act_cost='$id_item' 
              and cfcode='CMT' and v_codecurr IN('COSTING3','COSTING6','COSTING8','COSTING12')");
          // $tot_ot = flookup("sum(if(jenis_rate='B',price/rate_beli,price))",
          //     "act_costing_oth a inner join act_costing s on a.id_act_cost=s.id
          //     inner join masterrate d on 'USD'=d.curr and s.cost_date=d.tanggal","id_act_cost='$id_item'");
          // $total_ga_cost = ($tot_cd + $tot_mf + $tot_ot) * $ga/100;
          // $total_cost = $tot_cd + $tot_mf + $tot_ot + $total_ga_cost;
          // $total_vat = (($total_cost+$total_ga_cost)*$vat/100);
          // $total_deal = (($total_cost+$total_vat+$total_ga_cost)*$deal/100);
          // $total_cost_plus = $total_cost + $total_vat + $total_deal + $total_ga_cost;
          // $px_cost=$total_cost_plus;
          $px_cost = $rs['cfm_price'];
          #$val_cost=$px_cost*$rs['qty_cost'];
          $val_cost=$px_cost*$rs['qty_so'];
          if($rs['tax_so']>0)
          {
            $val_so=($rs['px_so']+($rs['px_so']*$rs['tax_so']/100)) * $rs['qty_so'];
            $px_so = ($rs['px_so']+($rs['px_so']*$rs['tax_so']/100));
          }
          else
          {
            $val_so=$rs['px_so']*$rs['qty_so'];
            $px_so = $rs['px_so'];
          }
          $val_bal=$val_so-$val_cost;
          if($rs['cancel_h']=="Y")
          {
            $bgcol=" style='color: red; font-weight:bold;'";
          }
          else if($val_bal<0) 
          {
            $bgcol=" style='color: blue; font-weight:bold;'";
          } 
          else 
          {
            $bgcol="";
          }
          echo "<tr $bgcol>
            <td>$no</td>
            <td>$rs[cost_no]</td>
            <td>".fd_view($rs['cost_date'])."</td>
            <td>$rs[supplier]</td>
            <td>$rs[styleno]</td>
            <td>".fd_view($rs['deldate'])."</td>
            <td>$rs[product_group]</td>
            <td>$rs[product_item]</td>
            <td>$rs[kpno]</td>
            <td>$rs[so_no]</td>
            <td>".fd_view($rs['so_date'])."</td>
            <td>$rs[buyerno]</td>
            <td>".fn($rs['qty_cost'],0)."</td>
            <td>".fn($rs['qty_so'],0)."</td>
            <td>$rs[unit]</td>
            <td>".fn($px_cost,2)."</td>
            <td>".fn($px_so,2)."</td>
            <td>".fn($val_cost,2)."</td>
            <td>".fn($val_so,2)."</td>
            <td>".fn($val_bal,2)."</td>
            <td>".fn($tot_mf,4)."</td>
            <td>$rs[kode_mkt]</td>";
          echo "</tr>";
          $t_cost = $t_cost + $rs['qty_cost'];
          $t_sales = $t_sales + $rs['qty_so'];
          $no++; // menambah nilai nomor urut
        }
      echo "</tbody>";
    echo "</table>";
    echo "Total Qty Costing : ".fn($t_cost,0)."";
    echo "<br>Total Qty Sales : ".fn($t_sales,0)."";
    echo "<br><i style='color: red; font-weight:bold;'>Cancel SO</i>";
    echo " <i style='color: blue; font-weight:bold;'>Value SO < Value Costing</i>";
  echo "</div>";
echo "</div>";
?>  