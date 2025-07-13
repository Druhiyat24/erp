<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$rs=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $nm_company=$rs['company'];
if(isset($_GET['txtfrom']))
{
  $txtfrom = fd($_GET['txtfrom']);
  $txtto = fd($_GET['txtto']);
}
else
{
  $txtfrom = fd($_POST['txtfrom']);
  $txtto = fd($_POST['txtto']);
}
if($excel=="N") 
{ echo "<a href='?mod=rptreqmat&dest=xls&txtfrom=$txtfrom&txtto=$txtto'>Save To Excel</a></br>"; }
if(isset($_GET['dest']))
{ $excel = "Y";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=lap_req_mat.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0");
}
?>
<div class="box">
  <div class="box-body">
    <table id="examplefix3" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>No</th>
        <th>Request #</th>
        <th>Request Date</th>
        <th>Buyer</th>
        <th>Style #</th>
        <th>WS #</th>
        <th>Item Name</th>
        <th>Qty Req</th>
        <th>Qty Out</th>
        <th>Satuan</th>
        <th>Sent To</th>
        <th>Created By</th>
        <th>No. BPPB</th>
        <th>Tgl. BPPB</th>
        <th>Time Laps</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("select a.username,bppbno,bppbdate,s.supplier,ac.kpno,ac.styleno,ms.supplier buyer,
          mi.itemdesc,a.qty qty_req,a.id_item,a.unit     
          from bppb_req a inner join mastersupplier s on a.id_supplier=s.id_supplier 
          inner join jo_det jod on a.id_jo=jod.id_jo 
          inner join so on jod.id_so=so.id 
          inner join act_costing ac on so.id_cost=ac.id 
          inner join mastersupplier ms on ac.id_buyer=ms.id_supplier 
          inner join masteritem mi on a.id_item=mi.id_item   
          where bppbdate between '$txtfrom' and '$txtto' 
          group by a.id_item,a.bppbno 
          order by bppbdate desc "); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { 
          $sql = "select if(bppbno_int!='',bppbno_int,bppbno) trans_no,bppbdate,sum(qty) qty_out from bppb where 
            bppbno_req='$data[bppbno]' and id_item='$data[id_item]' group by id_item";
          // echo "<br>".$sql;
          $rsbpb = mysqli_fetch_array(mysqli_query($con_new,$sql));
          $cekbppb = $rsbpb['trans_no'];
          $cektglbppb = $rsbpb['bppbdate'];
          if($cektglbppb!="")
          {
            $date1 = date_create($data['bppbdate']);
            $date2 = date_create($rsbpb['bppbdate']);
            $diff = date_diff($date1,$date2);
            $timediff = $diff->format("%a days");
            $qty_out = $rsbpb['qty_out'];
          }
          else
          {
            $timediff = 0;
            $qty_out = 0;
          }
          echo "<tr>";
            echo "
            <td>$no</td>
            <td>$data[bppbno]</td>
            <td>$data[bppbdate]</td>
            <td>$data[buyer]</td>
            <td>$data[styleno]</td>
            <td>$data[kpno]</td>
            <td>$data[itemdesc]</td>
            <td>$data[qty_req]</td>
            <td>$qty_out</td>
            <td>$data[unit]</td>
            <td>$data[supplier]</td>
            <td>$data[username]</td>
            <td>$cekbppb</td>
            <td>$cektglbppb</td>
            <td>$timediff</td>
          </tr>";
          $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
