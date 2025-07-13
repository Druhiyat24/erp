<?php 
if (isset($_GET['dest']))
{ $excel = "Y";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=lap_prod_status.xls");//ganti nama sesuai keperluan 
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
  $pilih = $_GET['pilih'];
  if (isset($_GET['id_buyer'])) {$buyer = $_GET['id_buyer'];} else {$buyer = "";}
  if (isset($_GET['status'])) {$status = $_GET['status'];} else {$status = "";}
}
else
{ $from = fd_view($_POST['txtfrom']);
  $to = fd_view($_POST['txtto']);
  $pilih = $_POST['txtpilih'];
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
  $status_sql=" and status='$status'";
}
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="N") { echo "<a href='?mod=$mod&from=".fd($from)."&to=".fd($to)."&id_buyer=".$buyer."&status=".$status."&pilih=".$pilih."&dest=xls'>Save To Excel</a></br>"; }
    if ($pilih=="Tgl. Delivery")
    { echo "Delivery Date : ".$from." - ".$to." Buyer ".$buyer_cap." Status ".$status_cap; 
      $fld="deldate_det";
    }
    else
    { echo "Tanggal Output Date : ".$from." - ".$to." Buyer ".$buyer_cap." Status ".$status_cap; 
      $fld="dateoutput";
    }
  echo "</div>";
echo "</div>";
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="Y") {$tbl_border="border='1'";} else {$tbl_border="";}
    echo "<table id='examplefix' $tbl_border class='display responsive' style='width:100%;font-size:12px;'>";
    	echo "<thead>";
        echo "<tr>";
          ?>
          <th>No</th>
          <th>WS #</th>
          <th>SO #</th>
          <?php if ($mod=="7v") { ?>
          <th>Buyer PO</th>
          <th>Dest</th>
          <th>Color</th>
          <th>Size</th>
          <?php } ?>
          <th>Qty Order</th>
          <th>Cutting</th>
          <th>Bal</th>
          <th>Secondary</th>
          <th>Bal</th>
          <th>Input Sewing</th>
          <th>Bal</th>
          <th>Sewing</th>
          <th>Bal</th>
          <th>QC Sewing</th>
          <th>Bal</th>
          <th>Packing</th>
          <th>Bal</th>
          <?php
        echo "</tr>";
      echo "</thead>";
      if ($mod=="7v") { $groupnya="co.id_so_det"; } else { $groupnya="ac.kpno"; }
        $sql="select ac.kpno,so.so_no,sum(co.qty) qtycut,so.buyerno,sod.dest,sod.color,sod.size,
          sum(sod.qty) qtyorder,so.unit,sum(tmp_sec.qtysec) qtysec,sum(tmp_sew_in.qtysewin) qtysewin,
          sum(tmp_sew.qtysew) qtysew,sum(tmp_qcsew.qtyqcsew) qtyqcsew,
          sum(tmp_pack.qtypack) qtypack
          from so inner join so_det sod on so.id=sod.id_so
          inner join act_costing ac on so.id_cost=ac.id
          left join cut_out co on sod.id=co.id_so_det
          left join 
          (select co.id_so_det,sum(co.qty) qtysec 
          from so inner join so_det sod on so.id=sod.id_so inner join mfg_out co 
          on sod.id=co.id_so_det where $fld between '".fd($from)."' 
          and '".fd($to)."' group by co.id_so_det) tmp_sec
          on co.id_so_det=tmp_sec.id_so_det
          left join 
          (select co.id_so_det,sum(co.qty) qtysewin 
          from so inner join so_det sod on so.id=sod.id_so inner join sew_in co 
          on sod.id=co.id_so_det where $fld between '".fd($from)."' 
          and '".fd($to)."' group by co.id_so_det) tmp_sew_in
          on co.id_so_det=tmp_sew_in.id_so_det
          left join 
          (select co.id_so_det,sum(co.qty) qtysew 
          from so inner join so_det sod on so.id=sod.id_so inner join sew_out co 
          on sod.id=co.id_so_det where $fld between '".fd($from)."' 
          and '".fd($to)."' group by co.id_so_det) tmp_sew
          on co.id_so_det=tmp_sew.id_so_det 
          left join 
          (select co.id_so_det,sum(co.qty) qtyqcsew 
          from so inner join so_det sod on so.id=sod.id_so inner join qc_out co 
          on sod.id=co.id_so_det where $fld between '".fd($from)."' 
          and '".fd($to)."' group by co.id_so_det) tmp_qcsew
          on co.id_so_det=tmp_qcsew.id_so_det
          left join 
          (select co.id_so_det,sum(co.qty) qtypack 
          from so inner join so_det sod on so.id=sod.id_so inner join pack_out co 
          on sod.id=co.id_so_det where $fld between '".fd($from)."' 
          and '".fd($to)."' group by co.id_so_det) tmp_pack
          on co.id_so_det=tmp_pack.id_so_det 
          where $fld between '".fd($from)."' and '".fd($to)."'
          group by $groupnya ";
        #echo $sql;
        $query = mysql_query($sql); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { echo "<tr>";
            echo "<td>$no</td>";
            if ($mod=="7sv")
            { echo "
              <td>
                <a href='#' class='edit-record' data-id=$data[kpno] 
                  data-toggle='tooltip' title='Detail'>$data[kpno]
                </a>
              </td>";
            }
            else
            { echo "<td>$data[kpno]</td>";  }
            echo "<td>$data[so_no]</td>";
            if ($mod=="7v")
            { echo "<td>$data[buyerno]</td>";
              echo "<td>$data[dest]</td>";
              echo "<td>$data[color]</td>";
              echo "<td>$data[size]</td>";
            }
            $balcut=$data['qtycut']-$data['qtyorder'];
            $balsec=$data['qtysec']-$data['qtyorder'];
            $balsewin=$data['qtysewin']-$data['qtyorder'];
            $balqcsew=$data['qtyqcsew']-$data['qtyorder'];
            $balsew=$data['qtysew']-$data['qtyorder'];
            $balpac=$data['qtypack']-$data['qtyorder'];
            echo "
              <td>$data[qtyorder]</td>
              <td>$data[qtycut]</td>
              <td>$balcut</td>
              <td>$data[qtysec]</td>
              <td>$balsec</td>
              <td>$data[qtysewin]</td>
              <td>$balsewin</td>
              <td>$data[qtysew]</td>
              <td>$balsew</td>
              <td>$data[qtyqcsew]</td>
              <td>$balqcsew</td>
              <td>$data[qtypack]</td>
              <td>$balpac</td>";
          echo "</tr>";
          $no++; 
        }
      echo "</tbody>";
    echo "</table>";
  echo "</div>";
echo "</div>";
?>  