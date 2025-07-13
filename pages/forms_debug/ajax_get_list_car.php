<?php
session_start();

include "../../include/conn.php";
include "../forms/fungsi.php";

$user = $_SESSION['username'];
$bppbnya = $_REQUEST['bppbnya'];
?>
<table id="examplefix3" class="display responsive" style="width:100%">
  <thead>
  <tr>
    <th>WS #</th>
    <th>Item</th>
    <th>Deskripsi</th>
    <th>Qty Req</th>
    <th>Unit</th>
    <th>Qty BKB</th>
  </tr>
  </thead>
  <tbody>
    <?php
    # QUERY TABLE
    $query = mysqli_query($con_new,"select a.id_jo,a.id_item,ac.kpno,mi.goods_code,
      mi.itemdesc,a.qty,a.unit,mi.id_gen     
      from bppb_req a inner join masteritem mi on a.id_item=mi.id_item 
      left join jo_det s on a.id_jo=s.id_jo 
      left join so on s.id_so=so.id 
      left join act_costing ac on so.id_cost=ac.id 
      where a.bppbno='$bppbnya' "); 
    $no = 1;
    while($data = mysqli_fetch_array($query))
    { 
      $sdhout = flookup_new($con_new,"sum(qty)","bppb","id_item='$data[id_item]' and id_jo='$data[id_jo]' and bppbno_req='$bppbnya'");
      if($sdhout>0)
      {
        $ket="X";
      }
      else
      {
        $ket="";
      }   
      echo "
      <tr>
        <td>$data[kpno]</td>
        <td>$data[goods_code]</td>
        <td>$data[itemdesc]</td>
        <td>$data[qty]</td>
        <td>$data[unit]</td>
        <td>
          $sdhout
          <input type='hidden' name ='ket[$no]' class='ketcl' id='ket$no' value='$ket' readonly>
        </td>
      </tr>";
      $no++;
    }
    ?>
  </tbody>
</table>